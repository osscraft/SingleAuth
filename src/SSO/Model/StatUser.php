<?php
namespace Dcux\SSO\Model;

use Lay\Advance\core\Model;

class StatUser extends Model
{
    protected $id = 0;
    protected $username = '';
    protected $count = 0;
    protected $date = '';
    /**
     * 返回模型对应数据表名或其他数据库中的集合名称
     * @return string
     */
    public function table()
    {
        return 'stat_user';
    }
    /**
     * 返回模型属性名与对应数据表字段的映射关系数组
     * @return array
     */
    public function columns()
    {
        return array(
                'id' => 'id',
                'username' => 'username',
                'count' => 'count',
                'date' => 'date'
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
