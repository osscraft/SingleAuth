<?php

namespace Dcux\DB;

use Dcux\Core\Model;
use Dcux\DB\CRUDable;
use Dcux\DB\Mysql;
use Dcux\DB\Mongo;
use Dcux\DB\Redis;
use Dcux\DB\Memcache;
use Dcux\DB\ConfigCacher;
use Dcux\DB\Ldap;
use Dcux\Core\Singleton;

abstract class DataBase extends Singleton implements CRUDable
{
    public static function factory($name = 'mysql')
    {
        switch ($name) {
            case 'memcache':
            case 'memcached':
                return Memcache::getInstance();
                break;
            case 'redis':
                return Redis::getInstance();
                break;
            case 'mongodb':
            case 'mongo':
                return Mongo::getInstance();
                break;
            case 'ldap':
            case 'openldap':
                return Ldap::getInstance();
                break;
            case 'configcacher':
                return ConfigCacher::getInstance();
                break;
            case 'mysql':
            default:
                return Mysql::getInstance();
                break;
        }
    }
    /**
     * 模型对象
     * @var Model $model 模型对象
     */
    protected $model;
    /**
     * 数据库连接
     * @var mixed $link
     */
    protected $link;
    /**
     * 设置模型对象
     * @param Model $model 模型对象
     * @return void
     */
    final public function setModel(Model $model)
    {
        $this->model = $model;
    }
    /**
     * 获取模型对象
     * @return Model
     */
    final public function getModel()
    {
        return $this->model;
    }
    final public function getLink()
    {
        return $this->link;
    }
    /**
     * 连接数据库
     * @return boolean
     */
    abstract public function connect();
    /**
     * 选择数据库名称
     *
     * @return mixed
     */
    abstract public function choose($dbname);
    /**
     * 选择数据库
     * @return boolean
     */
    abstract public function alter($name = '');
    /**
     * close connection
     * @return boolean
     */
    abstract public function close();
}
// PHP END
