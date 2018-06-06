<?php

namespace Dcux\SSO\Kernel;

use Dcux\SSO\Kernel\App;
use Dcux\Core\Action;
use Dcux\Util\Utility;
use Dcux\Util\Logger;

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
    public function cache()
    {
        global $CFG;
        if (!empty($CFG['cache_open']) && !empty($this->cachefiles)) {
            foreach ($this->cachefiles as $cache) {
                $f = empty($cache['filename']) ? false : $cache['filename'];
                $c = empty($cache['content']) ? false : $cache['content'];
                if (!empty($f) && !empty($c)) {
                    Utility::createFolders(dirname($f));
                    file_put_contents($f, $c);
                    chmod($f, 0777);
                }
            }
        }
    }
    /**
     * 清除前端缓存
     */
    public function cleanCache()
    {
        $cachedir = App::$_docpath . DIRECTORY_SEPARATOR . 'cache';
        Utility::rmdir($cachedir, false);
    }
}

// PHP END
