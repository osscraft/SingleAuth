<?php

namespace Dcux\Admin\Action;

use Dcux\Admin\Kernel\AAction;
use Dcux\Admin\Kernel\MenuPermission;
use Dcux\SSO\Service\ClientService;

class Stat extends MenuPermission
{
    public function cmd()
    {
        return 'statistics';
    }
    public function onCreate()
    {
        parent::onCreate();
        $t = $this->template->getTheme();
        if (!empty($t) && $t != 'default') {//different theme different php
            //直接跳至
            $this->template->redirect('/admin/stat/summary.php', array(), false);
        }
        if (empty($_SESSION['token']) || empty($_SESSION['user']) || $_SESSION['user']['isAdmin'] < 1) {
            $this->template->redirect('index.php', array(), false);
        }
    }
    public function onGet()
    {
        global $CFG;
        $st = time() + microtime();
        $out = array();
        $_REQUEST['key'] = empty($_REQUEST['key']) ? 'view' : $_REQUEST['key'];
        $out['TITLE'] = $CFG['LANG']['STATISTICS'];
        $out['TOKEN'] = $_SESSION['token'];
        $out['USER'] = $_SESSION['user'];
        $out['SESSION'] = $_SESSION;
        
        switch ($_REQUEST['key']) {
            case 'client':
                $this->doClient();
                break;
            case 'view':
            default:
                $this->doView();
                break;
        }
        $et = time() + microtime();
        $out['SIGN'] = 'stat';
        $out['LANG'] = $CFG['LANG'];
        $out['TIME'] = array(
                'START_TIME' => $st,
                'END_TIME' => $et,
                'DIFF_TIME' => ($et - $st)
        );
        $out['RAND'] = rand(100000, 999999);
        $this->template->push($out);
    }
    public function onPost()
    {
        $this->onGet();
    }

    protected function doClient()
    {
        global $CFG;
        $out = array();
        $id = empty($_REQUEST['id']) ? false : $_REQUEST['id'];
        $out['CLIENT'] = ClientService::readClient($id);
        $out['TITLE'] .= $CFG['LANG']['TITLE_SPLIT_SIGN'] . $CFG['LANG']['VIEW'] . $CFG['LANG']['TITLE_SPLIT_SIGN'] . $out['CLIENT']['clientName'];
        $this->template->push($out);
        $this->template->file('stat/client.php');
    }
    protected function doView()
    {
        global $CFG;
        $out = array();
        $paging = ClientService::readClientPaging();
        $total = ClientService::readClientTotal($paging);
        $paging->count = $total;
        $out['TITLE'] .= $CFG['LANG']['TITLE_SPLIT_SIGN'] . $CFG['LANG']['VIEW'];
        $out['CLIENTS'] = ClientService::readClientList($paging);
        $out['PAGING'] = $paging->toPaging();
        for ($i = 0; $i < count($out['PAGING']['pages']); $i ++) {
            $out['PAGING']['pages'][$i]['url'] = 'client.php?page=' . $out['PAGING']['pages'][$i]['p'] . "&pageSize=" . $out['PAGING']['pages'][$i]['pageSize'];
        }
        $this->template->push($out);
        $this->template->file('stat/stat.php');
    }
}
// PHP END
