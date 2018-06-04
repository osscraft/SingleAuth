<?php
namespace Lay\Advance\Core;

use Lay\Advance\Core\Bean;
use Lay\Advance\DB\CRUDable;

interface Modelizable extends CRUDable {
    public function save();
    /**
     * 返回模型对应数据表名或其他数据库中的集合名称
     * @return string
     */
    public function table();
    /**
     * 返回模型属性名与对应数据表字段的映射关系数组
     * @return array
     */
    public function columns();
    /**
     * 返回模型属性名对应数据表主键字段名
     * @return array
     */
    public function primary();
    /**
     * 返回模型对应数据表所在数据库名
     * @return string
     */
    public function schema();
    /**
     * 返回对象属性名对属性值的数组
     * @return array
     */
    public function toArray();
}
// PHP END