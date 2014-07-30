<?php
    class SiteController Extends BackendController
    {
        public function init()
        {
            parent::init();
        }

        /*
         * Index Page
         */
        public function actionIndex()
        {
            $this->render('index');
        }
        
        /*
         * Login Page
         */
        public function actionLogin()
        {
            if ($this->adminType == 'guest'){
                $this->showMenu = false;
                if (Yii::app()->request->isAjaxRequest && ($_POST['username'] || $_POST['password'])){
                    $identity   = new BackendUserIdentity($_POST['username'] ,$_POST['password']);
                    if($identity->authenticate()){
                        $duration   =  3600 * 2; // 2 hrs
                        Yii::app()->user->login($identity,$duration);
                        die(json_encode(array('status'=>true,'redirectUrl'=>$this->createUrl(Yii::app()->params['defaultLanding']))));
                    }else{
                        die(json_encode(array('status'=>false,'errorMessage'=>$identity->errorMessage)));
                    }
                }elseif ($_POST){
                    $errorMessage = 'Please Input Username And Password For Login.';
                    die(json_encode(array('status'=>false,'errorMessage'=>$errorMessage)));
                }
                $this->render('login',array('errorMessage' => $errorMessage));
            }else{
                $this->redirect($this->createUrl(Yii::app()->params['defaultLanding']));
            }
        }
        
        /**
        * Logs out the current user and redirect to homepage.
        */
        public function actionLogout()
        {
            Yii::app()->user->logout();
            $this->redirect($this->loginUrl);
        }
    }
?>
