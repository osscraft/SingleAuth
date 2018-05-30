<?php

namespace Dcux\Core;

interface Ldaplizable extends Identification {
	/**
	 * @return array objectclass in ldap schema
	 */
	public function objectClass();
	/**
	 * @return boolean
	 */
	public function usePassword();
	public function setPassword($password);
	public function getPassword();
}
// PHP END