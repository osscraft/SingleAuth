<?php

namespace Lay\Advance\Core;

use Lay\Advance\Core\Model;
use Lay\Advance\Core\Identification;
use Lay\Advance\Core\Ldaplizable;
use Lay\Advance\DB\DataBase;

abstract class ModelLdap extends Model implements Ldaplizable {
    protected static $passwords = array();
    public function usePassword() {
        return true;
    }
    public function setPassword($password) {
        $ps = &ModelLdap::$passwords;
        $keyof = spl_object_hash($this);
        $ps[$keyof] = $password;
    }
    public function getPassword() {
        $ps = &ModelLdap::$passwords;
        $keyof = spl_object_hash($this);
        if(empty($ps[$keyof])) {
            return '';
        } else {
            return '{md5}' . base64_encode(pack('H*', md5($ps[$keyof])));;
        }
    }
    /**
     * schema is useless
     */
    public function schema() {
        return '';
    }
    public function db() {
        $ldap = DataBase::factory('ldap');
        $ldap->setModel($this);
        return $ldap;
    }
    // verify username and password
    public function verify($name, $pass, $fields = array()) {
        $db = $this->db();
        if($db instanceof Identification) {
            return $db->verify($name, $pass, $fields);
        } else {
            return false;
        }
    }
}