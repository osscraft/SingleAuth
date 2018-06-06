<?php

namespace Dcux\SSO\Action;

use Dcux\SSO\Kernel\UAction;
use Dcux\SSO\Core\MemSession;

class VerifyCode extends UAction
{
    public function onGet()
    {
        if ($_REQUEST['key'] == 'verifyCode') {
            \Dcux\SSO\Kernel\VerifyCode::getCode(4, 100, 45);
            exit();
        } elseif ($_REQUEST['key'] == 'checkCode') {
            // MemSession::getSession();
            $action = $_REQUEST['act'];
            $code = trim($_REQUEST['verifyCode']);
            // echo $_SESSION["verifyCode"];
            if ($action == 'char') {
                if ($code == $_SESSION["verifyCode"]) {
                    // echo "correct";exit;
                    $this->template->push('success', '1');
                } else {
                    $this->template->push('success', '2');
                    // echo "wrong";
                }
            } else {
                // echo "no param";
                $this->template->push('success', '3');
            }
        }
    }
    public function onPost()
    {
        $this->onGet();
    }
}
// PHP END
