<?php
return CMap::mergeArray(
    require(dirname(__FILE__).'/main.php'),
    array(
        // Put front-end settings there
        // (for example, url rules).
            'defaultController' => 'FB',
            'components'=>array(
                'urlManager'=>array(
                    'urlFormat'=>'path',
                    'showScriptName'=>false,
                    'rules'=>array(
                        '<language:[a-z]{2}>/<controller:\w+>/<action:\w+>/<id:\d+>'                    => '<controller>/<action>',
                        '<language:[a-z]{2}>/<controller:\w+>/<action:\w+>'                             => '<controller>/<action>',
                        '<language:[a-z]{2}>/<controller:\w+>/'                                         => '<controller>/index',
                        '<language:[a-z]{2}>'                                                           => 'FB/index', // Site/index
                        ''                                                                              => 'FB/index', // Site/index
//                        '<controller:\w+>/<action:\w+>/<id:\d+>'                                        => '<controller>/<action>',
//                        '<controller:\w+>/<action:\w+>'                                                 => '<controller>/<action>',
                    ),
			),
		),
    )
);
?>
