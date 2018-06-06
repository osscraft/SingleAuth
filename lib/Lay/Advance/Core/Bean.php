<?php

namespace Lay\Advance\Core;

use Lay\Advance\Core\Component;
use Lay\Advance\Util\Logger;
use Lay\Advance\Util\Utility;
use Lay\Advance\Core\Beanizable;
use ReflectionObject;
use ReflectionProperty;
use Iterator;
use ArrayAccess;
use stdClass;
use Exception;

abstract class Bean extends Component
{
    final public function __construct()
    {
        //初始化值
        foreach ($this->properties() as $name => $value) {
            $this->$name = $value;
        }
    }
    /**
     * @see Component::rules()
     */
    public function rules()
    {
        return array();
    }
    /**
     * @see Component::format()
     */
    public function format($val, $key, $options = array())
    {
        return $val;
    }
    /**
     * 兼容,
     */
    public function build($args = array())
    {
        $args = empty($args) ? $_REQUEST : $args;
        foreach ($this->properties() as $p => $v) {
            if (isset($args[$p])) {
                $this->$p = $args[$p];
            }
        }
    }
}
// PHP END
