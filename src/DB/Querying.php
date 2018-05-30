<?php
namespace Dcux\DB;

interface Querying {
	public function query($sql, $encoding = 'UTF8', array $option = array());
	public function select($fields = array(), $condition = array(), $order = array(), $limit = array(), $safe = true);
	public function insert($fields = array(), $values = array(), $replace = false);
	public function update($fields = array(), $values = array(), $condition = array(), $safe = true);
	public function delete($condition = array(), $safe = true);
	public function increase($field, $num = 1, $condition = array(), $safe = true);
}
// PHP END