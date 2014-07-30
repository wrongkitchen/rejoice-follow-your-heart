<?php
return CMap::mergeArray(
    require(dirname(__FILE__).'/main.php'),
    array(
        // Put front-end settings there
        // (for example, url rules).
		'components'=>array(
			'urlManager'=>array(
				'urlFormat'=>'path',
				'showScriptName'=>false,
				'rules'=>array(
					'api.php'=>'site/index',
					'api/post/<id:\d+>/<title:.*?>'=>'post/view',
					'api/posts/<tag:.*?>'=>'post/index',
					'api/<controller:\w+>/<id:\d+>'=>'<controller>/view',
					'api/<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
					'api/<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				),
			),
		),
    )
);
?>