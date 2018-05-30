<?php

namespace Dcux\Util;

use Dcux\Core\Singleton;
use Dcux\Core\App;

class Logger extends Singleton {
    // use Singleton;
    /**
     * 定义不打印输出或不记录日志的级别
     *
     * @var int
     */
    const L_NONE = 0;
    /**
     * 定义打印输出或记录日志调试信息的级别
     *
     * @var int
     */
    const L_DEBUG = 1; // 1
    /**
     * 定义打印输出或记录日志信息的级别
     *
     * @var int
     */
    const L_INFO = 2; // 2
    /**
     * 定义打印输出或记录日志警告信息的级别
     *
     * @var int
     */
    const L_WARN = 4; // 4
    /**
     * 定义打印输出或记录日志错误信息的级别
     *
     * @var int
     */
    const L_ERROR = 8; // 8
    /**
     * 记录日志级别
     *
     * @var int
     */
    const L_LOG = 16;
    /**
     * 定义打印输出或记录日志所有级别信息的级别
     *
     * @var int
     */
    const L_ALL = 127; // 127
    protected $dir = '';
    protected $level = true;
    /**
     * 构造方法
     */
    protected function __construct() {
    }
    /**
     * set log dir
     * 
     * @param string $dir            
     */
    public function directory($dir) {
        if ($path = realpath($dir)) {
            $this->dir = $path;
        }
    }
    /**
     * set log dir
     * 
     * @param string $dir            
     */
    public function level($level) {
        if (is_bool($level)) {
            $this->level = $level;
        } else if (is_array($level)) {
            $level = isset($level['level']) ? $level['level'] : isset($level[0]) ? $level[0] : true;
            $this->initialize($level);
        } else if (is_int($level)) {
            $this->level = $level;
        } else {
            $this->level = true;
        }
    }
    /**
     * log级别，包括打印输出和syslog日志
     *
     * @param mixed $debug
     *            级别，如：true; false; array(true); array(Logger::L_NONE)
     * @return void
     */
    public function initialize() {
        $this->directory(App::$_docpath); // 初始化日志文档目录
        $this->level(App::get('logger', true));
    }
    /**
     * 当前级别数值与给出的级别数值是否匹配
     *
     * @param int $set
     *            当前级别数值
     * @param int $lv
     *            给出的级别数值
     * @return boolean
     */
    private function regular($set, $lv = 1) {
        $ret = $lv & $set;
        return $ret === $lv ? true : false;
    }
    /**
     *
     * @param mixed $var            
     * @param string $name            
     * @param int $level            
     * @param string $line_delimiter            
     */
    public function write($var, $name = 'sso', $level = 0, $line_delimiter = "\n") {
        if ($this->level === true || ($this->level && $this->regular(intval($this->level), $level))) {
            $lv = strtolower($this->parseLevel($level));
            $file = $this->dir . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . "$name.$lv.log";
            $var = is_string($var) ? $var : var_export($var, true);
            $exists = file_exists($file);
            error_log('[' . date('Y-m-d H:i:s') . '] ' . rtrim($var, $line_delimiter) . $line_delimiter, 3, $file);
            empty($exists) && @chmod($file, 0777);
        }
    }
    
    /**
     * 记录调试信息
     *
     * @param string $msg
     *            字符信息字符串
     * @param string $tag
     *            标签名
     * @param string $enforce
     *            是否强制打印输出，默认非
     * @return void
     */
    public static function debug($msg, $tag = 'sso') {
        self::getInstance()->write($msg, $tag, self::L_DEBUG);
    }
    /**
     * 记录信息
     *
     * @param string $msg
     *            字符信息字符串
     * @param string $tag
     *            标签名
     * @return void
     */
    public static function info($msg, $tag = 'sso') {
        self::getInstance()->write($msg, $tag, self::L_INFO);
    }
    /**
     * 记录警告信息
     *
     * @param string $msg
     *            字符信息字符串
     * @param string $tag
     *            标签名
     * @return void
     */
    public static function warning($msg, $tag = 'sso') {
        self::warn($msg, $tag);
    }
    /**
     * 记录警告信息
     *
     * @param string $msg
     *            字符信息字符串
     * @param string $tag
     *            标签名
     * @return void
     */
    public static function warn($msg, $tag = 'sso') {
        self::getInstance()->write($msg, $tag, self::L_WARN);
    }
    /**
     * 记录错误信息
     *
     * @param string $msg
     *            字符信息字符串
     * @param string $tag
     *            标签名
     * @return void
     * @throws Exception
     */
    public static function error($msg, $tag = 'sso') {
        self::getInstance()->write($msg, $tag, self::L_ERROR);
    }
    /**
     * 记录日志信息
     *
     * @param string $msg
     *            字符信息字符串
     * @param string $tag
     *            标签名
     * @return void
     */
    public static function log($msg, $tag = 'sso') {
        self::getInstance()->write($msg, $tag, self::L_LOG);
    }
    
    /**
     * 缩短显示字符
     *
     * @param string $string
     *            字符串
     * @param number $front
     *            之前保留长度
     * @param number $follow
     *            之前保留长度
     * @param string $dot
     *            省略部分替代字符串
     * @return string
     */
    protected function cutString($string, $front = 10, $follow = 0, $dot = '...') {
        $strlen = strlen($string);
        if ($strlen < $front + $follow) {
            return $string;
        } else {
            $front = abs(intval($front));
            $follow = abs(intval($follow));
            $pattern = '/^(.{' . $front . '})(.*)(.{' . $follow . '})$/';
            $bool = preg_match($pattern, $string, $matches);
            if ($bool) {
                $front = $matches[1];
                $follow = $matches[3];
                return $front . $dot . $follow;
            } else {
                return $string;
            }
        }
    }
    /**
     * 通过级别得到不同CSS样式
     *
     * @param int|string $lv
     *            级别
     * @return string
     */
    protected function parseColor($lv) {
        switch ($lv) {
            case Logger::L_DEBUG :
            case 'DEBUG' :
                $lv = 'color:#0066FF';
                break;
            case Logger::L_INFO :
            case 'INFO' :
                $lv = 'color:#006600';
                break;
            case Logger::L_WARN :
            case 'WARN' :
                $lv = 'color:#FF9900';
                break;
            case Logger::L_ERROR :
            case 'ERROR' :
                $lv = 'color:#FF0000';
                break;
            case Logger::L_LOG :
            case 'LOG' :
                $lv = 'color:#CCCCCC';
                break;
        }
        return $lv;
    }
    /**
     * 级别与特定标签之间转换
     *
     * @param int|string $lv
     *            级别
     * @return mixed
     */
    protected function parseLevel($lv) {
        switch ($lv) {
            case Logger::L_DEBUG :
                $lv = 'DEBUG';
                break;
            case Logger::L_INFO :
                $lv = 'INFO';
                break;
            case Logger::L_WARN :
                $lv = 'WARN';
                break;
            case Logger::L_ERROR :
                $lv = 'ERROR';
                break;
            case Logger::L_LOG :
                $lv = 'LOG';
                break;
            case 'DEBUG' :
                $lv = Logger::L_DEBUG;
                break;
            case 'INFO' :
                $lv = Logger::L_INFO;
                break;
            case 'WARN' :
                $lv = Logger::L_WARN;
                break;
            case 'ERROR' :
                $lv = Logger::L_ERROR;
                break;
            case 'LOG' :
                $lv = Logger::L_LOG;
                break;
        }
        return $lv;
    }
}
// PHP END