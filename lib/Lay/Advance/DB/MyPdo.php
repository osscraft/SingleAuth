<?php

namespace Lay\Advance\DB;

use Lay\Advance\DB\DataBase;
use Lay\Advance\DB\Querying;
use Lay\Advance\DB\Uniqueness;
use Lay\Advance\Util\Utility;
use Lay\Advance\Util\Logger;

use PDO;
use PDOStatement;

class MyPdo extends DataBase implements Querying, Uniqueness {
    /**
     * 服务器访问地址(DSN)
     *
     * @var string $name
     */
    protected $server;
    /**
     * 登录数据库用户名
     *
     * @var string $username
     */
    protected $username;
    /**
     * 登录数据库用户密码
     *
     * @var string $password
     */
    protected $password;
    /**
     * 选择的数据库名
     *
     * @var string $dbname
     */
    protected $dbname;
    /**
     * 数据库连接源
     *
     * @var mixed $link
     */
    protected $link;
    /**
     * SQL查询产生的结果集
     *
     * @var mixed $result
     */
    protected $result;
    /**
     * 数据库编码
     * @var string
     */
    protected $encode;
    /**
     * 记录SQL日志
     * @var boolean
     */
    protected $showsql;
    /**
     * 连接数据库
     * @return boolean
     */
    public abstract function connect() {

	}
    /**
     * 选择数据库名称
     *
     * @return mixed
     */
    public abstract function choose($dbname) {

	}
    /**
     * 选择数据库
     * @return boolean
     */
    public abstract function alter($name = '') {

	}
    /**
     * close connection
     * @return boolean
     */
    public abstract function close() {

	}
	public function query($sql, $encoding = 'UTF8', array $option = array()) {

	}
	public function select($fields = array(), $condition = array(), $order = array(), $limit = array(), $safe = true) {
		
	}
	public function insert($fields = array(), $values = array(), $replace = false) {
		
	}
	public function update($fields = array(), $values = array(), $condition = array(), $safe = true) {
		
	}
	public function delete($condition = array(), $safe = true) {
		
	}
	public function increase($field, $num = 1, $condition = array(), $safe = true) {
		
	}
    public function getByUnique($unique) {

    }
    public function updByUnique($unique, array $info) {
    	
    }
    public function delByUnique($unique) {
    	
    }
}
