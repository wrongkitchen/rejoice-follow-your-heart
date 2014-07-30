<?php
    class BackendUserController extends VD_CRUDController
    {
        public function init()
        {
            $this->model                            = BackendUser::model();  
            $this->pageTitle                        = 'Backend User Managment';
            $this->optionConfig['deletable']        = false;
            $this->optionConfig['exportable']       = false;
            $this->fieldConfig                      = array(
                                                        array('fieldName'=>'user_id','label'=>'User ID','insertable'=>false,'editable'=>false,'showInEdit'=>false),
                                                        array('fieldName'=>'username','label'=>'Username','editable'=>false,'editable'=>false),
                                                        array('fieldType'=>'password','fieldName'=>'password','label'=>'Password'),
                                                        array('fieldType'=>'select','fieldName'=>'user_type','label'=>'User Type','optionArray'=>array('normal'=>'Normal','super'=>'Super Admin')),
                                                        array('fieldType'=>'select','fieldName'=>'user_status','label'=>'User Status','optionArray'=>array('1'=>'Active','0'=>'Abord')),
                                                        array('fieldName'=>'last_login_time','label'=>'Last Login','insertable'=>false,'editable'=>false,'editable'=>false)
                                                    );
            parent::init();
        }
    }
?>
