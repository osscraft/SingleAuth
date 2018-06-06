<?php

/**
 * 配置数据访问类
 *
 * @author Dcux Li
 */
namespace Lay\Advance\Core;

use Lay\Advance\Core\App;
use Lay\Advance\Core\Singleton;
use Lay\Advance\Util\Utility;
use Lay\Advance\Core\Service;

//use Dcux\SSO\Service\SettingService;

/**
 * 配置数据访问类
 *
 * @author Dcux Li
 */
class Configuration extends Singleton
{
    // use Singleton;
    private static $_config = array();
    private static $_cachefile = 'dcux.config.php';
    private static $_cachedir = __DIR__;
    private static $_caches = array();
    private static $_dirty = false;

    private static $_service = null;
    
    /**
     * 初始化配置项
     *
     * @return void
     */
    public static function initialize($conf_service = '')
    {
        // 动态配置项
        if (!empty($conf_service) && is_subclass_of($conf_service, 'Lay\Advance\Core\Service')) {
            if (is_string($conf_service)) {
                self::$_service = $conf_service::getInstance();
            } else {
                self::$_service = $conf_service;
            }
        }
        // 设置缓存文件目录
        self::setCacheDir(sys_get_temp_dir());
        // 注册shutdown事件
        // register_shutdown_function(array('Dcux\Core\Configuration', 'updateCache'));
        // 没有缓存配置，加载配置
        //empty($config) && self::load();
        self::load();
        // 加载配置缓存
        self::loadCache();
        /*if(is_array($config) && !empty ($config)){
            $CFG = $config;
        }*/
    }
    /**
     * 加载并设置配置
     *
     * @param string|array $configuration
     *            配置文件或配置数组
     * @param boolean $isFile
     *            标记是否是配置文件
     * @return void
     */
    public static function configure($configuration, $isFile = true, $setCache = false)
    {
        $_ROOTPATH = &App::$_rootpath;
        if (is_array($configuration) && ! $isFile) {
            foreach ($configuration as $key => $item) {
                if (is_string($key) && $key) { // key is not null
                    self::set($key, $item);
                    $setCache && self::setCache($key, $item);
                }
            }
        } elseif (is_array($configuration)) {
            if (! empty($configuration)) {
                foreach ($configuration as $index => $configfile) {
                    self::configure($configfile);
                }
            }
        } elseif (is_string($configuration)) {
            // Logger::info('configure file:' . $configuration, 'CONFIGURE');
            if (is_file($configuration)) {
                $tmparr = include_once $configuration;
            } elseif (is_file($_ROOTPATH . $configuration)) {
                $tmparr = include_once $_ROOTPATH . $configuration;
            } else {
                // Logger::warn($configuration . ' is not a real file', 'CONFIGURE');
                $tmparr = array();
            }
            
            if (empty($tmparr)) {
                self::configure($tmparr);
            } else {
                self::configure($tmparr, false);
            }
        } else {
            // Logger::warn('unkown configuration type', 'CONFIGURE');
        }
    }
    /**
     * 加载配置信息
     *
     * @return void
     */
    public static function load()
    {
        global $CFG;
        $path = App::$_rootpath;
        $envfile = $path . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'env.php';
        self::configure($envfile);
        // 运行环境
        $env = self::get('env', 'test');
        $languages = self::get('languages', array('zh_cn', 'en_us'));
        // 语言环境，可自定义
        $CFG['language'] = empty($_REQUEST['_lang']) || !in_array($_REQUEST['_lang'], $languages) ? 'zh_cn' : $_REQUEST['_lang'];
        $configfile = $path . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'main.' . $env . '.php';
        self::configure($configfile);
    }
    /**
     * 加载配置信息缓存
     *
     * @return void
     */
    public static function loadCache()
    {
        global $CFG;// 兼容
        $rootpath = App::$_rootpath;
        $cachename = realpath(self::$_cachedir . DIRECTORY_SEPARATOR . self::$_cachefile);
        if (is_file($cachename)) {
            self::$_caches = include $cachename;
        } elseif (!empty(self::$_service)) {
            $settings = self::$_service->getAll();
            self::$_caches=array();
            foreach ($settings as $key => $val) {
                self::setCache($val['k'], $val['v']);
            }
            self::updateCache();
        }
        if (is_array(self::$_caches) && ! empty(self::$_caches)) {
            // 使用传递的数组递归替换第一个数组的元素
            // @see http://php.net/manual/zh/function.array-replace-recursive.php
            $CFG = self::$_config = array_replace_recursive(self::$_config, self::$_caches);
        }
        return self::$_config;
    }
    /**
     * 清除配置信息缓存文件
     *
     * @return void
     */
    public static function cleanCache()
    {
        self::$_caches = array();
        $cachename = realpath(self::$_cachedir . DIRECTORY_SEPARATOR . self::$_cachefile);
        if (is_file($cachename)) {
            @unlink($cachename);
        }
    }
    /**
     * 更新配置信息缓存
     *
     * @return boolean
     */
    public static function updateCache($force = false)
    {
        if (! empty(self::$_dirty) || $force) {
            // 先读取，再merge，再存储
            $cachename = self::$_cachedir . DIRECTORY_SEPARATOR . self::$_cachefile;
            // 写入
            $content = Utility::array2PHPContent(self::$_caches);
            $handle = fopen($cachename, 'w');
            $return = @chmod($cachename, 0777);
            $result = fwrite($handle, $content);
            $return = fflush($handle);
            $return = fclose($handle);
            self::$_dirty = false;
            return $result;
        } else {
            return false;
        }
    }
    /**
     * 设置配置信息缓存文件所在目录
     *
     * @return boolean
     */
    public static function setCacheDir($dirpath)
    {
        if ($dir = realpath($dirpath)) {
            self::$_cachedir = $dir;
        }
    }
    /**
     * 获取配置信息缓存文件所在目录
     *
     * @return string
     */
    public static function getCacheDir()
    {
        return self::$_cachedir;
    }
    /**
     * 设置新的配置项缓存
     *
     * @param string $classname
     *            类名
     * @param string $filepath
     *            类文件路径
     * @return void
     */
    public static function setCache($keystr, $value)
    {
        self::$_dirty = true;
        //self::$_caches[$key] = $value;
        if (! self::checkKey($keystr)) {
            // Logger::warn('given key isnot supported;string,int is ok.', 'CONFIGURATION');
        } else {
            if (! self::checkValue($value)) {
                // Logger::warn('given value isnot supported;string,number,boolean is ok.', 'CONFIGURATION');
            } else {
                if (! self::checkKeyValue($keystr, $value)) {
                    // Logger::warn('given key and value isnot match;if key is array,value must be array.', 'CONFIGURATION');
                } else {
                    $node = &self::$_caches;
                    if (is_array($keystr) && $keystr) {
                        foreach ($keystr as $i => $key) {
                            self::setCache($key, isset($value[$i]) ? $value[$i] : false);
                        }
                    } elseif (is_string($keystr) && $keystr) {
                        $keys = explode('.', $keystr);
                        $count = count($keys);
                        foreach ($keys as $index => $key) {
                            if (isset($node[$key]) && $index === $count - 1) {
                                // warning has been configured by this name
                                // Logger::warn('$configuration["' . implode('"]["', $keys) . '"] has been configured.', 'CONFIGURATION');
                                $node[$key] = $value;
                            } elseif (isset($node[$key])) {
                                $node = &$node[$key];
                            } elseif ($index === $count - 1) {
                                $node[$key] = $value;
                            } else {
                                $node[$key] = array();
                                $node = &$node[$key];
                            }
                        }
                    }
                }
            }
        }
    }
    /**
     * 获取某个配置项的缓存或所有
     *
     * @param string $classname
     *            类名
     * @return mixed
     */
    public static function getCache($key = '')
    {
        if (is_string($key) && $key && isset(self::$_caches[$key]) && self::checkKey($keystr)) {
            //return self::$_caches[$key];
            if (is_array($keystr) && $keystr) {
                $node = array();
                foreach ($keystr as $i => $key) {
                    $node[$i] = self::getCache($key);
                }
            } elseif (is_string($keystr) && $keystr) {
                $node = &self::$_caches;
                $keys = explode('.', $keystr);
                foreach ($keys as $key) {
                    if (isset($node[$key])) {
                        $node = &$node[$key];
                    } else {
                        return $default;
                    }
                }
            } else {
                $node = &self::$_caches;
            }
            return $node;
        } else {
            return self::$_caches;
        }
    }
    
