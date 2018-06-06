<?php

namespace Dcux\SSO\Kernel;

use Dcux\Core\Action;
use Dcux\Core\Configuration;
use Dcux\SSO\Kernel\SAction;
use Dcux\Util\Logger;

abstract class AAction extends SAction
{
    /**
     * get cmd
     * @param string $name
     * @return string
     */
    public function cmd()
    {
        if (empty($this->name)) {
            return $this->name = strtolower(substr(get_called_class(), strlen('Cms_App_')));
        } else {
            return $this->name;
        }
    }
    /**
     * get menu
     * @param array $menu
     */
    public function menu()
    {
        $cmd = $this->cmd();
        if (!empty(self::$menu)) {
            $menu = array();
            foreach (self::$menu as $k => &$m) {
                $_m = $this->_menu($m, $cmd);
                if (!empty($_m)) {
                    $menu[] = $m;
                }
            }
            self::$menu = $menu;
        }
        return self::$menu;
    }
    /**
     * 激活菜单节点
     * @param array $menu
     * @param string $activeid
     * @return boolean
     */
    protected function _menu(&$menu, $activeid)
    {
        //设置默认不是激活的
        $menu['active'] = false;
        $menu['disable'] = empty($menu['disable']) ? false : true;
        $menu['alias'] = empty($menu['alias']) ? array() : (array)$menu['alias'];
        $menu['implication'] = empty($menu['implication']) ? array() : (array)$menu['implication'];
        if (empty($menu['href'])) {
            $menu['href'] = 'javascript:;';
        }
        if (!empty($menu['children'])) {
            $children = array();
            foreach ($menu['children'] as &$m) {
                //检查子节点是否是要激活的，如需要则激活并返回激活状态
                $_m = $this->_menu($m, $activeid);
                //如果父节点是激活的，则忽略，如果父节点不是激活的，则服从子节点的激活状态
                $menu['active'] = $menu['active'] ? : (empty($_m) ? false : $_m['active']);
                //检查返回子节点是否可用
                if (!empty($_m)) {
                    $children[] = $m;
                }
            }
            $menu['children'] = $children;
        }
        //比对需激活的ID与菜单ID或别名ID及隐式ID
        $extras = array_merge($menu['alias'], $menu['implication']);
        if ($menu['id'] == $activeid || in_array($activeid, $extras)) {
            //只有是末节点时才设置
            if (empty($menu['children'])) {
                //设置最新激活的菜单
                $this->current = $menu;
            }
            $menu['active'] = true;
            //return $menu['active'] = true;
        }
        if (empty($menu['disable'])) {
            return $menu;
        } else {
            return false;
        }
    }
    /**
     * 过滤掉没有权限的菜单
     * @param array $grants
     * @return boolean|array
     */
    protected function grant($grants = array())
    {
        if (!empty(self::$menu)) {
            $menu = array();
            foreach (self::$menu as $k => &$m) {
                //检查子节点是否是在可访问权限内
                $grant = $this->_grant($m, $grants);
                if (!empty($grant)) {
                    $menu[] = $m;
                }
            }
            self::$menu = $menu;
        }
    }
    /**
     * 检测权限
     * @param array $menu
     * @param array $grants
     * @return boolean
     */
    protected function _grant(&$menu, $grants = array())
    {
        if (!empty($menu['children'])) {
            $children = array();
            foreach ($menu['children'] as $k => &$m) {
                //检查子节点是否是在可访问权限内
                $grant = $this->_grant($m, $grants);
                if (!empty($grant)) {
                    $children[] = $m;
                }
            }
            $menu['children'] = $children;
        }
        //不是授权的节点 ，同时不存在子节点
        if (empty($menu['children']) && !in_array($menu['id'], $grants)) {
            return false;
        } else {
            return true;
        }
    }
    
    public function onCreate()
    {
        global $CFG;
        // init config
        $this->initConfig();
        // init template dir
        $this->template->directory(\Dcux\Core\App::$_docpath . DIRECTORY_SEPARATOR . 'admin');
        $this->template->theme(empty($CFG['theme']['admin']) ? 'default' : $CFG['theme']['admin']);
        parent::onCreate();
    }
    public function onRender()
    {
        global $CFG;
        // add menu
        $this->template->push('menu', $CFG['menu']);
        parent::onRender();
    }
    protected function initConfig()
    {
        $path = \Dcux\Core\App::$_rootpath;
        $env = \Dcux\Core\App::get('env', 'test');
        $configfile = $path . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'main.' . $env . '.php';
        Configuration::configure($configfile);
    }
    protected function errorResponse($error, $error_description = null, $error_uri = null)
    {
        global $CFG;
        $result['error'] = $error;
        
        if (! empty($CFG['display_error']) && $error_description) {
            $result["error_description"] = $error_description;
        }
        
        if (! empty($CFG['display_error']) && $error_uri) {
            $result["error_uri"] = $error_uri;
        }
        
        $this->template->push($result);
    }
}
