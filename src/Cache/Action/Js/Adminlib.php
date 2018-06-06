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

class Adminlib extends CAction
{
    public function onGet()
    {
        $opts = $this->getJsOption();
        $asset = new AssetCollection(array(
            //new FileAsset(App::$_docpath . '/lib/underscore.js', $opts),
            //new FileAsset(App::$_docpath . '/lib/angular/angular.js', $opts),
            new FileAsset(App::$_docpath . '/lib/jquery/jquery.js', $opts),// jquery 1.9.1
            new FileAsset(App::$_docpath . '/lib/jquery/jquery-migrate-1.2.1.min.js', $opts), // migrate old version, > 1.9
            new FileAsset(App::$_docpath . '/lib/bootstrap/dist/js/bootstrap.js', $opts),// bootstrap
            new GlobAsset(App::$_docpath . '/lib/bootstrap/js/*.js', $opts),//
            new FileAsset(App::$_docpath . '/lib/datatables/media/js/jquery.dataTables.js', $opts),// for jquery dataTables
            new GlobAsset(App::$_docpath . '/lib/jquery/jquery.*.js', $opts),// simple jquery plugins
            new FileAsset(App::$_docpath . '/lib/datatables/extensions/jquery.dataTables.editable.js', $opts),
            new FileAsset(App::$_docpath . '/lib/datatables/extensions/dataTables.bootstrap.js', $opts),// datatables bootstrap style
            new FileAsset(App::$_docpath . '/lib/noty/jquery.noty.js', $opts),//noty lib
            new FileAsset(App::$_docpath . '/lib/noty/extensions/noty-defaults.js', $opts),// for noty default style
            new FileAsset(App::$_docpath . '/lib/md5.js', $opts),
            new FileAsset(App::$_docpath . '/lib/bootbox.js', $opts),//bootstrap modal dialog
            new FileAsset(App::$_docpath . '/lib/checkBo/checkBo.js', $opts),//bootstrap modal dialog
            new FileAsset(App::$_docpath . '/lib/moment/min/moment-with-locales.min.js', $opts),//momentjs
            new FileAsset(App::$_docpath . '/lib/numeral/min/numeral.min.js', $opts),// numeraljs
            new FileAsset(App::$_docpath . '/lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js', $opts),// perfect-scrollbar
            new FileAsset(App::$_docpath . '/lib/flot-0.8.3/jquery.flot.js', $opts),// flot
            new GlobAsset(App::$_docpath . '/lib/flot-0.8.3/jquery.flot.*.js', $opts),
            new FileAsset(App::$_docpath . '/lib/flexigrid-1.1/js/flexigrid.js', $opts),// flexgrid
            new FileAsset(App::$_docpath . '/lib/sweetalert/dist/sweetalert.min.js', $opts),
            new FileAsset(App::$_docpath . '/lib/summernote/dist/summernote.js', $opts),// summernote
            new FileAsset(App::$_docpath . '/lib/summernote/lang/summernote-zh-CN.js', $opts),// summernote language
            new FileAsset(App::$_docpath . '/lib/d3/d3.js', $opts) // d3
        ));
        $arr = array();
        $arr['filename'] = App::$_docpath . DIRECTORY_SEPARATOR . 'cache/js/adminlib.js';
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
