<?php
namespace Dcux\Cli\Service;

use Lay\Advance\Core\Service;
use Lay\Advance\Util\Logger;

use Dcux\Cli\Model\TransferUser;
use Dcux\Cli\Model\TransferUsers;
use Dcux\Cli\Model\TransferSetting;
use Dcux\Cli\Service\TransferUserService;
use Dcux\Cli\Service\TransferUsersService;
use Dcux\Cli\Service\TransferSettingService;

class TransferService extends Service
{
    private $user;
    private $userService;
    private $users;
    private $usersService;
    private $setting;
    private $settingService;
    private $ds;
    private $bind;
    protected function __construct()
    {
        parent::__construct();
        $this->user = TransferUser::getInstance();
        $this->users = TransferUsers::getInstance();
        $this->setting = TransferSetting::getInstance();
        $this->userService = TransferUserService::getInstance();
        $this->usersService = TransferUsersService::getInstance();
        $this->settingService = TransferSettingService::getInstance();
    }
    public static function getInstance()
    {
        $instance = parent::getInstance();
        return $instance;
    }
    // base users
    public function model()
    {
        return $this->users;
    }

    public function sync()
    {
        global $CFG;
        $lock = $this->setting->get('sync_locked');
        if (!empty($lock)) {
            return true;
        } else {
            $this->setting->replace(array('k' => 'sync_locked', 'v' => 1));
        }
        $last = $this->setting->get('sync_last_time');
        // 获取最后更新时间
        if (empty($last)) {
            //不存在最后更新时间，使用1天前
            $last_time = date('Y-m-d H:i:s', time() - 86400);
        } else {
            $last_time = $last['v'];
        }

        $top_time = date('Y-m-d H:i:s');
        $offset = 0;
        $num = 100;
        do {
            $condtion = array();
            $condtion['create_time.0'] = array($last_time, '>=');
            $condtion['create_time.1'] = array($top_time, '<=');
            $order = array();
            $limit = array($offset, $num);
            $list = $this->usersService->getQueryList($condtion, $order, $limit);
            //释放
            $free = $this->usersService->freeResult();

            if (empty($list)) {
                // 跳出
                break;
            } else {
                if (empty($this->ds)) {
                    $this->ds = ldap_connect($CFG['ldap_host']);
                    ldap_set_option($this->ds, LDAP_OPT_PROTOCOL_VERSION, 3);
                    $this->bind = @ldap_bind($this->ds, $CFG['ldap_name'], $CFG['ldap_pass']);
                }
            }

            if (empty($this->bind)) {
                $this->setting->del('sync_locked');
                // 直接返回
                return false;
            }

            foreach ($list as $k => $current) {
                // 当前userid
                $userid = $current['userid'];
                // 历史数据
                $history = $this->userService->get($userid);
                //释放
                $free = $this->userService->freeResult();
                // 是否有历史更新数据
                if (empty($history)) {
                    // 没有历史数据
                    if ($current['status'] >= 1) {
                        // 添加
                        $this->addUser($current);
                    } else {
                        // 删除
                        $this->delUser($current['userid']);
                    }
                } else {
                    // 有历史数据
                    if ($history['status'] == $current['status']) {
                        // 相同状态时不做任务操作
                    } elseif ($history['status'] >= 1) {
                        // 删除
                        $this->delUser($current['userid']);
                    } elseif ($current['status'] >= 1) {
                        // 添加
                        $this->addUser($current);
                    }
                }
                // 增加 或更新历史
                $this->userService->replace($current);
            }

            $count = count($list);
            $offset += $num;
        } while ($count >= $num);

        // 更新最后更新时间
        $info = array();
        $info['k'] = 'sync_last_time';
        $info['v'] = date('Y-m-d H:i:s');

        if (!empty($this->ds)) {
            ldap_close($this->ds);
        }
        $this->setting->del('sync_locked');
        return $this->setting->replace($info);
    }

