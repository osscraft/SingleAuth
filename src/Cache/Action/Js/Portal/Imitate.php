<?php

namespace Dcux\Cache\Action\Js\Portal;

use Autoloader;
use Lay\Advance\Util\Utility;
use Lay\Advance\Util\Logger;
use Lay\Advance\Core\App;

use Dcux\Cache\Kernel\CAction;

use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Assetic\Asset\GlobAsset;
use Assetic\Filter\JSMinFilter;

class Imitate extends CAction {
	public function onGet() {
		$opts = $this->getJsOption();
		$asset = new AssetCollection(array(
			/*new FileAsset(App::$_docpath . '/js/env.js', $opts),
			new FileAsset(App::$_docpath . '/js/config.js', $opts),
			new FileAsset(App::$_docpath . '/js/SSOToOAuth2.js', $opts),
			new FileAsset(App::$_docpath . '/js/app.js', $opts),
			new FileAsset(App::$_docpath . '/js/alt.js', $opts),
			new FileAsset(App::$_docpath . '/js/acceptor.js', $opts),*/
			new FileAsset(App::$_docpath . '/js/portal.js', $opts),
			new FileAsset(App::$_docpath . '/js/portal/imitate.js', $opts),
			new FileAsset(App::$_docpath . '/js/portal/index.js', $opts)
		));
		$arr = array();
		$arr['filename'] = App::$_docpath . DIRECTORY_SEPARATOR . 'cache/js/portal.imitate.js';
		$arr['content'] = $asset->dump();
		$this->template->push($arr['content']);
		$this->push($arr);
	}
    public function onPost() {
        $this->onGet();
    }
}
// PHP END