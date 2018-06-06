<?php
namespace Dcux\Api\Action\Stat;

use Lay\Advance\Core\Component;
use Lay\Advance\Core\Errode;

use Dcux\Api\Data\VList;
use Dcux\Api\Data\VStatOnline;
use Dcux\Api\Kernel\TApi;
use Dcux\Api\Kernel\TokenApi;
use Dcux\SSO\Service\StatService;

class CurrentOnline extends TokenApi
{
    public function onCreate()
    {
        parent::onCreate();
        $this->statService = StatService::getInstance();
    }
    public function onGet()
    {
        $list = $this->statService->getCurrentOnline();
        $total = count($list);
        $vscl = VStatOnline::parseListSimple($list, $total, $this->genHasNext($total), '');
        $this->success($vscl);
    }
    public function onPost()
    {
        $this->onGet();
    }
}
// PHP END
