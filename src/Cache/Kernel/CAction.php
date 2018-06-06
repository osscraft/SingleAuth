<?php

namespace Dcux\Cache\Kernel;

use Lay\Advance\Core\Action;
use Lay\Advance\Util\Utility;
use Lay\Advance\Util\Logger;

use Dcux\Cache\Kernel\App;

use Assetic\Filter\CssMinFilter;
use Assetic\Filter\JSMinFilter;

abstract class CAction extends Action
{
    /**
     * array(
     *     array('filename' => '/foo/foo.js', 'content' => 'var foo = 1;'),
     *     array('filename' => '/foo/bar.js', 'content' => 'var bar = 1;')
     * )
     * @var array
     */
    protected $cachefiles = array();
    
    public function onCreate()
    {
        parent::onCreate();
        App::$_event->listen(App::$_app, App::E_FINISH, array($this, 'cache'));
    }
    protected function push($cache)
    {
        if (is_array($cache) && !empty($cache)) {
            $this->cachefiles[] = $cache;
        }
    }
    protected function getOption($opt = array())
    {
        return array();
    }
    protected function getCssOption($opt = array())
    {
        global $CFG;
        $opts = empty($CFG['frontcache']['css']['minimize']) ? array() : array(new CssMinFilter());
        return $opts;
    }
    protected function getJsOption($opt = array())
    {
        global $CFG;
        $opts = empty($CFG['frontcache']['js']['minimize']) ? array() : array(new JSMinFilter());
        return $opts;
    }
    public function cache()
    {
        global $CFG;
        $image_exts = array('png', 'jpg');
        if (!empty($this->cachefiles)) {
            foreach ($this->cachefiles as $cache) {
                $f = empty($cache['filename']) ? false : $cache['filename'];
                $c = empty($cache['content']) ? false : $cache['content'];
                $e = Utility::getExtension($f);
                if (empty($c) || empty($f)) {
                    continue;
                } elseif (!empty($e) && $e == 'js' && empty($CFG['frontcache']['js']['open'])) {
                    continue;
                } elseif (!empty($e) && $e == 'css' && empty($CFG['frontcache']['css']['open'])) {
                    continue;
                } elseif (!empty($e) && in_array($e, $image_exts) && empty($CFG['frontcache']['css']['image'])) {
                    continue;
                }
                // ...
                Utility::createFolders(dirname($f));
                file_put_contents($f, $c);
                chmod($f, 0777);
            }
        }
    }
    /**
     * 清除前端缓存
     */
    public function cleanCache()
    {
        // to do remove js ,css ...
        $cachedir = App::$_docpath . DIRECTORY_SEPARATOR . 'cache/js';
        Utility::rmdir($cachedir, true);
        $cachedir = App::$_docpath . DIRECTORY_SEPARATOR . 'cache/css';
        Utility::rmdir($cachedir, true);
    }
}

// PHP END
