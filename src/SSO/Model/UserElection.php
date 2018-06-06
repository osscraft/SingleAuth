<?php
namespace Dcux\SSO\Model;

use Lay\Advance\Core\ModelUnique;

class UserElection extends ModelUnique
{
    protected $id = 0;
    protected $uid = '';
    protected $client_id = '';
    protected $time = '';
    public function unique()
    {
        return array('uid', 'client_id');
    }
    /**
     * 返回模型对应数据表名或其他数据库中的集合名称
     * @return string
     */
    public function table()
    {
        return "user_election";
    }
    /**
     * 返回模型属性名与对应数据表字段的映射关系数组
     * @return array
     */
    public function columns()
    {
        return array(
                'id' => 'id',
                'uid' => 'uid',
                'clientId' => 'client_id',
                'time' => 'time'
        );
    }
    /**
     * 返回模型属性名对应数据表主键字段名
     * @return array
     */
    public function primary()
    {
        return "id";
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
