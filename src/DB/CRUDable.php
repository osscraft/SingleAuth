<?php

namespace Dcux\DB;

interface CRUDable {
    /**
     * 获取某条记录
     * 
     * @param int|string $id
     *            ID
     * @return array
     */
    public function get($id, $fields = array());
    /**
     * 删除某条记录
     * 
     * @param int|string $id
     *            ID
     * @return boolean
     */
    public function del($id);
    /**
     * 增加一条记录
     * 
     * @param array $info
     *            数据数组
     * @return boolean
     */
    public function add(array $info);
    
    /**
     * 更新某条记录
     * 
     * @param int|string $id
     *            ID
     * @param array $info
     *            数据数组
     * @return boolean
     */
    public function upd($id, array $info);
    
    /**
     * 某些条件下的记录数
     * 
     * @param array $info
     *            数据数组
     * @return int
     */
    public function count(array $info = array());
    /**
     * 增加或替换一条记录
     * 
     * @param array $info
     *            数据数组
     * @return int
     */
    public function replace(array $info = array());
}
// PHP END