<?php
global $CFG;

// cover portal theme
$CFG['theme']['portal'] = 'default';
// lang
include dirname(__DIR__) . '/lang/portal.' . $CFG['language'] . '.php';

return array();

// PHP END