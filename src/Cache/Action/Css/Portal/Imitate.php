<?php

namespace Dcux\Cache\Action\Css\Portal;

use Autoloader;
use Lay\Advance\Util\Utility;
use Lay\Advance\Util\Logger;
use Lay\Advance\Core\App;

use Dcux\Cache\Kernel\CAction;

use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Assetic\Asset\GlobAsset;
use Assetic\Filter\CssMinFilter;

class Imitate extends CAction
{
    public function onGet()
    {
        $opts = $this->getCssOption();
        $asset = new AssetCollection(array(
            new FileAsset(App::$_docpath . '/css/portal.imitate.css', $opts)
        ));
        $arr = array();
        $arr['filename'] = App::$_docpath . DIRECTORY_SEPARATOR . 'cache/css/portal.imitate.css';
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
