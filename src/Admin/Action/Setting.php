<?php
namespace Dcux\Admin\Action;

use Lay\Advance\Core\Preloading;
use Lay\Advance\Core\Configuration;

use Dcux\Admin\Kernel\AAction;
use Dcux\Admin\Kernel\MenuPermission;
use Dcux\SSO\Service\SettingService;

class Setting extends MenuPermission {
    public function cmd() {
        return 'setting';
    }
    public function onCreate() {
        parent::onCreate();
        $t = $this->template->getTheme();
        if(!empty($t) && $t != 'default') {//different theme different php
            //直接跳至
            $this->template->redirect('/admin/setting/lists.php', array(), false);
        }
        if (empty($_SESSION['token']) || empty($_SESSION['user']) || $_SESSION['user']['isAdmin'] < 1) {
            $this->template->redirect('index.php', array(), false);
        }
    }
	public function onGet(){
		global $CFG;
		$out = array();
		$st =time() + microtime();
		$_REQUEST['key'] = empty($_REQUEST['key']) ? 'list' : $_REQUEST['key'];
        $out['TITLE'] = $CFG['LANG']['SETTING_MANAGER'];
        $out['SESSION'] = $_SESSION; 
		switch ($_REQUEST['key']) {
			case 'view':
				$this->doView();
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
        $out['SIGN'] = 'setting';
        $out['LANG'] = $CFG['LANG'];
        $out['TIME'] = array (
                'START_TIME' => $st,
                'END_TIME' => $et,
                'DIFF_TIME' => ($et - $st) 
        );
        $out['RAND'] = rand(100000, 999999);
        $this->template->push($out);
	}
	public function onPost(){
		$this->onGet();
	}

    protected function doView() {
        global $CFG;
        $out = array();
		$k=$_REQUEST['k'];
		$out['TITLE'] .= $CFG['LANG']['TITLE_SPLIT_SIGN'] . $CFG['LANG']['VIEW'];
		$out['SETTING'] = SettingService::read(array('k'=>$k));
		$this->template->file('setting/view.php');
        $this->template->push($out);
    }
    protected function doToCreate() {
        global $CFG;
        $out = array();
		$out['TITLE'] .= $CFG['LANG']['TITLE_SPLIT_SIGN'] . $CFG['LANG']['CREATE'];
        $this->template->file('setting/create.php');
        $this->template->push($out);
    }
    protected function doCreate() {
        global $CFG;
        $out = array();
		$out['TITLE'] .= $CFG['LANG']['TITLE_SPLIT_SIGN'] . $CFG['LANG']['CREATING'];
        $out['SUCCESS'] = SettingService::insert(array('k'=>$_REQUEST['k'],'v'=>$_REQUEST['v'],'info'=>$_REQUEST['info']));
        $this->template->file('setting/createok.php');
        $this->template->push($out);
    }
    protected function doToModify() {
        global $CFG;
        $out = array();
		$k = empty($_REQUEST['k']) ? false : $_REQUEST['k'];
        $out['TITLE'] .= $CFG['LANG']['TITLE_SPLIT_SIGN'] . $CFG['LANG']['MODIFY'];
        $out['SETTING'] = SettingService::read(array('k'=>$k));
		$out['themes'] = $CFG['themes'];
        $this->template->file('setting/edit.php');
        $this->template->push($out);
    }
    protected function doModify() {
        global $CFG;
        $out = array();
		$k = empty($_REQUEST['k']) ? false : $_REQUEST['k'];
        $out['TITLE'] .= $CFG['LANG']['TITLE_SPLIT_SIGN'] . $CFG['LANG']['MODIFING'];
		$setting=SettingService::read(array('k'=>$_REQUEST['k']));
        $out['SUCCESS'] = SettingService::update(array('k'=>$_REQUEST['k'],'v'=>$_REQUEST['v'],'info'=>$_REQUEST['info']));
        $this->template->file('setting/editok.php');
        $this->template->push($out);
    }
    protected function doToDelete() {
        global $CFG;
        $out = array();
		$k = empty($_REQUEST['k']) ? false : $_REQUEST['k'];
        $out['TITLE'] .= $CFG['LANG']['TITLE_SPLIT_SIGN'] . $CFG['LANG']['DELETE'];
        $out['SETTING'] = SettingService::read(array('k'=>$k));
        $this->template->file('setting/remove.php');
        $this->template->push($out);
    }
    protected function doDelete() {
        global $CFG;
        $out = array();
		$k = empty($_REQUEST['k']) ? false : $_REQUEST['k'];
        $out['TITLE'] .= $CFG['LANG']['TITLE_SPLIT_SIGN'] . $CFG['LANG']['DELETING'];
        $out['SUCCESS'] = SettingService::delete(array('k'=>$k));
        $this->template->file('setting/removeok.php');
        $this->template->push($out);
    }
    protected function doList() {
        global $CFG;
        $out = array();
        $paging = SettingService::readSettingPaging();
        $total = SettingService::readSettingTotal($paging);
        $paging->count = $total;
        $out['TITLE'] .= $CFG['LANG']['TITLE_SPLIT_SIGN'] .$CFG['LANG']['LIST'];
        $settings = SettingService::readSettingList($paging);
		$sets = array();
		foreach($settings as $i=>$setting){
			if(strpos($setting['k'],'theme')===false){
				$sets[] = $setting;
			}
		}
		$out['SETTING'] = $sets;
        $out['PAGING'] = $paging->toPaging();
		for($i = 0; $i < count($out['PAGING']['pages']); $i ++) {
            $out['PAGING']['pages'][$i]['url'] = 'setting.php?page=' . $out['PAGING']['pages'][$i]['p'] . "&pageSize=" . $out['PAGING']['pages'][$i]['pageSize'];
        }
        $this->template->file('setting/list.php');
        $this->template->push($out);
    }
}
?>