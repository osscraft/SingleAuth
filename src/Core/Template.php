<?php

namespace Dcux\Core;

use Dcux\Http\Request;
use Dcux\Http\Response;
use Dcux\Core\AbstractSingleton;
use Dcux\Util\Utility;
use Dcux\Util\Logger;
use Dcux\Core\App;
use Dcux\Core\Singleton;
use Dcux\Core\Templatelizable;
use Exception;
use Iterator;

class Template extends Singleton implements Templatelizable
{
    // use Singleton;//php 5.4
    /**
     * HttpRequest对象
     *
     * @var HttpRequest $request
     */
    protected $request;
    /**
     * HttpReponse对象
     *
     * @var HttpReponse $response
     */
    protected $response;
    /**
     * the language
     *
     * @var string $lan
     */
    protected $lan = '';
    /**
     * 输出变量内容数组
     *
     * @var array $vars
     */
    protected $vars = array();
    /**
     * resources
     *
     * @var array $resources
     */
    protected $resources = array();
    /**
     * HTTP headers
     *
     * @var array $headers
     */
    protected $headers = array();
    /**
     * HTML metas
     *
     * @var array $metas
     */
    protected $metas = array();
    /**
     * HTML scripts
     *
     * @var array $jses
     */
    protected $jses = array();
    /**
     * HTML scripts in the end
     *
     * @var array $javascript
     */
    protected $javascript = array();
    /**
     * http attachments
     *
     * @var array $attachments
     */
    protected $attachments = array();
    /**
     * HTML css links
     *
     * @var array $csses
     */
    protected $csses = array();
    /**
     * template files directory
     *
     * @var string $dir
     */
    protected $dir = '';
    /**
     * the theme name
     *
     * @var string $theme
     */
    protected $theme = '';
    /**
     * file path
     *
     * @var string $file
     */
    protected $file = '';
    /**
     * redirect url
     *
     * @var string $redirect
     */
    protected $redirect = '';
    /**
     *redirect delay millis
     */
    protected $delay = 0;
    /**
     *redirect delay script file
     */
    protected $delay_script = '';
    /**
     * rendering plain
     *
     * @var string $out
     */
    protected $plain = '';
    /**
     * if dirty rendering
     *
     * @var string $out
     */
    protected $dirty = '';
    /**
     * 构造方法
     */
    final protected function __construct()
    {
        $this->request = Request::getInstance()->getHttpRequest();
        $this->response = Response::getInstance()->getHttpResponse();
        $this->directory(App::$_docpath); // 初始化文档目录
        $this->language(); // 初始化语言
        $this->resource(); // 初始化语言包
        $this->theme(App::get('theme.main', 'default')); // 初始化主题皮肤
        
        $this->listen();
    }
    final protected function listen()
    {
        App::$_event->listen(App::$_app, App::E_FINISH, array(
                $this,
                'spit'
        ));
    }
    /**
     * get template file path
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }
    /**
     * get template dir path
     *
     * @return string
     */
    public function getDir()
    {
        return $this->dir;
    }
    /**
     * set template theme
     *
     * @return string
     */
    public function setTheme($theme)
    {
        $this->theme($theme);
    }
    /**
     * get template theme
     *
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }
    /**
     * push header for output
     *
     * @param string $header
     *            http header string
     */
    public function header($header)
    {
        $this->headers[] = $header;
    }
    /**
     * push variables with a name
     *
     * @param string $name
     *            name of variable
     * @param mixed $value
     *            value of variable
     */
    public function push($name, $value = null)
    {
        if (is_array($name) && ! Utility::isAssocArray($name)) {
            foreach ($name as $val) {
                $this->push($val, $value);
            }
        } elseif (is_array($name)) {
            // ignore $value
            foreach ($name as $key => $val) {
                $this->push($key, $val);
            }
        } elseif ($name instanceof Iterator) {
            $this->push(iterator_to_array($name));
        } elseif (is_object($name)) {
            $this->push(get_object_vars($name));
        } elseif (! is_null($value) && is_string($name)) {
            $this->vars[$name] = $value;
        } elseif (is_null($value) && is_scalar($name)) {
            $this->vars[] = $name;
        }
    }
    /**
     * set language
     *
     * @param string $lan
     *            language
     */
    public function language($lan = 'zh-cn')
    {
        $supports = App::get('languages', array( 'zh-cn' ));
        $support = App::get('language', 'zh-cn');
        $this->lan = in_array($lan, ( array ) $supports) ? $lan : $support;
    }
    /**
     * set language
     *
     * @param string $lan
     *            language
     */
    public function resource()
    {
        $respath = $this->dir . DIRECTORY_SEPARATOR . 'resource' . DIRECTORY_SEPARATOR . $this->lan . '.php';
        if ($path = realpath($respath)) {
            $this->resources = include $path;
        }
    }
    /**
     * set template dir
     *
     * @param string $dir
     */
    public function directory($dir)
    {
        if ($path = realpath($dir)) {
            $this->dir = $path;
        }
    }
    /**
     * set template filename
     *
     * @param string $filename
     */
    public function file($filename)
    {
        $filepath = $this->dir . DIRECTORY_SEPARATOR . 'theme' . DIRECTORY_SEPARATOR . $this->theme . DIRECTORY_SEPARATOR . $filename;
        if ($path = realpath($filepath)) {
            $this->file = $path;
        }
    }
    /**
     * set template theme name
     *
     * @param string $theme
     */
    public function theme($theme)
    {
        $themepath = $this->dir . DIRECTORY_SEPARATOR . 'theme' . DIRECTORY_SEPARATOR . $theme;
        if (is_dir(realpath($themepath))) {
            $this->theme = $theme;
        }
    }
    /**
     * clean template variables
     */
    public function distinct()
    {
        $this->vars = array();
    }
    /**
     * clean template file and variables
     */
    public function clean()
    {
        $this->file = '';
        $this->vars = array();
        $this->headers = array();
        $this->metas = array();
        $this->jses = array();
        $this->javascript = array();
        $this->attachments = array();
        $this->csses = array();
    }
    /**
     * set meta infomation
     *
     * @param array $meta
     *            array for html meta tag
     */
    public function meta($meta)
    {
        $metas = &$this->metas;
        if (is_array($meta)) {
            foreach ($meta as $i => $m) {
                $metas[] = $m;
            }
        } else {
            $metas[] = $meta;
        }
    }
    /**
     * set include js path
     *
     * @param string $js
     *            javascript file src path in html tag script
     */
    public function js($js)
    {
        $jses = &$this->jses;
        if (is_array($js)) {
            foreach ($js as $i => $j) {
                $jses[] = $j;
            }
        } else {
            $jses[] = $js;
        }
    }
    /**
     * set include js path,those will echo in end of document
     *
     * @param string $js
     *            javascript file src path in html tag script
     */
    public function javascript($js)
    {
        $javascript = &$this->javascript;
        if (is_array($js)) {
            foreach ($js as $i => $j) {
                $javascript[] = $j;
            }
        } else {
            $javascript[] = $js;
        }
    }
    /**
     * set include css path
     *
     * @param string $css
     *            css file link path
     */
    public function css($css)
    {
        $csses = &$this->csses;
        if (is_array($css)) {
            foreach ($css as $i => $c) {
                $csses[] = $c;
            }
        } else {
            $csses[] = $css;
        }
    }
    /**
     * get template headers,
     * return the point of template headers
     *
     * @return array
     */
    public function headers()
    {
        $h = &$this->headers;
        return $h;
    }
    /**
     * get template variables,
     * return the point of template variables
     *
     * @return array
     */
    public function vars()
    {
        $v = &$this->vars;
        return $v;
    }
    /**
     * 默认是懒方法，只是标记了跳转，在输出时才真正地进行跳转
     *
     * @param string $url
     * @param array $params
     * @param boolean $lazy
     * @param int $delay
     */
    public function redirect($url, array $params = array(), $lazy = true, $delay = 0, $delay_script = '')
    {
        $this->redirect = $url . ($params ? '?' . http_build_query($params) : '');
        $this->delay($delay, $delay_script);
        if (empty($lazy)) {
            if ($this->delay > 0) {
                $delay = $this->delay;
                $seconds = floor($this->delay / 1000);
                if (empty($this->delay_script) || !is_file($this->delay_script)) {
                    echo '<meta charset="UTF-8"><meta http-equiv="refresh" content="'.$seconds.';url='.$this->redirect.'">';
                } else {
                    // $this->distinct();
                    $this->push('delay', $delay);
                    $this->push('seconds', $seconds);
                    $this->push('redirect', $this->redirect);
                    $this->file($this->delay_script);
                    // send
                    echo $this->output();
                }
            } else {
                header("Location: {$this->redirect}");
                // more headers
                foreach ($this->headers as $header) {
                    header($header);
                }
            }
            exit();
        }
    }
    /**
     * 设置跳转延迟，毫秒数
     *
     * @param int $delay
     * @param string $delay_script
     */
    public function delay($delay = 0, $delay_script = '')
    {
        if ($delay > 0) {
            $this->delay = intval($delay);
        }
        $filepath = $this->dir . DIRECTORY_SEPARATOR . 'theme' . DIRECTORY_SEPARATOR . $this->theme . DIRECTORY_SEPARATOR . $delay_script;
        if (!empty($delay_script) && $path = realpath($filepath)) {
            $this->delay_script = $delay_script;
        }
    }
    public function image($type = 'png')
    {
        // if dirty data exists
        $this->swallow();
        // if redirecting
        if ($this->redirect) {
            /*header("Location: {$this->redirect}");
            // more headers
            foreach ( $this->headers as $header ) {
                header($header);
            }*/
            $this->redirect($this->redirect, array(), false);
        } else {
            // header json data
            header('Content-Type: image/'.$type);
            // more headers
            foreach ($this->headers as $header) {
                header($header);
            }
            // set css data string
            $results = implode("\n", $this->vars);
            // send
            echo $results;
        }
    }
    /**
     * output as json string
     */
    public function cssp()
    {
        // if dirty data exists
        $this->swallow();
        // if redirecting
        if ($this->redirect) {
            /*header("Location: {$this->redirect}");
            // more headers
            foreach ( $this->headers as $header ) {
                header($header);
            }*/
            $this->redirect($this->redirect, array(), false);
        } else {
            // header json data
            header('Content-Type: text/css');
            // more headers
            foreach ($this->headers as $header) {
                header($header);
            }
            // set css data string
            $results = implode("\n", $this->vars);
            // send
            echo $results;
        }
    }
    /**
     * output as json string
     */
    public function jsonp()
    {
        // if dirty data exists
        $this->swallow();
        // if redirecting
        if ($this->redirect) {
            /*header("Location: {$this->redirect}");
            // more headers
            foreach ( $this->headers as $header ) {
                header($header);
            }*/
            $this->redirect($this->redirect, array(), false);
        } else {
            // header json data
            header('Content-Type: application/javascript');
            // more headers
            foreach ($this->headers as $header) {
                header($header);
            }
            // set javascript data string
            $results = implode("\n", $this->vars);
            // send
            echo $results;
        }
    }
    /**
     * output as json string
     */
    public function json()
    {
        // if dirty data exists
        $this->swallow();
        // if redirecting
        if ($this->redirect) {
            /*header("Location: {$this->redirect}");
            // more headers
            foreach ( $this->headers as $header ) {
                header($header);
            }*/
            $this->redirect($this->redirect, array(), false);
        } else {
            // header json data
            header('Content-Type: application/json');
            // more headers
            foreach ($this->headers as $header) {
                header($header);
            }
            //过滤$CFG
            if (!empty($this->vars['CFG'])) {
                unset($this->vars['CFG']);
            }
            // if cli add time
            if (Utility::isCli()) {
                echo "[". date('Y-m-d H:i:s') . "] ";
            }
            // set varibales data
            if ($cli && version_compare(phpversion(), '5.4.0') > 0) {
                $results = json_encode($this->vars, JSON_PRETTY_PRINT);
            } else {
                $results = json_encode($this->vars);
            }
            // send
            echo $results;
            // if cli add \n
            if (Utility::isCli()) {
                echo "\n";
            }
        }
    }
    /**
     * output as xml string
     */
    public function xml()
    {
        // if dirty data exists
        $this->swallow();
        // if redirecting
        if ($this->redirect) {
            /*header("Location: {$this->redirect}");
            // more headers
            foreach ( $this->headers as $header ) {
                header($header);
            }*/
            $this->redirect($this->redirect, array(), false);
        } else {
            // header xml data
            header('Content-Type: text/xml');
            //过滤$CFG
            if (!empty($this->vars['CFG'])) {
                unset($this->vars['CFG']);
            }
            // more headers
            foreach ($this->headers as $header) {
                header($header);
            }
            // set varibales data
            $results = Utility::array2XML($this->vars);
            // send
            echo $results;
        }
    }
    /**
     * output as csv string
     */
    public function csv()
    {
        // if dirty data exists
        $this->swallow();
        // if redirecting
        if ($this->redirect) {
            /*header("Location: {$this->redirect}");
            // more headers
            foreach ( $this->headers as $header ) {
                header($header);
            }*/
            $this->redirect($this->redirect, array(), false);
        } else {
            // header xml data
            header('Content-Type: application/csv');
            //过滤$CFG
            if (!empty($this->vars['CFG'])) {
                unset($this->vars['CFG']);
            }
            // more headers
            foreach ($this->headers as $header) {
                header($header);
            }
            // set varibales data
            $results = Utility::array2CSV($this->vars);
            // send
            echo $results;
        }
    }
    /**
     * output as template
     *
     * @return void
     */
    public function display()
    {
        // if dirty data exists
        $this->swallow();
        // if redirecting
        if ($this->redirect) {
            /*header("Location: {$this->redirect}");
            // more headers
            foreach ( $this->headers as $header ) {
                header($header);
            }*/
            $this->redirect($this->redirect, array(), false);
        } elseif ($this->file) {
            // header html data
            header('Content-Type: text/html; charset=utf-8');
            // more headers
            foreach ($this->headers as $header) {
                header($header);
            }
            // get output data
            $results = $this->output();
            // send
            echo $results;
        } else {
            $this->json();
        }
    }
    /**
     * get output data
     *
     * @return array
     */
    public function output()
    {
        // if plain data exists
        if ($this->plain) {
            $results = $this->plain;
        } elseif ($this->file) {
            ob_start();
            $_l_ = &$this->lan;
            $_v_ = &$this->vars;
            $_a_ = &$this->attachments;
            $_m_ = &$this->metas;
            $_j_ = &$this->jses;
            $_s_ = &$this->javascript;
            $_c_ = &$this->csses;
            $_h_ = &$this->headers;
            $_r_ = &$this->resources;
            extract($_v_);
            include($this->file);
            // ob_flush();
            $results = $this->plain = ob_get_contents();
            ob_end_clean();
        } else {
            throw new Exception('not template file');
        }
        return $results;
    }
    
    /**
     * swallow output
     *
     * @return void
     */
    public function swallow()
    {
        // ob_start();
        // if dirty data exists
        // ob_flush();
        $cache = ob_get_contents();
        if (! empty($cache)) {
            if (empty($this->dirty)) {
                $this->dirty = $cache;
            } elseif (is_array($this->dirty)) {
                $this->dirty[] = $cache;
            } else {
                $dirty = $this->dirty;
                $this->dirty = array();
                $this->dirty[] = $dirty;
                $this->dirty[] = $cache;
            }
        }
        ob_end_clean();
    }
    /**
     * spit output
     *
     * @return void
     */
    public function spit()
    {
        // ob_start();
        if (! empty($this->dirty) && is_array($this->dirty)) {
            echo json_encode($this->dirty);
        } elseif (! empty($this->dirty)) {
            echo $this->dirty;
        }
        // ob_flush();
    }
}

// PHP END
