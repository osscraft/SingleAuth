<?php

namespace Lay\Advance\DB;

use Lay\Advance\DB\Cacheable;

abstract class Cacher extends DataBase implements Cacheable {
	public function encode($mix) {
		return json_encode($mix);
	}
	public function decode($str) {
		return json_decode($str, true);
	}
    public final function count(array $info = array()) {
        return false;
    }
	public final function replace(array $info = array()) {
		return false;
	}
}
// PHP END