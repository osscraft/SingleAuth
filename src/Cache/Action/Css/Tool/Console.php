<?php

namespace Dcux\Cache\Action\Css\Tool;

use Autoloader;
use Lay\Advance\Util\Utility;
use Lay\Advance\Util\Logger;
use Lay\Advance\Core\App;

use Dcux\Cache\Kernel\CAction;

use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Assetic\Asset\GlobAsset;
use Assetic\Filter\CssMinFilter;

class Console extends CAction
{
    public function onGet()
    {
        $opts = $this->getCssOption();
        $asset = new AssetCollection(array(
            new FileAsset(App::$_docpath . '/css/tool.console.css', $opts)
        ));
        $arr = array();
        $arr['filename'] = App::$_docpath . DIRECTORY_SEPARATOR . 'cache/css/tool.console.css';
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
