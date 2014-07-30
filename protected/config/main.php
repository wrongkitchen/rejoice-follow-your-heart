<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Lifely',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.components.VD_CRUD.*',
		'application.components.Facebook.*',
		'application.components.Helper.*',
	),

	// application components
	'components'=>array(
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
        ),
        'Smtpmail'=>array(
            'class'=>'application.extensions.smtpmail.PHPMailer',
            'Host'=>"",
            'Username'=>'',
            'Password'=>'',
            'Mailer'=>'smtp',
            'Port'=>587, //25 is normal
            'SMTPAuth'=>true,
        ),
		'db'=>array(
			'connectionString'  => 'mysql:host=localhost;dbname=sekgamdong_apps',
			'emulatePrepare'    => true,
			'username'          => 'sekgamdong_apps',
			'password'          => 'wfn01FW8',
			'charset'           => 'utf8',
			'tablePrefix'       => 'rejoice_youtube__',
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),
	'behaviors'=>array(
		'runEnd'=>array(
			'class'=>'application.components.WebApplicationEndBehavior',
		),
	),
	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__).'/params.php'),
);