<?php

namespace Dcux\Admin\Action;

use Dcux\Admin\Kernel\AAction;
use Dcux\SSO\Manager\LDAPConfigManager;

class LdapConfig extends AAction
{
    public function onGet()
    {
        global $CFG;
        $_REQUEST['page'] = 1;
        $_REQUEST['pageSize'] = 10;
        $_REQUEST['key'] = empty($_REQUEST['key']) ? 'view' : $_REQUEST['key'];
        
        if (empty($_SESSION['token']) || empty($_SESSION['user'])) {
            $this->template->redirect('index.php');
        } else {
            $out['TITLE'] = $CFG['LANG']['LDAP_CONFIG_MANAGER'];
            $out['SESSION'] = $_SESSION;
            switch ($_REQUEST['key']) {
                case 'view':
                    $out['view'] = true;
                    $out['TITLE'] .= $CFG['LANG']['TITLE_SPLIT_SIGN'] . $CFG['LANG']['VIEW'];
                    $out['LDAPCONFIG'] = LDAPConfigManager::readLDAPConfig();
                    $this->template->file('ldap/view.php');
                    break;
                case 'tomodify':
                    $out['tomodify'] = true;
                    $out['TITLE'] .= $CFG['LANG']['TITLE_SPLIT_SIGN'] . $CFG['LANG']['MODIFY'];
                    $out['LDAPCONFIG'] = LDAPConfigManager::readLDAPConfig();
                    $this->template->file('ldap/edit.php');
                    break;
                case 'modify':
                    $out['modify'] = true;
                    $out['TITLE'] .= $CFG['LANG']['TITLE_SPLIT_SIGN'] . $CFG['LANG']['MODIFING'];
                    $out['SUCCESS'] = LDAPConfigManager::updateLDAPConfig();
                    $this->template->file('ldap/editok.php');
                    break;
                default:
                    $out['default'] = true;
                    $out['TITLE'] .= $CFG['LANG']['TITLE_SPLIT_SIGN'] . $CFG['LANG']['VIEW'];
                    $out['LDAPCONFIG'] = LDAPConfigManager::readLDAPConfig();
                    $this->template->file('ldap/view.php');
                    break;
            }
            
            $et = time() + microtime();
            $out['SIGN'] = 'ldapConfig';
            $out['LANG'] = $CFG['LANG'];
            $out['TIME'] = array(
                    'START_TIME' => $st,
                    'END_TIME' => $et,
                    'DIFF_TIME' => ($et - $st)
            );
            $out['RAND'] = rand(100000, 999999);
            $this->template->push($out);
        }
        
        return;
    }
    public function onPost()
    {
        $this->onGet();
    }
}
// PHP END
