<?php

namespace Dcux\Core;

use Dcux\Core\Singleton;
use Dcux\Core\App;
use Dcux\Core\EventEmitter;
use Dcux\Http\Request;
use Dcux\Http\Response;
use Dcux\Core\Template;
use Dcux\Core\Actionizable;
use Dcux\Util\Logger;
use Dcux\Util\Utility;
use Dcux\Autoloader;

abstract class Action extends Singleton implements Actionizable
{
    // use Singleton;
    /**
     * 事件常量，创建时
     *
     * @var string
     */
    const E_CREATE = 'action:event:create';
    /**
     * 事件常量，验证时
     */
    const E_VALIDATE = 'action:event:validate';
    /**
     * 事件常量，STATE
     *
     * @var string
     */
    const E_STATE = 'action:event:state';
    /**
     * 事件常量，GET时
     *
     * @var string
     */
    const E_GET = 'action:event:get';
    /**
     * 事件常量，POST时
     *
     * @var string
     */
    const E_POST = 'action:event:post';
    /**
     * 事件常量，PUT
     *
     * @var string
     */
    const E_PUT = 'action:event:put';
    /**
     * 事件常量，DELETE
     *
     * @var string
     */
    const E_DELETE = 'action:event:delete';
    /**
     * 事件常量，HEAD
     *
     * @var string
     */
    const E_HEAD = 'action:event:head';
    /**
     * 事件常量，PATCH
     *
     * @var string
     */
    const E_PATCH = 'action:event:patch';
    /**
     * 事件常量，OPTIONS
     *
     * @var string
     */
    const E_OPTIONS = 'action:event:options';
    /**
     * 事件常量，渲染时
     *
     * @var string
     */
    const E_RENDER = 'action:event:render';
    
    /**
     * HttpRequest
     *
     * @var HttpRequest
     */
    protected $request;
    /**
     * HttpResponse
     *
     * @var HttpResponse
     */
    protected $response;
    /**
     * 存放业务逻辑对象的数组
     *
     * @var array
     */
    protected $services = array();
    /**
     * 模板引擎对象
     *
     * @var Template
     */
    protected $template;
    
    /**
     * 构造方法
     *
     * @return Action
     */
    protected function __construct()
    {
    }
    /**
     * App初始化
     *
     * @return void
     */
    public function initialize()
    {
        $this->request = Request::getInstance();
        $this->response = Response::getInstance();
        $this->template = Template::getInstance();
    }
    /**
     * Action生命同期
     *
     * @return void
     */
    public function lifecycle()
    {
        $class= get_class($this);
        // on create
        $this->onCreate();
        App::$_event->fire($class, Action::E_CREATE, array(
                $this
        ));
        $valid = $this->onValidate();
        App::$_event->fire($class, Action::E_VALIDATE, array(
                $this, $valid
        ));
        if (!empty($valid)) {
            $method = $this->request->getMethod();
            $method = empty($method) ? 'get' : $method;
            // on
            $this->{'on' . ucfirst(strtolower($method))}();
            App::$_event->fire($class, Action::E_STATE, array(
                    $this
            ));
            App::$_event->fire($class, $this->method2Event($method), array(
                    $this
            ));
        }
        // render
        $this->onRender();
        App::$_event->fire($class, Action::E_RENDER, array(
                $this
        ));
    }
    /**
     * convert http method to Action event
     *
     * @return void
     */
    private function method2Event($method)
    {
        switch (strtoupper($method)) {
            case 'POST':
                $event = Action::E_POST;
                break;
            case 'PUT':
                $event = Action::E_PUT;
                break;
            case 'DELETE':
                $event = Action::E_DELETE;
                break;
            case 'PATCH':
                $event = Action::E_PATCH;
                break;
            case 'HEAD':
                $event = Action::E_HEAD;
                break;
            case 'OPTIONS':
                $event = Action::E_OPTIONS;
                break;
            case 'GET':
            default:
                $event = Action::E_GET;
                break;
        }
        return $event;
    }
    
    /**
     * 返回HttpRequest
     *
     * @return HttpRequest
     */
    public function getRequest()
    {
        return $this->request;
    }
    /**
     * 返回HttpReponse
     *
     * @return HttpReponse
     */
    public function getResponse()
    {
        return $this->response;
    }
    /**
     * 返回模板引擎对象
     *
     * @return Template
     */
    public function getTemplate()
    {
        return $this->template;
    }
    /**
     * 获取Service对象
     *
     * @param string $classname
     * @return Service
     */
    public function service($classname)
    {
        if (! array_key_exists($classname, $this->services) && class_exists($classname)) {
            $this->services[$classname] = $classname::getInstance();
        }
        return $this->services[$classname];
    }
    
    /**
     * 创建事件触发方法
     */
    public function onCreate()
    {
    }
    /**
     * 执行onGet,onPost等方法前的验证方法;如果返回true，执行onGet,onPost等方法.
     * 默认返回true
     * @return boolean
     */
    public function onValidate()
    {
        return true;
    }
    /**
     * GET事件触发方法
     */
    public function onGet()
    {
    }
    /**
     * POST事件触发方法
     */
    public function onPost()
    {
    }
    public function onPut()
    {
    }
    public function onDelete()
    {
    }
    public function onPatch()
    {
    }
    public function onHead()
    {
    }
    public function onOptions()
    {
    }
    public function onRender()
    {
        header('X-Powered-By: dcux');
        $rep = $this->request->getExtension();
        switch ($rep) {
            case 'json':
                $this->template->json();
                break;
            case 'xml':
                $this->template->xml();
                break;
            case 'css':
                $this->template->cssp();
                break;
            case 'csv':
                $this->template->csv();
                break;
           /* case 'src' :
                $pathname = Autoloader::getClass(get_class($this));
                highlight_file($pathname);
                break;*/
            case 'png':
            case 'jpg':
            case 'jpeg':
            case 'gif':
                $this->template->image($rep);
                break;
            case '':
            case 'html':
            case 'htm':
            case 'php':
                $this->template->display();
                break;
            case 'js':
            case 'jsonp':
            default:
                $this->template->jsonp();
                break;
        }
    }
}
