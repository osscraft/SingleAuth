<?php

namespace Lay\Advance\Core;

interface Templatelizable extends Variously {
    /**
     * push variables with a name
     *
     * @param string $name
     *            name of variable
     * @param mixed $value
     *            value of variable
     */
    public function push($name, $value = null);
    /**
     * set template filename
     *
     * @param string $filename            
     */
    public function file($filename);
    /**
     * set template theme name
     *
     * @param string $theme            
     */
    public function theme($theme);
    /**
     * 此方法只是标记了跳转，在输出时才真正地进行跳转
     * 
     * @param string $url            
     * @param array $params            
     */
    public function redirect($url, array $params = array(), $lazy = true);
    /**
     * output as css string
     */
    public function cssp();
    /**
     * output as javascript string
     */
    public function jsonp();
    /**
     * output as json string
     */
    public function json();
    /**
     * output as xml string
     */
    public function xml();
    /**
     * output as csv string
     */
    public function csv();
    /**
     * display as string
     *
     * @return void
     */
    public function display();
    /**
     * get output data
     *
     * @return array
     */
    public function output();
}