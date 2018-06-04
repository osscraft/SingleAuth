<?php

namespace Lay\Advance\Core;

use ArrayAccess;
use Iterator;
//use JsonSerializable;

interface Beanizable { /*extends JsonSerializable*/
    /**
     * 返回对象属性名对属性值的数组
     * @return array
     */
	public function toArray();
    /**
     * 返回对象转换为stdClass后的对象
     * @return stdClass
     */
    public function toStandard();
}
// PHP END