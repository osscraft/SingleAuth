<?php

namespace Dcux\Admin\Kernel;

use Lay\Advance\Core\Action;
use Lay\Advance\Core\Configuration;
use Lay\Advance\Util\Logger;

use Dcux\SSO\Kernel\SAction;
use Dcux\SSO\Service\UserGrantService;

abstract class AAction extends SAction
{
    protected $grants = array();
    protected $isSuper = false;
    protected $grantService;
    /**
     * admin action id
     */
    abstract public function cmd();
    public function onCreate()
    {
        parent::onCreate();
        $this->grantService = UserGrantService::getInstance();
    }
    /**
     * 生成当前用户的有权限菜单ID数组，如果是超级管理员，则为空
     */
    protected function genGrants()
    {
        if (!empty($_SESSION['user'])) {
            $uid = $_SESSION['user']['uid'];
            if ($_SESSION['user']['isAdmin'] > 1) {
                $this->isSuper = true;
            } else {
                $grant = $this->grantService->get($uid);
                if (!empty($grant)) {
                    $grants = trim($grant['grants'], ' ;');
                    $this->grants = empty($grants) ? array() : explode(';', $grants);
                }
            }
        }
        return $this->grants;
    }
    /**
     * get menu
     * @param array $menu
     */
    public function menu()
    {
        global $CFG;
        $this->genGrants();// 生成可用权限数组
        $cmd = $this->cmd();
        if (!empty($CFG['menu'])) {
            $menu = array();
            foreach ($CFG['menu'] as $k => &$m) {
                $_m = $this->_menu($m, $cmd);
                if (!empty($_m)) {
                    $menu[] = $m;
                }
            }
            $CFG['menu'] = $menu;
        }
        return $CFG['menu'];
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
            if ($this->isSuper || in_array($menu['id'], $this->grants)) {
                // 超级管理员或有权限的管理员
                return $menu;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function chain()
    {
        global $CFG;
        $chain = array();
        if (!empty($CFG['menu'])) {
            foreach ($CFG['menu'] as $k => &$m) {
                $_m = $this->_chain($chain, $m);
                if (!empty($_m)) {
                    array_unshift($chain, $m);
                }
            }
        }
        return $chain;
    }
    protected function _chain(&$chain, &$menu)
    {
        if (!empty($menu['children'])) {
            $children = array();
            foreach ($menu['children'] as &$m) {
                //检查子节点是否是激活的
                $_m = $this->_chain($chain, $m);
                //检查返回子节点是激活的
                if (!empty($_m)) {
                    array_unshift($chain, $m);
                }
            }
            //$menu['children'] = $children;
        }
        if (empty($menu['disable']) && !empty($menu['active'])) {
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
        global $CFG;
        if (!empty($CFG['menu'])) {
            $menu = array();
            foreach ($CFG['menu'] as $k => &$m) {
                //检查子节点是否是在可访问权限内
                $grant = $this->_grant($m, $grants);
                if (!empty($grant)) {
                    $menu[] = $m;
                }
            }
            $CFG['menu'] = $menu;
        }
        return $CFG['menu'];
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
    /**
     * 使用JS跳跃页,默认返回上一页。
     * 如果在子iframe可执行则会进行检测，再调用CMS中的接收方法
     * @param string $info 弹出信息
     * @param int $p 数值：跳转历史页；字符串：跳转至指定页；数组：见cms.js中的App.Acceptor对象
     * @param boolean $success
     */
    protected function skip($info = '', $data = 0, $success = false)
    {
        $cmd = $this->cmd();
        $response = array();
        $response['cmd'] = $cmd;
        $response['info'] = $info;
        $response['data'] = $data;
        $response['success'] = $success;
        $this->template->push($response);
        $this->template->file('skip.php');
    }

    // overide
    protected function initTheme()
    {
        global $CFG;
        // init template dir
        $this->template->directory(\Lay\Advance\Core\App::$_docpath . DIRECTORY_SEPARATOR . 'admin');
        // init by configuration
        $this->template->theme(empty($CFG['theme']['admin']) ? 'default' : $CFG['theme']['admin']);
        // init by cookie
        if (!empty($_COOKIE['theme_admin']) && !empty($CFG['theme_customize'])) {
            $this->template->theme($_COOKIE['theme_admin']);
        }
        // new feature,change theme by request
        if (!empty($_REQUEST['_theme'])) {
            $this->template->theme($_REQUEST['_theme']);
        }
    }
    public function onRender()
    {
        // set default code
        $vars = $this->template->vars();
        if (!isset($vars['code'])) {
            $this->template->push('code', 0);
        }
        parent::onRender();
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
