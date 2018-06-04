<?php

namespace Lay\Advance\DB;

use Lay\Advance\DB\Cacher;
use Lay\Advance\DB\Uniqueness;
use Lay\Advance\Core\Volatile;
use Lay\Advance\Util\Logger;

class Memcache extends Cacher implements Uniqueness {
    const NON_TTL = 0;//没有过期时间
    const MIN_TTL = 100;
    const MAX_TTL = 2592000;//最大时间
    /**
     * memcache服务器访问地址
     *
     * @var string $name
     */
    protected $host;
    /**
     * memcache服务器端口
     *
     * @var string $port
     */
    protected $port;
    /**
     * 数据库连接源
     *
     * @var \Memcache $link
     */
    protected $link;
    /**
     * SQL查询产生的结果集
     *
     * @var mixed $result
     */
    protected $result;
    protected $lifetime = 0;
    protected $show;
    protected function __construct() {
        global $CFG;
        $this->host  = $CFG['memcache_host'];
        $this->port  = $CFG['memcache_port'];
        $this->lifetime  = !isset($CFG['memcache_lifetime']) ? self::MAX_TTL : $CFG['memcache_lifetime'];
        $this->show  = empty($CFG['memcache_show']) ? false : true;
        $this->alter();
    }
    /**
     * connect memcache database.
     * new instance.
     */
	public function connect() {
        $this->link = new \Memcache();
        $this->link->connect($this->host, $this->port);
        return $this->link;
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
        global $CFG;
        if(!empty($name) &&!empty($CFG['memcache']) && !empty($CFG['memcache'][$name])) {
            $this->host = $CFG['memcache'][$name]['host'];
            $this->port = $CFG['memcache'][$name]['port'];
            $this->lifetime = !isset($CFG['memcache'][$name]['lifetime']) ? self::MAX_TTL : $CFG['memcache'][$name]['lifetime'];
            $this->show = empty($CFG['memcache'][$name]['show']) ? false : true;
        } else {
            $this->host = $CFG['memcache_host'];
            $this->port = $CFG['memcache_name'];
            $this->lifetime = !isset($CFG['memcache_lifetime']) ? self::MAX_TTL : $CFG['memcache_lifetime'];
            $this->show = empty($CFG['memcache_show']) ? false : true;
        }
        return $this->connect();
    }
	public function close() {
        if ($this->link) {
            $ret = $this->link->close();
            $this->link = null;
            return $ret;
        }
	}
    public final function query($cmd, $key, $data = '', $lifetime = 0) {
        $cmd = empty($cmd) ? false : strtoupper($cmd);
        if(!empty($cmd) && !empty($key)) {
            $link = !empty($this->link) ?: $this->connect();
            switch ($cmd) {
                case 'GET':
                    $this->result = $ret = $this->link->get($key);
                    if($cmd && $this->show)
                        Logger::info("$cmd $key $ret", 'memcache');
                    break;
                case 'SET':
                    $this->result = $this->link->set($key, $data, 0, $lifetime);
                    if($cmd && $this->show)
                        Logger::info("$cmd $key $data $lifetime", 'memcache');
                    break;
                case 'DELETE':
                    $this->result = $this->link->delete($key);
                    if($cmd && $this->show) {
                        Logger::info("$cmd $key", 'memcache');
                    }
                    break;
            }
            return $this->result;
        } else {
            return false;
        }
    }
    public final function get($id, $fields = array()) {
        if(!empty($id)) {
            $model = $this->model;
            $columns = $this->model->columns();
            $pk = $this->model->primary();
            $table = $this->model->table();
            $table = is_array($table) ? implode('.', $table) : $table;
            $key = "$table:$pk:$id";
            $ret = $this->query('get', $key);
            $ret = $this->decode($ret);
            return empty($ret) ? false : $ret;
        } else {
            return false;
        }
    }
    public final function add(array $info, $use_last_id = true) {
        return false;
    }
    public final function del($id) {
        if(!empty($id)) {
            $model = $this->model;
            $columns = $this->model->columns();
            $pk = $this->model->primary();
            $table = $this->model->table();
            $table = is_array($table) ? implode('.', $table) : $table;
            $key = "$table:$pk:$id";
            $ret = $this->query('delete', $key);
            return empty($ret) ? false : $ret;
        } else {
            return false;
        }
    }
    public final function upd($id, array $info) {
        if(!empty($id)) {
            $model = $this->model;
            $columns = $this->model->columns();
            $pk = $this->model->primary();
            $table = $this->model->table();
            $table = is_array($table) ? implode('.', $table) : $table;
            $key = "$table:$pk:$id";
            // life time
            if($this->model instanceof Volatile) {
                $this->model->build($info);
                $lifetime = $this->model->lifetime();
            } else {
                $lifetime = $this->lifetime;
            }
            $data = $this->encode($info);
            $ret = $this->query('set', $key, $data, $lifetime);
            return empty($ret) ? false : $ret;
        } else {
            return false;
        }
    }
    /**
     * @param array|string $unique
     */
    public final function updByUnique($unique, array $info) {
        $key = $this->makeUniqueKey($unique);
        if(!empty($key)) {
            // life time
            if($this->model instanceof Volatile) {
                $lifetime = $this->model->lifetime();
            } else {
                $lifetime = $this->lifetime;
            }
            // encode
            $data = $this->encode($info);
            // set
            $ret = $this->query('set', $key, $data, $lifetime);
            return empty($ret) ? false : $ret;
        } else {
            return false;
        }
    }
    /**
     * @param array|string $unique
     */
    public final function getByUnique($unique) {
        $key = $this->makeUniqueKey($unique);
        if(!empty($key)) {
            $ret = $this->query('get', $key);
            $ret = $this->decode($ret);
            return empty($ret) ? false : $ret;
        } else {
            return false;
        }
    }
    public final function delByUnique($unique) {
        $key = $this->makeUniqueKey($unique);
        if(!empty($key)) {
            return $this->query('delete', $key);
        } else {
            return false;
        }
    }
    public function makeUniqueKey($unique) {
        $model = $this->model;
        $table = $this->model->table();
        $table = is_array($table) ? implode('.', $table) : $table;
        $columns = $this->model->columns();
        $uk = $this->model->unique();
        if(!empty($uk) && is_array($uk) && is_array($unique)) {
            $arr = array();
            foreach ($uk as $k) {
                $p = array_search($k, $columns);
                if(!empty($unique[$k])) {
                    $arr[] = $unique[$k];
                } else if(!empty($unique[$p])) {
                    $arr[] = $unique[$p];
                } else {
                    return false;
                }
            }
            $uk = implode('.', $uk);
            $unique = implode('.', $arr);
            $key = "$table:$uk:$unique";
        } else if(!empty($uk) && is_string($uk) && is_string($unique)) {
            $key = "$table:$uk:$unique";
        } else {
            return false;
        }
        return $key;
    }
}
// PHP END