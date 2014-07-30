<?php
    return CMap::mergeArray(
        require(dirname(__FILE__).'/main.php'),
        array(
            'components'=>array(
                'urlManager'=>array(
                    'urlFormat'=>'path',
                    'showScriptName'=>false,
                    'rules'=>array(
                        '<controller:\w+>/<action:\w+>' =>  '<controller>/<action>',
                    ),
                ),
            ),
            'params'    => array(
                'defaultLanding'        => 'Site/Index',
                'restrictAccessRight'   => array(
                                            'super' 	=> array(),
                                            'developer' => array(),
                                            'normal'	=> array('admin'=>array('*'))
                                        ),
                'menuConfig'            => array(
                                           /*
                                                array('label' => '', 
                                                    'controller'=>'',
                                                    'action'=>'', 
                                                    'visible' => array('*') 
                                                            // array('*') mean all, 
                                                            // array('developer') mean only allow super and developer to see, 
                                                            // array('normal','developer') mean only allow super and developer and normal to see.
                                                ),
                                            */
                                            array('label' => 'FB Config', 'controller'=>'FbApp', 'action'=>'Index','visible' => array('*')),                                                                                                                                             
                                            array('label' => 'Coupon', 'controller'=>'Coupon', 'action'=>'Index','visible' => array('*')),                                                                                                                                                
                                            array('label' => 'Setting', 'controller'=>'', 'action'=>'','visible' => array('*'),
                                                'child' => array(
//                                                    array('label' => 'Lang', 'controller'=>'Lang', 'action'=>'Index','visible' => array('*')),
                                                    array('label' => 'Backend User', 'controller'=>'BackendUser', 'action'=>'Index','visible' => array('super','developer')),
                                                )
                                            ),
                                        ),
            )
        )
    );
?>
