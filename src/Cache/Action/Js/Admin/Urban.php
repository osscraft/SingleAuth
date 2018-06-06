<?php

namespace Dcux\Cache\Action\Js\Admin;

use Autoloader;
use Lay\Advance\Util\Utility;
use Lay\Advance\Util\Logger;
use Lay\Advance\Core\App;

use Dcux\Cache\Kernel\CAction;

use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Assetic\Asset\GlobAsset;
use Assetic\Filter\JSMinFilter;

class Urban extends CAction
{
    public function onGet()
    {
        $opts = $this->getJsOption();
        $asset = new AssetCollection(array(
            new FileAsset(App::$_docpath . '/js/admin/urban.js', $opts),
            new GlobAsset(App::$_docpath . '/js/admin/urban.*.js', $opts)
        ));
        $arr = array();
        $arr['filename'] = App::$_docpath . DIRECTORY_SEPARATOR . 'cache/js/admin.urban.js';
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
