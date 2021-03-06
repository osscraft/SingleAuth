<?php

namespace Dcux\Cache\Action\Js;

use Autoloader;
use Lay\Advance\Util\Utility;
use Lay\Advance\Util\Logger;
use Lay\Advance\Core\App;

use Dcux\Cache\Kernel\CAction;

use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Assetic\Asset\GlobAsset;
use Assetic\Filter\JSMinFilter;

class Jquery extends CAction
{
    public function onGet()
    {
        $opts = $this->getJsOption();
        $asset = new AssetCollection(array(
            new FileAsset(App::$_docpath . '/lib/jquery/jquery.js', $opts),
            new FileAsset(App::$_docpath . '/lib/jquery/jquery-migrate-1.2.1.min.js', $opts),
            new GlobAsset(App::$_docpath . '/lib/jquery/jquery.*.js', $opts)
        ));
        $arr = array();
        $arr['filename'] = App::$_docpath . DIRECTORY_SEPARATOR . 'cache/js/jquery.js';
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
