<?php
    class BackendUserIdentity extends CUserIdentity
    {
        private $_id;
        
        /*
         * Authenticate Process
         */
        public function authenticate()
        {
            $result = BackendUser::model()->login($this->username,$this->password);
            if ($result['errorMessage'] === false){
                $thisBackendUser    = $result['backendUser'];
                $this->_id          = $thisBackendUser->user_id;
                $this->setState('userType',$thisBackendUser->user_type);
                $this->errorMessage = false;
            }else{
                $this->errorMessage = $result['errorMessage'];
            }
            return !$this->errorMessage;
        }

        public function getId()
        {
            return $this->_id;
        }
    }
?>