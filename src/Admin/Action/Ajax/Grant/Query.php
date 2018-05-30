<?php

namespace Dcux\Admin\Action\Ajax\Grant;

use Dcux\Admin\Kernel\AjaxPermission;
use Dcux\Admin\Action\UserGrant;
use Dcux\SSO\Service\UserGrantService;

class Query extends AjaxPermission {
	protected $userGrantService;
	public function onCreate() {
        parent::onCreate();
		$this->userGrantService = UserGrantService::getInstance();
	}
    public function onGet() {
		global $CFG;
		//$this->template->push('data', $CFG['menu']);
		$data = array();
		foreach($CFG['menu'] as $menu){
			$data[] = $this->grants($menu);
		}
		$this->template->push('data', $data);
    }
    public function onPost() {
    	$this->onGet();
    }
	
	public function grants($menu = array()){
		$data = array();
		if(empty($menu['isSuper'])){
			$data['id'] = $menu['id'];
			$data['name'] = $menu['name'];
			if(!empty($menu['children'])){
				foreach($menu['children'] as $child){
					if(empty($child['isSuper'])){
						$data[] = $this->grants($child);
					}
				}
			}
		}
		return empty($data)?null:$data;
	}
}
// PHP END