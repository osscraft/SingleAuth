<?php

namespace Dcux\SSO\Service;

use Lay\Advance\Core\App;
use Lay\Advance\Core\Service;
use Lay\Advance\Util\Logger;
use Lay\Advance\Util\EventEmitter;
use Lay\Advance\Util\Utility;
use Lay\Advance\Core\Action;
use Lay\Advance\DB\Uniqueness;
use Lay\Advance\Util\Paging;

use Dcux\SSO\OAuth2\OAuth2;
use Dcux\SSO\Model\Client;
use Dcux\SSO\Service\StatService;
use Dcux\SSO\Service\UserService;

class ClientService extends Service {
    protected $userService;
    protected function __construct() {
        parent::__construct();
        $this->userService = UserService::getInstance();
    }
    public function model() {
        return Client::getInstance();
    }
    public function get($id, $secret = false) {
        $ret = parent::get($id);
        if(empty($secret)) {
            unset($ret['clientSecret']);
        }
        return $ret;
    }
    public function getByUnique($unique, $secret = false) {
        $ret = parent::getByUnique($unique);
        if(empty($secret)) {
            unset($ret['clientSecret']);
        }
        return $ret;
    }

    public static function readClient($id, $secret = false) {
        return self::getInstance()->get($id, $secret);
    }
    public static function readClientByRole($role) {
        return self::getInstance()->getClientListByRole($role);
    }
    public static function readClientMessages() {
        return self::getInstance()->getClientListByShow();
    }
    public static function readClientByWord($word) {
        return self::getInstance()->getClientListByWord($word);
    }
    public static function readClientsByGroup($group) {
        return self::getInstance()->getClientListByUnique($group);
    }
    public static function readClientPaging() {
        $p = new Paging();
        return $p->build($_REQUEST);
    }
    public static function readClientList($paging = array()) {
        $paging = $paging instanceof Paging ? $paging : self::readClientPaging();
        return self::getInstance()->getClientListPaging(array(), array('id' => 'ASC'), $paging->toLimit());
    }
    public static function readClientTotal($paging = array()) {
        $paging = $paging instanceof Paging ? $paging : self::readClientPaging();
        $ret = self::getInstance()->count();
        return empty($ret) ? 0 : $ret;
    }
    public static function updateClient($id, $info = array()) {
        return empty($id) || empty($info) ? false : self::getInstance()->upd($id, $info);
    }
    public static function deleteClient($id) {
        return self::getInstance()->del($id);
    }
    public static function createClient($info) {
        return self::getInstance()->add($info);
    }

