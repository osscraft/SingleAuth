<?php

namespace Dcux\Admin\Action;

use Dcux\Admin\Kernel\Permission;
use Dcux\Admin\Kernel\MenuPermission;
use Dcux\Admin\Kernel\AAction;
use Dcux\SSO\Service\ClientService;

class Client extends MenuPermission
{
    public function cmd()
    {
        return 'client';
    }
    public function onCreate()
    {
        parent::onCreate();
        $t = $this->template->getTheme();
        if (!empty($t) && $t != 'default') {//different theme different php
            //直接跳至
            $this->template->redirect('/admin/client/lists.php', array(), false);
        }
    }
    public function onGet()
    {
        global $CFG;
        $st = time() + microtime();
        $out = array();
        $_REQUEST['key'] = empty($_REQUEST['key']) ? 'list' : $_REQUEST['key'];
    
        $out['TITLE'] = $CFG['LANG']['CLIENT_MANAGER'];
        $out['SESSION'] = $_SESSION;
        
        switch ($_REQUEST['key']) {
            case 'view':
                $this->doView();
                break;
            case 'stat':
                $this->doStat();
                break;
            case 'search':
                $this->doSearch();
                break;
            case 'tocreate':
                $this->doToCreate();
                break;
            case 'create':
                $this->doCreate();
                break;
            case 'tomodify':
                $this->doToModify();
                break;
            case 'modify':
                $this->doModify();
                break;
            case 'todelete':
                $this->doToDelete();
                break;
            case 'delete':
                $this->doDelete();
                break;
            case 'list':
            default:
                $this->doList();
                break;
        }
        
        
        $et = time() + microtime();
        $out['SIGN'] = 'client';
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

    protected function doView()
    {
        global $CFG;
        $out = array();
        $id = empty($_REQUEST['id']) ? false : $_REQUEST['id'];
        $out['TITLE'] .= $CFG['LANG']['TITLE_SPLIT_SIGN'] . $CFG['LANG']['VIEW'];
        $out['CLIENT'] = ClientService::readClient($id);
        $this->template->file('client/view.php');
        $this->template->push($out);
    }
    protected function doStat()
    {
        global $CFG;
        $out = array();
        $id = empty($_REQUEST['id']) ? false : $_REQUEST['id'];
        $out['TITLE'] .= $CFG['LANG']['TITLE_SPLIT_SIGN'] . $CFG['LANG']['VIEW'];
        $out['CLIENT'] = ClientService::readClient($id);
        $this->template->file('client/stat.php');
        $this->template->push($out);
    }
    protected function doSearch()
    {
        global $CFG;
        $out = array();
        $search = empty($_REQUEST['clientId']) ? false : $_REQUEST['clientId'];
        $out['TITLE'] .= $CFG['LANG']['TITLE_SPLIT_SIGN'] . $CFG['LANG']['VIEW'];
        // $out['CLIENT'] = ClientService::readClient($_REQUEST['clientId']);
        $out['CLIENTS'] = ClientService::readClientByWord($search);
        $this->template->file('client/list.php');
        $this->template->push($out);
    }
    protected function doToCreate()
    {
        global $CFG;
        $out = array();
        $out['TITLE'] .= $CFG['LANG']['TITLE_SPLIT_SIGN'] . $CFG['LANG']['CREATE'];
        $this->template->file('client/create.php');
        $this->template->push($out);
    }
    protected function doCreate()
    {
        global $CFG;
        $out = array();
        $out['TITLE'] .= $CFG['LANG']['TITLE_SPLIT_SIGN'] . $CFG['LANG']['CREATING'];
        $out['SUCCESS'] = ClientService::createClient($_REQUEST);
        $this->template->file('client/createok.php');
        $this->template->push($out);
    }
    protected function doToModify()
    {
        global $CFG;
        $out = array();
        $id = empty($_REQUEST['id']) ? false : $_REQUEST['id'];
        $out['TITLE'] .= $CFG['LANG']['TITLE_SPLIT_SIGN'] . $CFG['LANG']['MODIFY'];
        $out['CLIENT'] = ClientService::readClient($id);
        $this->template->file('client/edit.php');
        $this->template->push($out);
    }
    protected function doModify()
    {
        global $CFG;
        $out = array();
        $id = empty($_REQUEST['id']) ? false : $_REQUEST['id'];
        $out['TITLE'] .= $CFG['LANG']['TITLE_SPLIT_SIGN'] . $CFG['LANG']['MODIFING'];
        $out['SUCCESS'] = ClientService::updateClient($id, $_REQUEST);
        $this->template->file('client/editok.php');
        $this->template->push($out);
    }
    protected function doToDelete()
    {
        global $CFG;
        $out = array();
        $id = empty($_REQUEST['id']) ? false : $_REQUEST['id'];
        $out['TITLE'] .= $CFG['LANG']['TITLE_SPLIT_SIGN'] . $CFG['LANG']['DELETE'];
        $out['CLIENT'] = ClientService::readClient($id);
        $this->template->file('client/remove.php');
        $this->template->push($out);
    }
    protected function doDelete()
    {
        global $CFG;
        $out = array();
        $id = empty($_REQUEST['id']) ? false : $_REQUEST['id'];
        $out['TITLE'] .= $CFG['LANG']['TITLE_SPLIT_SIGN'] . $CFG['LANG']['DELETING'];
        $out['SUCCESS'] = ClientService::deleteClient($id);
        $this->template->file('client/removeok.php');
        $this->template->push($out);
    }
    protected function doList()
    {
        global $CFG;
        $out = array();
        $paging = ClientService::readClientPaging();
        $total = ClientService::readClientTotal($paging);
        $paging->count = $total;
        $out['TITLE'] .= $CFG['LANG']['TITLE_SPLIT_SIGN'] . $CFG['LANG']['LIST'];
        $out['CLIENTS'] = ClientService::readClientList($paging);
        $out['PAGING'] = $paging->toPaging();
        for ($i = 0; $i < count($out['PAGING']['pages']); $i ++) {
            $out['PAGING']['pages'][$i]['url'] = 'client.php?page=' . $out['PAGING']['pages'][$i]['p'] . "&pageSize=" . $out['PAGING']['pages'][$i]['pageSize'];
        }
        $this->template->file('client/list.php');
        $this->template->push($out);
    }
}
// PHP END
