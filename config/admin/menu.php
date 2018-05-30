<?php
global $CFG;

$CFG['menu'] = array(
	array(
        'id' => 'index',
        'name' => $CFG['LANG']['MAINPAGE'],
        'icon' => 'fa fa-home',
        'alias' => array(),
        'implication' => array(),
        'specific' => 0,
        'active' => '',
		'isSuper' =>false,
        'href' => '/admin/',
        'disable' => false,
        'children' => array(
        )
    ),
	array(
        'id' => 'grant',
        'name' => $CFG['LANG']['ADMIN_GRANT_MANAGE'],
        'icon' => 'fa fa-key',
        'alias' => array(),
        'implication' => array(),
        'specific' => 1,
        'active' => '',
		'isSuper' =>true,
        'href' => '/admin/grant/lists.php',
        'disable' => false,
        'children' => array(
            array(
                'id' => 'grant.lists',
                'name' => $CFG['LANG']['ADMIN_GRANT_CONFIGURE'],
                'alias' => array('grant'),
                'implication' => array(),
                'specific' => 0,
                'active' => '',
				'isSuper' =>true,
                'href' => '/admin/grant/lists.php',
                'disable' => false,
                'children' => array()
            )
        )
    ),
    array(
        'id' => 'client',//ID,即CMD
        'name' => $CFG['LANG']['ADMIN_APP_MANAGE'],
        'icon' => 'fa fa-th',//fa-send
        'alias' => array(),//别名ID
        'implication' => array(),//隐式ID
        'specific' => 0,//是否特殊
        'active' => '',
		'isSuper' =>false,
        'href' => '/admin/client/lists.php',
        'disable' => false,
        'children' => array(
            array(
                'id' => 'client.lists',
                'name' => $CFG['LANG']['ADMIN_APP_LIST'],
                'alias' => array('client'),
                'implication' => array(),
                'specific' => 0,
                'active' => '',
				'isSuper' =>false,
                'href' => '/admin/client/lists.php',
                'disable' => false,
                'children' => array()
            )
        )
    ),
    array(
        'id' => 'user',
        'name' => $CFG['LANG']['ADMIN_USER_MANAGE'],
        'icon' => 'fa fa-users',
        'alias' => array(),
        'implication' => array(),
        'specific' => 0,
        'active' => '',
		'isSuper' =>true,
        'href' => '/admin/user/lists.php',
        'disable' => false,
        'children' => array(
            array(
                'id' => 'user.lists',
                'name' => $CFG['LANG']['ADMIN_USER_LIST'],
                'alias' => array('user'),
                'implication' => array(),
                'specific' => 0,
                'active' => '',
				'isSuper' =>true,
                'href' => '/admin/user/lists.php',
                'disable' => false,
                'children' => array()
            ),
            array(
                'id' => 'token',
                'name' => $CFG['LANG']['ADMIN_USER_GRANT_LIST'],
                'alias' => array('user.token'),
                'implication' => array(),
                'specific' => 0,
                'active' => '',
				'isSuper' =>true,
                'href' => '/admin/user/token.php',
                'disable' => false,
                'children' => array()
            )

        )
    ),
    array(
        'id' => 'statistics',
        'name' => $CFG['LANG']['ADMIN_STATISTICS_GRAPH'],
        'icon' => 'fa fa-pie-chart',
        'alias' => array(),
        'implication' => array(),
        'specific' => 0,
        'active' => '',
		'isSuper' =>false,
        'href' => '/admin/stat/summary.php',
        'disable' => false,
        'children' => array(
            array(
                'id' => 'statistics.summary',
                'name' => $CFG['LANG']['ADMIN_STATISTICS_SUMMARY'],
                'alias' => array('statistics'),
                'implication' => array(),
                'specific' => 0,
                'active' => '',
				'isSuper' =>false,
                'href' => '/admin/stat/summary.php',
                'disable' => false,
                'children' => array()
            ),
            array(
                'id' => 'statistics.client',
                'name' => $CFG['LANG']['ADMIN_STATISTICS_VISIT'],
                'alias' => array(),
                'implication' => array(),
                'specific' => 0,
                'active' => '',
				'isSuper' =>false,
                'href' => '/admin/stat/client.php',
                'disable' => false,
                'children' => array()
            ),
            array(
                'id' => 'statistics.top',
                'name' => $CFG['LANG']['ADMIN_STATISTICS_RANK'],
                'alias' => array(),
                'implication' => array(),
                'specific' => 0,
                'active' => '',
				'isSuper' =>false,
                'href' => '/admin/stat/top-client.php',
                'disable' => false,
                'children' => array(
                    array(
                        'id' => 'statistics.top.client',
                        'name' => $CFG['LANG']['ADMIN_STATISTICS_CLIENT_RANK'],
                        'alias' => array(),
                        'implication' => array('statistics.top'),
                        'specific' => 0,
                        'active' => '',
						'isSuper' =>false,
                        'href' => '/admin/stat/top-client.php',
                        'disable' => false,
                        'children' => array()
                    ),
                    array(
                        'id' => 'statistics.top.user',
                        'name' => $CFG['LANG']['ADMIN_STATISTICS_USER_RANK'],
                        'alias' => array(),
                        'implication' => array(),
                        'specific' => 0,
                        'active' => '',
						'isSuper' =>false,
                        'href' => '/admin/stat/top-user.php',
                        'disable' => false,
                        'children' => array()
                    )
                )
            ),
            array(
                'id' => 'statistics.scatter',
                'name' => $CFG['LANG']['ADMIN_STATISTICS_SCATTERGRAM'],
                'alias' => array(),
                'implication' => array(),
                'specific' => 0,
                'active' => '',
				'isSuper' =>false,
                'href' => '/admin/stat/scatter.php',
                'disable' => false,
                'children' => array()
            ),
            array(
                'id' => 'statistics.rawlog',
                'name' => $CFG['LANG']['ADMIN_STATISTICS_RAW_LOG'],
                'alias' => array(),
                'implication' => array(),
                'specific' => 0,
                'active' => '',
				'isSuper' =>false,
                'href' => '/admin/stat/rawlog.php',
                'disable' => false,
                'children' => array()
            )

        )
    ),
    array(
        'id' => 'setting',
        'name' => $CFG['LANG']['ADMIN_SETTING_MANAGE'],
        'icon' => 'fa fa-cog',
        'alias' => array(),
        'implication' => array(),
        'specific' => 0,
        'active' => '',
		'isSuper' =>true,
        'href' => '/admin/setting/lists.php',
        'disable' => false,
        'children' => array(
            array(
                'id' => 'setting.lists',
                'name' => $CFG['LANG']['ADMIN_SETTING_LIST'],
                'alias' => array('setting'),
                'implication' => array(),
                'specific' => 0,
                'active' => '',
				'isSuper' =>false,
                'href' => '/admin/setting/lists.php',
                'disable' => false,
                'children' => array()
            )
        )
    ),
    array(
        'id' => 'skin',
        'name' => $CFG['LANG']['ADMIN_THEME_MANAGE'],
        'icon' => 'fa fa-tachometer',
        'alias' => array(),
        'implication' => array(),
        'specific' => 0,
        'active' => '',
		'isSuper' =>true,
        'href' => '/admin/skin/lists.php',
        'disable' => false,
        'children' => array(
            array(
                'id' => 'skin.lists',
                'name' => $CFG['LANG']['ADMIN_THEME_LIST'],
                'alias' => array('skin'),
                'implication' => array(),
                'specific' => 0,
                'active' => '',
				'isSuper' =>false,
                'href' => '/admin/skin/lists.php',
                'disable' => false,
                'children' => array()
            )
        )
    )
);

return $CFG;
// PHP END