<?php
    class BackendController Extends BaseController    
    {
        public $loginUrl;
        public $baseUrl            = '';
        public $menu                = array();
        public $adminType           = 'guest';
        public $userRestrictAccess 	= array();
        public $actionId            = '';
        /**
        * @var Control Display or Show Menu, Default true
        */
        public $showMenu            = true;
        /*
         * Initiatize
         */
        public function init()
        {
            parent::init();
            $this->layout       = 'backend';
            $this->resourceUrl  = str_replace('/restricted','',$this->baseUrl).'/resource';
            $this->loginUrl     = $this->createUrl('Site/Login');
            if (Yii::app()->user->id){
                $this->adminType    = Yii::app()->user->userType;
            }else{
                $this->showMenu     = false;
            }
            $this->userRestrictAccess   = Yii::app()->params['restrictAccessRight'];
            $this->baseUrl              = Yii::app()->request->baseUrl;
        }
        
        /*
         * Override CControler::run()
         * Only authorize user can use the backend function
         */
        public function run($actionId)
        {
            if ( !$actionId ) {
                $actionId = 'index';
            }
            $actionId = strtolower($actionId);
            // Check Authorize
            if (!($actionId == 'login' || $actionId == 'logout')){
                $this->checkLogin($actionId);
                if (!$this->checkAccessRight(Yii::app()->controller->id,$actionId)){
                    $this->render('//site/message',array('message'=>'Access Denied.'));
                    die();
                }
            }
            $this->actionId = $actionId;
            $this->{'action'.ucwords($actionId)}();
        }
        
        /**
        * Check User is Login or not
        */
        public function checkLogin()
        {
            if (Yii::app()->user->isGuest){
                $this->redirect($this->loginUrl);
            }
        }

        /**
        * Check Access Right of User
        */
        public function checkAccessRight($controller,$actionId)
        {
            $thisUserType	= $this->adminType;
            $restrictAccess	= $this->userRestrictAccess[$thisUserType];
            if ($restrictAccess[0] == '*'){
                return false;
            }else{
                $actionRistrict = (array)$this->userRestrictAccess[$thisUserType][strtolower($controller)];
                return (!in_array(strtolower($actionId),$actionRistrict) && !in_array('*',$actionRistrict));
            }
        }
        
        /**
        * This is the action to handle external exceptions.
        */
        public function actionError()
        {
            $error = Yii::app()->errorHandler->error;
            if($error){
                if(Yii::app()->request->isAjaxRequest)
                    echo $error['message'];
                else
                    $this->render('//error/error', $error);
            }
        }


        
    }
?>