    protected function addUser($info)
    {
        global $CFG;
        $userid = $info['userid'];
        $role = $info['role'];
        $pass = $info['password'];
        $name = $info['name'];
        if ($role == "教师") {
            $ou="teacher";
        } elseif ($role == "学生") {
            $ou="student";
        } else {
            $ou="other";
        }
        $bn_sso = "o=sso,dc=ldap,dc=lixin,dc=edu,dc=cn";
        $bn_db = "o=centerDB,dc=ldap,dc=lixin,dc=edu,dc=cn";
        $filter_sso = "userid=$userid";
        $filter_db = "cn=$userid";
        // search dn
        $result_sso = @ldap_search($this->ds, $bn_sso, $filter_sso, array("userid","username","role"));
        $entry_sso = @ldap_get_entries($this->ds, $result_sso);
        $s_dn_sso = empty($entry_sso) || empty($entry_sso[0]) ? false : $entry_sso[0]['dn'];
        $result_db = @ldap_search($this->ds, $bn_db, $filter_db, array("cn","sn","employeeType"));
        $entry_db = @ldap_get_entries($this->ds, $result_db);
        $s_dn_db = empty($entry_db) || empty($entry_db[0]) ? false : $entry_db[0]['dn'];

        $dn_sso = "userid=$userid,ou=$ou,o=sso,dc=ldap,dc=lixin,dc=edu,dc=cn";
        $password = "{md5}".(base64_encode(pack('H*', md5($pass))));
        $info_sso = array(
            "userid"=>$userid,
            "userPassword"=>$password,
            "role"=>$role,
            "username"=>$name,
            "objectClass"=>array("top","user")
        );
        $dn_db="cn=$userid,ou=024,o=centerDB,dc=ldap,dc=lixin,dc=edu,dc=cn";
        $info_db = array(
            "cn"=>$userid,
            "userPassword"=>$password,
            "sn"=>$name,
            "employeeType"=>$role,
            "objectClass"=>array("top","person","organizationalPerson","inetOrgPerson")
        );

        $ret_sso = !$s_dn_sso && ldap_add($this->ds, $dn_sso, $info_sso);
        $ret_db = !$s_dn_db && ldap_add($this->ds, $dn_db, $info_db);
        $loginfo = array(
            'bind' => $this->bind,
            'ret_sso' => $ret_sso,
            'dn_sso' => $dn_sso,
            'info_sso' => $info_sso,
            'ret_db' => $ret_db,
            'dn_db' => $dn_db,
            'info_db' => $info_db
        );
        // info log
        Logger::info('add '. json_encode($loginfo), 'sync');
        return empty($ret_sso) || empty($ret_db) ? false : true;
    }
    protected function delUser($uid)
    {
        global $CFG;
        $bn_sso = "o=sso,dc=ldap,dc=lixin,dc=edu,dc=cn";
        $bn_db = "o=centerDB,dc=ldap,dc=lixin,dc=edu,dc=cn";
        $filter_sso = "userid=$uid";
        $filter_db = "cn=$uid";
        // search dn
        $result_sso = @ldap_search($this->ds, $bn_sso, $filter_sso, array("userid","username","role"));
        $entry_sso = @ldap_get_entries($this->ds, $result_sso);
        $dn_sso = empty($entry_sso) || empty($entry_sso[0]) ? false : $entry_sso[0]['dn'];
        $result_db = @ldap_search($this->ds, $bn_db, $filter_db, array("cn","sn","employeeType"));
        $entry_db = @ldap_get_entries($this->ds, $result_db);
        $dn_db = empty($entry_db) || empty($entry_db[0]) ? false : $entry_db[0]['dn'];
        // do delete
        $ret_sso =  $dn_sso && ldap_delete($this->ds, $entry_sso[0]['dn']);
        $ret_db = $dn_db && ldap_delete($this->ds, $entry_db[0]['dn']);
        $loginfo = array(
            'bind' => $this->bind,
            'ret_sso' => $ret_sso,
            'dn_sso' => $dn_sso,
            'ret_db' => $ret_db,
            'dn_db' => $dn_db
        );
        // info log
        Logger::info('del '. json_encode($loginfo), 'sync');
        return empty($ret_sso) || empty($ret_db) ? false : true;
    }
}
// PHP END
