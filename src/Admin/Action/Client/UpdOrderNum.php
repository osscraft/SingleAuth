<?php

namespace Dcux\Admin\Action\Client;

use Dcux\Admin\Kernel\AAction;
use Dcux\SSO\Manager\ClientManager;

class UpdOrderNum extends AAction {
    public function onGet() {
        global $CFG;
        $st = time() + microtime();
        $out = array ();
        $_REQUEST['key'] = empty($_REQUEST['key']) ? 'list' : $_REQUEST['key'];
        
        if (empty($_SESSION['token']) || empty($_SESSION['user'])) {
            $this->template->redirect('index.php');
        } else {
            if (! empty($_REQUEST['id'])) {
                $client = ClientManager::readClient();
                if (! empty($client)) {
                    $client['clientOrderNum'] = ClientManager::getinstance()->updateClientOrderNum($client['clientId']);
                    $out['CLIENT'] = $client;
                    $this->template->push($out);
                } else {
                    $this->errorResponse('invalid_client');
                }
            } else {
                $this->errorResponse('invalid_client');
            }
        }
        
        return;
    }
    public function onPost() {
        $this->onGet();
    }
}
// PHP END