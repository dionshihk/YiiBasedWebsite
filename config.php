<?php
YiiBase::setPathOfAlias('adminConfig', './adminConfig');

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'protected',
	'name'=>'Dion Shi\'s Work',
	'import'=>array(
		'application.models.*',
		'application.components.*',
        'adminConfig.*',
	),
	'modules'=>array(
        'api',
		'admin',
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'12345',
			'ipFilters'=>array('127.0.0.1','::1'),
		),
	),

	'components'=>array(
        'session' => array(
            'autoStart'=>true,
            'cookieMode'=>'allow',
            'cookieParams'=>array(
                'path' => '/',
            ),
        ),

        'user'=>array(
            'allowAutoLogin'=>true,
        ),
			
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),

		'db'=>array(
			'connectionString'=>'mysql:host=127.0.0.1;dbname=tourway',
			'emulatePrepare'=>true,
			'username'=>'root',
			'password'=>'12345',
			'charset'=>'utf8mb4',
		),
			
		'authManager'=>array(
            'class'=>'CDbAuthManager',
            'connectionID'=>'db',
		),
			
		'errorHandler'=>array(
			'errorAction'=>'site/error',
		),
			
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(),
		),
	),

	'params'=>array(
        'runningMode'=>1,
	),
);