    /**
     * 获取节点的值
     *
     * @param string $keystr
     *            要获取的节点键名
     * @return mixed
     */
    public static function get($keystr = '', $default = null)
    {
        if (self::checkKey($keystr)) {
            if (is_array($keystr) && $keystr) {
                $node = array();
                foreach ($keystr as $i => $key) {
                    $node[$i] = self::get($key);
                }
            } elseif (is_string($keystr) && $keystr) {
                $node = &self::$_config;
                $keys = explode('.', $keystr);
                foreach ($keys as $key) {
                    if (isset($node[$key])) {
                        $node = &$node[$key];
                    } else {
                        return $default;
                    }
                }
            } else {
                $node = &self::$_config;
            }
            return $node;
        } else {
            return $default;
        }
    }
    /**
     * 设置节点的值
     *
     * @param array|string|int $keystr
     *            要设置的节点键名
     * @param array|string|number|boolean $value
     *            要设置的节点值
     * @return void
     */
    public static function set($keystr, $value)
    {
        if (! self::checkKey($keystr)) {
            // Logger::warn('given key isnot supported;string,int is ok.', 'CONFIGURATION');
        } else {
            if (! self::checkValue($value)) {
                // Logger::warn('given value isnot supported;string,number,boolean is ok.', 'CONFIGURATION');
            } else {
                if (! self::checkKeyValue($keystr, $value)) {
                    // Logger::warn('given key and value isnot match;if key is array,value must be array.', 'CONFIGURATION');
                } else {
                    $node = &self::$_config;
                    if (is_array($keystr) && $keystr) {
                        foreach ($keystr as $i => $key) {
                            self::set($key, isset($value[$i]) ? $value[$i] : false);
                        }
                    } elseif (is_string($keystr) && $keystr) {
                        $keys = explode('.', $keystr);
                        $count = count($keys);
                        foreach ($keys as $index => $key) {
                            if (isset($node[$key]) && $index === $count - 1) {
                                // warning has been configured by this name
                                // Logger::warn('$configuration["' . implode('"]["', $keys) . '"] has been configured.', 'CONFIGURATION');
                                $node[$key] = $value;
                            } elseif (isset($node[$key])) {
                                $node = &$node[$key];
                            } elseif ($index === $count - 1) {
                                $node[$key] = $value;
                            } else {
                                $node[$key] = array();
                                $node = &$node[$key];
                            }
                        }
                    }
                }
            }
        }
    }
    /**
     * 检测是否符合规定的格式，支持array,string,int,且数组中也必须符合此格式
     *
     * @param array|string|int $key
     *            节点键名
     * @return boolean
     */
    private static function checkKey($key)
    {
        if (is_array($key)) {
            foreach ($key as $i => $k) {
                if (! self::checkKey($k)) {
                    return false;
                }
            }
            return true;
        } elseif (is_string($key) || is_int($key)) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * 检测是否符合规定的格式，支持array,string,number,boolean,且数组中也必须符合此格式
     *
     * @param array|string|number|boolean $value
     *            节点值
     * @return boolean
     */
    private static function checkValue($value)
    {
        if (is_array($value)) {
            foreach ($value as $i => $var) {
                if (! self::checkValue($var)) {
                    return false;
                }
            }
            return true;
        } elseif (is_bool($value) || is_string($value) || is_numeric($value)) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * 检测节点键名与节点值是否是对应类型
     *
     * @param array $key
     *            节点键名
     * @param array $value
     *            节点值
     * @return boolean
     */
    private static function checkKeyValue($key, $value)
    {
        if (is_array($key)) {
            if (is_array($value)) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }
}
// PHP END
