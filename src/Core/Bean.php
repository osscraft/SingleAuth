<?php

namespace Dcux\Core;

use Dcux\Core\Component;
use Dcux\Util\Logger;
use Dcux\Util\Utility;
use Dcux\Core\Beanizable;
use ReflectionObject;
use ReflectionProperty;
use Iterator;
use ArrayAccess;
use stdClass;
use Exception;

abstract class Bean extends Component {
    public final function __construct() {
        //初始化值
        foreach ($this->properties() as $name => $value) {
            $this->$name = $value;
        }
    }
    /**
     * @see Component::rules()
     */
    public function rules() {
        return array();
    }
    /**
     * @see Component::format()
     */
    public function format($val, $key, $options = array()) {
        return $val;
    }
    /**
     * 兼容,
     */
    public function build($args = 0) {
        $args = empty($args) ? $_REQUEST : $args;
        foreach ($this->properties() as $p => $v) {
            if(isset($args[$p])) {
                $this->$p = $args[$p];
            }
        }
    }
    
}
// PHP END