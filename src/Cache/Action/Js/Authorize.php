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

class Authorize extends CAction {
	public function onGet() {
		$env = App::get('env', 'test');
		$opts = $this->getJsOption();
		$asset = new AssetCollection(array(
			new FileAsset(App::$_docpath . '/js/config/config.'.$env.'.js', $opts),
			new FileAsset(App::$_docpath . '/js/SSOToOAuth2.js', $opts),
			new FileAsset(App::$_docpath . '/js/app.js', $opts),
			new FileAsset(App::$_docpath . '/js/alt.js', $opts),
			new FileAsset(App::$_docpath . '/js/util.js', $opts),
			new FileAsset(App::$_docpath . '/js/acceptor.js', $opts),
			new FileAsset(App::$_docpath . '/js/authorize.js', $opts)
		));
		$arr = array();
		$arr['filename'] = App::$_docpath . DIRECTORY_SEPARATOR . 'cache/js/authorize.js';
		$arr['content'] = $asset->dump();
		$this->template->push($arr['content']);
		$this->push($arr);
	}
    public function onPost() {
        $this->onGet();
    }
}
// PHP END