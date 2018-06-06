<?php

namespace Lay\Advance\Core;

// 核心类
use Lay\Advance\Core\Singleton;
use Lay\Advance\Core\EventEmitter;
use Lay\Advance\Core\Action;
use Lay\Advance\Core\Trustee;
use Lay\Advance\Util\Utility;
use Lay\Advance\Util\Logger;
use Lay\Advance\Http\Request;
use Lay\Advance\Core\Errode;
use Lay\Advance\Core\Error;
// App类
use Exception;
use ErrorException;

abstract class App extends Singleton
{
    // use Singleton;
    const E_BEFORE = 'app:event:before';
    const E_RUN = 'app:event:run';
    const E_AFTER = 'app:event:after';
    const E_FINISH = 'app:event:finish';
    const E_ERROR = 'app:event:error';
    public static $_rootpath;
    public static $_docpath;
    public static $_trustee;
    public static $_action;
    public static $_config;
    public static $_logger;
    public static $_event;
    public static $_app;
    /**
     * 运行，创建App并启动App的生命同期
     *
     * @return void
     */
    public static function start($rootpath = '', $docpath = '', $conf_service = '')
    {
        // ob start
        ob_start();
        error_reporting(E_ALL ^ E_NOTICE);
        ini_set('output_buffering', 'on');
        ini_set('implicit_flush', 'off');
        // app instance
        self::$_app = self::getInstance();
        // set error handler
        if (!Utility::isCli()) {
            set_error_handler(array(self::$_app, 'error_handler'));
        }
        // initialize root path
        self::$_rootpath = empty($rootpath) ? dirname(dirname(dirname(__DIR__))) : $rootpath;
        // initialize document path, depend on root path
        self::$_docpath = empty($docpath) ? (empty($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['OLDPWD'] : $_SERVER['DOCUMENT_ROOT']) : $docpath;
        // initialize Logger, depend on document path
        self::$_logger = Logger::getInstance();
        self::$_logger->initialize();
        // initialize EventEmitter, depend on Logger
        self::$_event = EventEmitter::getInstance();
        self::$_event->initialize();
        // initialize Configuration, depend on Logger, EventEmitter
        self::$_config = Configuration::getInstance();
        self::$_config->initialize(empty($conf_service) ? '' : $conf_service);
        // initialize App
        self::$_app->initialize();
        self::$_app->detect(self::$_docpath);

        self::$_app->lifecycle();
        /*try {
        } catch ( Exception $err ) {
            self::$_app->error($err);
            self::$_event->fire(get_class(self::$_app), self::E_ERROR, array(
                    self::$_app
            ));
        }*/
    }
    /**
     *
     * @see https://github.com/chriso/klein.php
     */
    protected $klein;
    protected $apiname = '';
    protected $extension = '';
    protected $classname = '';
    /**
     * 异常托管Action类
     */
    protected $trustee = '';
    protected $routers = array();
    public function getApiname()
    {
        return $this->apiname;
    }
    public function getExtension()
    {
        return $this->extension;
    }
    public function getClassname()
    {
        return $this->classname;
    }
    public function getRouters()
    {
        return $this->routers;
    }
    /**
     * App初始化,根据实际需求将成员变量classname赋值
     *
     * @return void
     */
    abstract public function initialize();
    /**
     * App生命同期
     *
     * @return void
     */
    public function lifecycle($fire = true)
    {
        $class = get_class($this);
        try {
            // before
            $this->before();
            self::$_event->fire($class, self::E_BEFORE, array($this));
            // run
            $this->run();
            self::$_event->fire($class, self::E_RUN, array($this));
            // after
            $this->after();
            self::$_event->fire($class, self::E_AFTER, array($this));
            // finish
            $this->finish();
            self::$_event->fire($class, self::E_FINISH, array($this));
        } catch (Exception $err) {
            // error
            $this->error($err);
            self::$_event->fire($class, self::E_ERROR, array($this));
        }
    }
    protected function before()
    {
        try {
            if (empty($this->classname) || !class_exists($this->classname)) {
                $this->classname = $this->trustee;
            }
        } catch (\Exception $err) {
            throw new Error(Errode::file_not_found(), 0, $err);
        }
    }
    // override detect classname
    protected function detect($webpath, $prefix = '\\Dcux\\SSO\\Action\\')
    {
        $webpath = realpath($webpath) . DIRECTORY_SEPARATOR;
        $script = realpath($_SERVER['SCRIPT_FILENAME']);
        if (!isset($_SERVER['PATH_INFO']) || Utility::isCli()) {
            $name = basename(str_replace(DIRECTORY_SEPARATOR, '.', substr($script, strlen($webpath))), '.php');
            $this->apiname = $apiname = "/".trim(str_replace('\\', '/', $name), '/');
            $this->extension = 'php';
        } else {
            $pathinfo = pathinfo(empty($_SERVER['PATH_INFO']) ? '/' : $_SERVER['PATH_INFO']);
            extract($pathinfo);
            if (empty($extension)) {
                $this->apiname = $apiname = $_SERVER['PATH_INFO'];
            } else {
                $_dirname = str_replace('\\', '/', $dirname);
                $_dirname = trim($_dirname, '/');
                $this->apiname = $apiname = empty($_dirname) ? "/$filename" : "/$_dirname/$filename";
                $this->extension = $extension;
            }
        }
        $subject = preg_replace(array('/\//', '/\./', '/\-/'), array('\\', '\\', '_'), $apiname);
        $all = preg_match_all('/([\\\\|\/|\-|_])(\S)/', $subject, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $str = $match[0];
            $subject = str_replace($str, strtoupper($str), $subject);// 首字母大写
        }
        $this->classname = $classname = $prefix . trim($subject, '\\ ');
        return $classname;
    }
    // main run
    protected function run()
    {
        $classname = $this->classname;
        $routers = $this->routers;
        if (empty($routers)) {
            if (class_exists($classname) && self::$_action = $classname::getInstance()) {
                self::$_action->initialize();
                self::$_action->lifecycle();
            } elseif (! empty($classname)) {
                throw new Error(Errode::class_not_found($classname));
            } else {
                throw new Error(Errode::invalid_action_class());
            }
        } else {
            // use Klein(https://github.com/chriso/klein.php)
            $this->klein = $klein = new Klein();
            
            foreach ($this->routers as $k => $config) {
                $klein->respond($k, function ($req, $res) use ($klein, $config) {
                    $classname = $config['classname'];
                    if (class_exists($classname) && self::$_action = $classname::getInstance()) {
                        self::$_action->initialize();
                        self::$_action->lifecycle();
                    } elseif (! empty($classname)) {
                        throw new Error(Errode::class_not_found($classname));
                    } else {
                        throw new Error(Errode::invalid_action_class());
                    }
                });
            }
            $klein->dispatch();
        }
    }
    protected function after()
    {
    }
    protected function finish()
    {
        if (function_exists('fastcgi_finish_request')) {
            // ngnix fastcgi
            fastcgi_finish_request();
        }
    }
    /**
     * PHP捕获异常句柄
     */
    public function error_handler($errno, $errstr, $errfile, $errline)
    {
        // handle error
        //$err =
        $this->error(new \Exception($errstr, $errno));
    }
    /**
     * 运行异常
     */
    protected function error($err)
    {
        self::$_logger->error($this->errorIterator($err));
        try {
            if (empty($this->trustee)) {
                self::$_trustee = !empty(self::$_trustee) ? self::$_trustee : Trustee::getInstance();
            } elseif (is_subclass_of($this->trustee, 'Lay\Advance\Core\Action')) {
                $trustee = $this->trustee;
                self::$_trustee = $trustee::getInstance();
            }
            self::$_trustee->initialize();
            self::$_trustee->lifecycle();
        } catch (\Exception $e) {
            throw $err;
        }
    }
    protected function errorIterator($err)
    {
        $log = '';
        if ($err instanceof Exception) {
            $pre = $err->getPrevious();
            $log .= $err->getMessage() . '(' . $err->getCode() . ")\n";
            $log .= $err->getFile() . '(' . $err->getLine() . ")\n";
            $log .= $err->getTraceAsString() . "\n";
            if (!empty($pre)) {
                $log .= "Previous: " . $this->errorIterator($pre);
            }
        }
        return $log;
    }
    
    /**
     * 设置某个配置项
     *
     * @param string|array $keystr
     *            键名
     * @param string|boolean|int|array $value
     *            键值
     * @return void
     */
    public static function set($keystr, $value)
    {
        Configuration::set($keystr, $value);
    }
    /**
     * 获取某个配置项
     *
     * @param string $keystr
     *            键名，子键名配置项使用.号分割
     * @param mixed $default
     *            不存在时的默认值，默认null
     * @return mixed
     */
    public static function get($keystr = '', $default = null)
    {
        return Configuration::get($keystr, $default);
    }
}

// PHP END
