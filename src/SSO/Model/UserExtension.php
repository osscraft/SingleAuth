<?php
namespace Dcux\SSO\Model;

use Lay\Advance\core\Model;

class UserExtension extends Model
{
    protected $uid = '';
    protected $lastLogin = '';
    protected $lastClientId = '';
    protected $lastIp = 0;
    protected $lastOs = '';
    protected $lastBrowser = '';
    protected $lastUa = '';
    /**
     * 返回模型对应数据表名或其他数据库中的集合名称
     * @return string
     */
    public function table()
    {
        return "user_extension";
    }
    /**
     * 返回模型属性名与对应数据表字段的映射关系数组
     * @return array
     */
    public function columns()
    {
        return array(
                'uid' => 'uid',
                'lastLogin' => 'last_login',
                'lastClientId' => 'last_client_id',
                'lastIp' => 'last_ip',
                'lastOs' => 'last_os',
                'lastBrowser' => 'last_browser',
                'lastUa' => 'last_ua'
        );
    }
    /**
     * 返回模型属性名对应数据表主键字段名
     * @return array
     */
    public function primary()
    {
        return "uid";
    }
    /**
     * 返回模型对应数据表所在数据库名
     * @return string
     */
    public function schema()
    {
        return "sso";
    }
}
// PHP END
