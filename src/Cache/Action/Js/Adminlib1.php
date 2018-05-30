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

class Adminlib1 extends CAction {
	public function onGet() {
		$opts = $this->getJsOption();
		$asset = new AssetCollection(array(
			new FileAsset(App::$_docpath . '/lib/underscore.js', $opts),
			new FileAsset(App::$_docpath . '/lib/d3/d3.js', $opts),
			new FileAsset(App::$_docpath . '/lib/flexigrid-1.1/js/flexigrid.js', $opts),
			new FileAsset(App::$_docpath . '/lib/md5.js', $opts),
			new FileAsset(App::$_docpath . '/lib/moment/min/moment-with-locales.min.js', $opts),// momentjs
			new FileAsset(App::$_docpath . '/lib/numeral/min/numeral.min.js', $opts),// numeraljs
			new FileAsset(App::$_docpath . '/lib/flot-0.8.3/jquery.flot.js', $opts),
			new GlobAsset(App::$_docpath . '/lib/flot-0.8.3/jquery.flot.*.js', $opts)
		));
		$arr = array();
		$arr['filename'] = App::$_docpath . DIRECTORY_SEPARATOR . 'cache/js/adminlib1.js';
		$arr['content'] = $asset->dump();
		$this->template->push($arr['content']);
		$this->push($arr);
	}
    public function onPost() {
        $this->onGet();
    }
}
// PHP END