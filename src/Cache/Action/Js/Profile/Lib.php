<?php

namespace Dcux\Cache\Action\Js\Profile;

use Autoloader;
use Lay\Advance\Util\Utility;
use Lay\Advance\Util\Logger;
use Lay\Advance\Core\App;

use Dcux\Cache\Kernel\CAction;

use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Assetic\Asset\GlobAsset;
use Assetic\Filter\JSMinFilter;

class Lib extends CAction
{
    public function onGet()
    {
        $env = App::get('env', 'test');
        $opts = $this->getJsOption();
        $asset = new AssetCollection(array(
            new FileAsset(App::$_docpath . '/lib/jquery/jquery.js', $opts),// jquery 1.9.1
            new FileAsset(App::$_docpath . '/lib/jquery/jquery-migrate-1.2.1.min.js', $opts), // migrate old version, > 1.9
            new GlobAsset(App::$_docpath . '/lib/jquery/jquery.*.js', $opts),// simple jquery plugins
            new FileAsset(App::$_docpath . '/lib/md5.js', $opts),
            new FileAsset(App::$_docpath . '/lib/moment/min/moment-with-locales.min.js', $opts),// momentjs
            new FileAsset(App::$_docpath . '/lib/numeral/min/numeral.min.js', $opts),// numeraljs
            // own lib
            new FileAsset(App::$_docpath . '/js/config/config.'.$env.'.js', $opts),
            new FileAsset(App::$_docpath . '/js/SSOToOAuth2.js', $opts),
            new FileAsset(App::$_docpath . '/js/app.js', $opts),
            new FileAsset(App::$_docpath . '/js/alt.js', $opts),
            new FileAsset(App::$_docpath . '/js/util.js', $opts),
            new FileAsset(App::$_docpath . '/js/acceptor.js', $opts)
        ));
        $arr = array();
        $arr['filename'] = App::$_docpath . DIRECTORY_SEPARATOR . 'cache/js/profile.lib.js';
        $arr['content'] = $asset->dump();
        $this->template->push($arr['content']);
        $this->push($arr);
    }
    public function onPost()
    {
        $this->onGet();
    }
}
// PHP END
