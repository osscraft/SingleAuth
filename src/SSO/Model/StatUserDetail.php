<?php
namespace Dcux\SSO\Model;

use Lay\Advance\core\Model;

class StatUserDetail extends Model{
    const LOGIN_BY_PASSWORD = 0;
    const LOGIN_BY_SESSION = 1;
    const LOGIN_BY_SID = 2;
    const LOGIN_BY_QR = 3;
    const LOGIN_BY_DELAY = 4;
    protected $id = 0;
    protected $time = '';
    protected $username = '';
    protected $clientId = '';
    protected $success = 0;
    protected $loginBy = 0;
    protected $isPassword = 0;
    protected $ip = 0;
    protected $os = '';
    protected $browser = '';
    protected $ua = '';
    protected $referer = '';
    /**
     * 返回模型对应数据表名或其他数据库中的集合名称
     * @return string
     */
    public function table(){
		return "stat_user_detail";
	}
    /**
     * 返回模型属性名与对应数据表字段的映射关系数组
     * @return array
     */
    public function columns(){
		return array (
                'id' => 'id',
                'time' => 'time',
                'username' => 'username',
                'clientId' => 'client_id',
                'success' => 'success',
                'loginBy' => 'login_by',
                'isPassword' => 'is_password',
                'ip' => 'ip',
                'os' => 'os',
                'browser' => 'browser',
                'ua'=>'ua',
                'referer'=>'referer'
        );
	}
    /**
     * 返回模型属性名对应数据表主键字段名
     * @return array
     */
    public function primary(){
		return "id";
	}

    /**
     * 返回模型对应数据表所在数据库名
     * @return string
     */
    public function schema(){
		return "sso";
	}
}
// PHP END