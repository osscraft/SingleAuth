<?php
global $CFG;

// cover dev theme
$CFG['theme']['dev'] = 'default';
// lang
include dirname(__DIR__) . '/lang/dev.' . $CFG['language'] . '.php';

return array();

// PHP END