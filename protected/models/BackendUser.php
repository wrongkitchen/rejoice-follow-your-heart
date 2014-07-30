<?php
    class BackendUser extends CActiveRecord
    {      
        public static function model($className=__CLASS__)
        {
            return parent::model($className);
        }
        
        public function rules()
        {
            return array();
        }

        public function relations()
        {
            return array();
        }
        
        public function tableName()
        {
            return '{{backend_user}}';
        }
        
        public function primaryKey()
        {
            return 'user_id';
        }
        
        public function login($username,$password)
        {
            $errorMessage = array(
               'Username or Password Invalid',
               'Account Aborted'
            );
            $thisBackendUser = $this->model()->findByAttributes(array('username'=>$username));
            if($thisBackendUser === null){
                $returnErrorMessage       = $errorMessage[0];
            }elseif ($thisBackendUser->password !== $this->hashPasword($password)){
                $returnErrorMessage       = $errorMessage[0];
            }elseif ($thisBackendUser->user_status != '1'){
                $returnErrorMessage       = $errorMessage[1];
            }else{
                $returnErrorMessage       = false;
                $thisBackendUser->last_login_time   = new CDbExpression('NOW()');
                $thisBackendUser->update();
            }
            
            return array('backendUser'=>$thisBackendUser,'errorMessage'=>$returnErrorMessage);
        }
        
        public function hashPasword($password)
        {
            return md5(Yii::app()->params['md5Salt'].$password);
        }
    }
?>