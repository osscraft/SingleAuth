<?php

namespace Lay\Advance\DB;

use Lay\Advance\DB\Cacher;
use Lay\Advance\DB\Uniqueness;
use Lay\Advance\Core\Volatile;
use Lay\Advance\Util\Logger;
use Lay\Advance\Core\Configuration;
use Lay\Advance\DB\Querying;

class ConfigCacher extends Cacher {
    /**
     * 数据库连接源
     *
     * @var  $link
     */
    protected $link;
    /**
     * 查询产生的结果集
     *
     * @var mixed $result
     */
    protected $result;
    protected function __construct() {
    }
    /**
     * connect memcache database.
     * new instance.
     */
	public function connect() {
        return false;
	}
    /**
     * 选择数据库名称
     *
     * @return mixed
     */
    public function choose($dbname) {
    	return true;
    }
    /**
     * another connect
     * new instance.
     */
    public function alter($name = 'default') {
        return false;
    }
	public function close() {
	}
    public final function get($id, $fields = array()) {
        return false;
    }
    public final function add(array $info, $use_last_id = true) {
        return $this->remove();
    }
    public final function del($id) {
        return $this->remove();
    }
    public final function upd($id, array $info) {
        return false;
    }
    protected function remove() {
        Configuration::cleanCache();
        $db = $this->model->db();
        if($db instanceof Querying) {
            $settings = $this->model->db()->select(array(), array(), array('k' => 'ASC'), array(), false);
            //Configuration::$_caches=array();
            foreach($settings as $key => $val){
                Configuration::setCache($val['k'],$val['v']);
            }
            Configuration::updateCache(true);
        }
        return true;
    }
}
// PHP END