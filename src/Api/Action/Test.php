<?php
namespace Dcux\Api\Action;

use Lay\Advance\Util\Logger;
use Lay\Advance\Core\Errode;
use Lay\Advance\Core\Error;

use Dcux\Api\Data\VList;
use Dcux\Api\Kernel\Api;

use Respect\Validation\Validator;
use Exception;

class Test extends Api
{
    public function onGet()
    {
        //throw Errode::invalid_param('action')->error();
        $e = new Error(Errode::invalid_param('action'), 999998);//throw new Exception('Test Exception');
        //$e = new Exception('unkown error');
        //$e = Errode::invalid_param('action')->error();
        //$e = Errode::invalid_param('action')->error(new Error('test error', 999997));
        //$e = Errode::invalid_param('action')->error(new Exception('test error', 999996));
        //$e = Errode::__error(new Error('test error', 999995));
        //$e = Errode::__error(new Exception('test error', 999994));
        //print_r(Errode::$stack);exit;
        //$this->failure(Errode::invalid_param('action'));
        throw $e;
        //$this->success($list);
        //$e = new Exception();var_dump($this->params);exit;
        //$this->success($this->params);
    }
    public function onPost()
    {
        $this->onGet();
    }

    protected function params()
    {
        return array(
            'id' => array(
                'validator' => array(
                    Validator::keyREQUEST('id', Validator::numeric()->notEmpty())
                ),
                'default' => 1
            )
        );
    }
}
// PHP END
