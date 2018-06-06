<?php

namespace Dcux\SSO\Kernel;

interface Authorizable
{
    public function getUser($uid, $scope = array());
    public function verifyResourceOwner($uid, $password, $scope = array());
}
