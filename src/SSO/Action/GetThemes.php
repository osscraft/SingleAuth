<?php

namespace Dcux\SSO\Action;

use Lay\Advance\Core\App;
use Lay\Advance\Core\Configuration;

use Dcux\SSO\Kernel\Action;

class GetThemes extends Action
{
    public function onGet()
    {
        global $CFG;
        $data = array();
        $k=$_REQUEST['k'];
        $param=explode('.', $k);
        $data['themes'] = $CFG['themes'][$param[1]];
        $this->template->push('data', $data);
    }
    
    public function onPost()
    {
        $this->onGet();
    }
}
// PHP END
