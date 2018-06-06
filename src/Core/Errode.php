<?php
namespace Dcux\Core;

use Dcux\Core\App;
use Dcux\Core\Component;
use Dcux\Util\Logger;
use Dcux\Core\Error;
use Exception;

final class Errode extends Component
{
    //protected static $last;
    protected static $stack = array();
    public static function __callStatic($name, $arguments)
    {
        global $CFG;

        if (isset($CFG['error_flip'][$name])) {
            $code = $CFG['error_flip'][$name];
            $msg = $CFG['error'][$code];
        } else {
            $code = 999999;
            $msg = $CFG['error'][999999];
        }
        // 组合
        array_unshift($arguments, $msg);

        return new Errode($code, call_user_func_array('sprintf', $arguments));
    }
    public static function __lastErrode()
    {
        reset(Errode::$stack);
        return current(Errode::$stack);
    }
    public static function __error($e)
    {
        return Errode::unkown_error()->error($e);
    }

    protected $code;
    protected $message;
    protected function __construct($code, $message)
    {
        $this->code = $code;
        $this->message = $message;

        array_unshift(Errode::$stack, $this);
    }
    public function error($e = null)
    {
        if ($e instanceof Error) {
            // new Errode;
            new Errode($e->getCode(), $e->getMessage());
            // return
            return $e;
        } elseif ($e instanceof Exception) {
            $code = $e->getCode();
            $message = $e->getMessage();
            $this->__set('code', $code);
            $this->__set('message', $message);
            return new Error($message, $code, $e->getPrevious());
        } else {
            return new Error($this->message, $this->code);
        }
    }
    /**
     * 返回对象所有属性名的数组
     * @return array
     */
    public function properties()
    {
        return array(
            'code' => 0,
            'message' => ''
        );
    }
    /**
     * 返回对象所有属性值规则
     * @return array
     */
    public function rules()
    {
        return array(
            'code' => Component::TYPE_NUMBER,
            'message' => Component::TYPE_STRING
        );
    }
    /**
     * 返回规则转换后的值
     * @return array
     */
    public function format($val, $key, $option = array())
    {
        return $val;
    }
    /**
     * 返回对象属性名对属性值的数组
     * @return array
     */
    public function toArray()
    {
        $ret = array();
        foreach ($this->properties() as $name => $def) {
            $ret[$name] = $this->$name;
        }
        return $ret;
    }
    /**
     * 返回对象转换为stdClass后的对象
     * @return stdClass
     */
    public function toStandard()
    {
        $ret = new stdClass();
        foreach ($this->properties() as $name => $value) {
            $ret->$name = $this[$name];
        }
        return $ret;
    }
    /**
     * PHP 5.4
     * json serialize function
     *
     * @return stdClass
     */
    public function jsonSerialize()
    {
        return $this->toStandard();
    }
}
// PHP END