    /**
     * 检测客户端的合法性
     */
    public function checkClient($clientId, $clientType = '', $redirectURI = '', $clientSecret = null) {
        $client = $this->getByUnique($clientId, empty($clientSecret) ? false : true);
        if(empty($client)) {
            return false;
        } else if(!empty($clientType)) {
            $pClientType = $this->parseType($client['clientType']);
            $_pClientType = $this->parseType($clientType);
            $twoType = array(OAuth2::CLIENT_TYPE_WEB, OAuth2::CLIENT_TYPE_IMPLICIT, OAuth2::CLIENT_TYPE_DESKTOP);
            if($pClientType != OAuth2::CLIENT_TYPE_MOBILE && $pClientType != $_pClientType) {
                // 非移动应用只对应一种方式，类型唯一
                return false;
            } else if($pClientType == OAuth2::CLIENT_TYPE_MOBILE && !in_array($_pClientType, $twoType)) {
                // 移动应用对应三种方式
                return false;
            }
        } else if(!empty($redirectURI) && trim($client['redirectURI']) != trim($redirectURI)) {
            return false;
        } else if(!empty($clientSecret) && $client['clientSecret'] != $clientSecret) {
            return false;
        }
        return $client;
    }
    /**
     * 将类型转换为对应数值
     */
    public function parseType($type) {
        $type = strtoupper($type);
        switch ($type) {
            case OAuth2::CLIENT_TYPE_WEB:
            case 'WEBAPP':
            case 'WEB应用':
                $t = OAuth2::CLIENT_TYPE_WEB;
                break;
            case OAuth2::CLIENT_TYPE_DESKTOP:
            case 'DESKTOPAPP':
            case 'DESKTOP应用':
            case '桌面应用':
                $t = OAuth2::CLIENT_TYPE_DESKTOP;
                break;
            case OAuth2::CLIENT_TYPE_IMPLICIT:
            case 'JSAPP':
            case 'JS应用':
                $t = OAuth2::CLIENT_TYPE_IMPLICIT;
                break;
            case OAuth2::CLIENT_TYPE_MOBILE:
            case 'MOBILEAPP':
            case 'MOBILE应用':
            case '移动应用':
                $t = OAuth2::CLIENT_TYPE_MOBILE;
                break;
            default:
                $t = OAuth2::CLIENT_TYPE_WEB;
                break;
        }
        return $t;
    }
    /**
     * 将类型转换为对应字符串
     */
    public function deparseType($type) {
        $type = strtoupper($type);
        switch ($type) {
            case '1':
            case 'WEBAPP':
            case 'WEB应用':
                $t = 'webApp';
                break;
            case '2':
            case 'DESKTOPAPP':
            case '桌面应用':
                $t = 'desktopApp';
                break;
            case '3':
            case 'JSAPP':
            case 'JS应用':
                $t = 'jsApp';
                break;
            case '4':
            case 'MOBILEAPP':
            case '移动应用':
                $t = 'mobileApp';
                break;
            default:
                $t = 'webApp';
                break;
        }
        return $t;
    }
    /**
     * 通过多个客户端ID获取客户端列表
     * @param array $condition
     * @param array $limit
     * @return array
     */
    public function getClientListByUnique($uniques = array(), $assoc = true, $secret = false) {
        $arr = array();
        foreach ($uniques as $u) {
            $ret = $this->getByUnique($u, $secret);
            if(!empty($ret) && empty($assoc)) {
                $arr[] = $ret;
            } else if(!empty($ret)) {
                $arr[$u] = $ret;
            }
        }
        return $arr;
    }
    /**
     * 分页获取客户端列表
     * @param array $condition
     * @param array $limit
     * @return array
     */
    public function getClientListPaging($condition = array(), $order = array(), $limit = array()) {
        $ret = $this->model()->db()->select(array(), $condition, $order, $limit, false);
        return empty($ret) ? array() : $ret;
    }
    /**
     * 获取所有客户端列表
     * @param array $condition
     * @param array $limit
     * @return array
     */
    public function getClientListAll($condition = array(), $order = array()) {
        $ret = $this->model()->db()->select(array(), $condition, $order, array(), false);
        return empty($ret) ? array() : $ret;
    }
    /**
     * 获取在首页显示的客户端列表
     * @param int|string $role
     * @return array
     */
    public function getClientListByShow() {
        $fIsShow = $this->model()->toField('clientIsShow');
        $condition = array();
        $condition[$fIsShow] = array('0', '>');
        $ret = $this->getQueryList($condition);
        return empty($ret) ? array() : $ret;
    }
    /**
     * 通过关键字获取相应的客户端列表
     * @param int|string $role
     * @return array
     */
    public function getClientListByWord($word, $limit = array()) {
        $fClientId = $this->model()->toField('clientId');
        $fClientName = $this->model()->toField('clientName');
        $fields = $this->model()->toFields();
        $condition = array();
        $condition[$fIsShow] = array('0', '>');
        $condition[$fClientId] = array($word, 'LIKE');
        $condition[$fClientName] = array($word, 'LIKE', 'OR');
        $ret = $this->model()->db()->select($fields, $condition);
        return empty($ret) ? array() : $ret;
    }
    /**
     * 通过角色类型获取相应的客户端列表
     * @param int|strin $role
     * @return array
     */
    public function getClientListByRole($role) {
        $pk = $this->model()->primary();
        $fIsShow = $this->model()->toField('clientIsShow');
        $fVisible = $this->model()->toField('clientVisible');
        $fOrderNum = $this->model()->toField('clientOrderNum');
        $fields = $this->model()->toFields();
        $role = $this->parseRole($role);
        $condition = array();
        $condition[$fIsShow] = array('0', '>');
        $condition[$fVisible] = array(array('0', "$role"), 'IN');
        $order = array();
        $order[$fOrderNum] = 'DESC';
        $order[$fIsShow] = 'DESC';
        $order[$pk] = 'DESC';
        $fields = array_values(array_diff($fields, array (
                $this->model()->toField('clientSecret')
        )));
        $ret = $this->model()->db()->select($fields, $condition, $order);
        return empty($ret) ? array() : $ret;
    }
    public function getVisitCount() {

    }

    /**
     * parse role type
     */
    public function parseRole($role) {
        return $this->userService->parseRole($role);
    }
    public function parseLdapRole($role) {
        return $this->userService->parseLdapRole($role);
    }

    // static task
    public function updateOrderNum($period = 7) {
        // default one week
        $clients = $this->getClientListByShow();
        foreach ($clients as $k => $c) {
            $client_id = $c['clientId'];
            $order_num = $this->updateClientOrderNum($c, $period);
            $clients[$k]['clientOrderNum'] = $order_num;
        }
        return $clients;
    }
    public function updateClientOrderNum($client, $period = 7) {
        $fOrderNum = $this->model()->toField('clientOrderNum');
        $clientId = $client['clientId'];
        $id = $client['id'];
        $statClient = StatService::getInstance()->getStatClientByPeriod($clientId, $period);
        $order_num = empty($statClient['count']) ? 0 : $statClient['count'];
        $raw_num = $client['clientOrderNum'];
        $num = $order_num - $raw_num;
        $ret = $this->model()->upd($id, array('clientOrderNum' => $order_num));
        //$ret = $this->model()->db()->increase($clientId, $order_num);
        return empty($ret) ? false : $order_num;
    }
}
// PHP END