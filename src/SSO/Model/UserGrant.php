<?php
namespace Dcux\SSO\Model;

use Lay\Advance\Core\Model;

class UserGrant extends Model
{
    protected $uid = '';
    protected $isSuper = '';
    protected $grants = '';
    /**
     * 返回模型对应数据表名或其他数据库中的集合名称
     * @return string
     */
    public function table()
    {
        return "user_grant";
    }
    /**
     * 返回模型属性名与对应数据表字段的映射关系数组
     * @return array
     */
    public function columns()
    {
        return array(
                'uid' => 'uid',
                'isSuper' => 'is_super',
                'grants' => 'grants'
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
