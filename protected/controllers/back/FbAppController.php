<?php
    class FbAppController extends VD_CRUDController
    {
        public $detailNavName   = '';
        public $mainPrimaryKey  = '';
        public $pageData        = array();
        public function init()
        {
            $this->mainPrimaryKey                       = 'fbconfig_id';
            $this->templateArray['index']               = 'index';
            $this->templateArray['listViewContent']     = 'listViewContent';
            $this->templateArray['listViewPaging']      = 'listViewPaging';
            $this->initSetting('fbConfig');
        }
        
        public function initSetting($settingId)
        {
            switch($settingId){
                case 'fbConfig' :
                        $this->model                = FbConfig::model();  
                        $this->optionConfig         = array(
                                                    'insertable'   => false,
                                                    'editable'     => true,
                                                    'deletable'    => false,
                                                    'exportable'   => false,
                                                    'searchable'   => true
                                                );
                        $this->pageTitle            = 'Facebook App Managment';
                        $this->order                = ' `fbconfig_id` ASC ';
                        $this->fieldConfig          = FbAppController::getFbConfig();
                    break;
                case 'fbFeedConfig' : 
                        $this->model                = FbFeedConfig::model();  
                        $this->optionConfig         = array(
                                                    'insertable'   => true,
                                                    'editable'     => true,
                                                    'deletable'    => false,
                                                    'exportable'   => true,
                                                    'searchable'   => true
                                                );
                        $this->pageTitle            = 'Facebook Feed Managment';
                        $this->order                = ' `fbfeed_id` ASC ';
                        $this->detailNavName        = 'feed';
                        $this->condition            = " `fbconfig_id` = '".$_GET[$this->mainPrimaryKey]."'" ;
                        $this->fieldConfig          = FbAppController::getFbFeedConfig();
                    break;
                default:
                    die('Missing Setting ID');
                    break;
            }
            parent::init();
        }
        
        public static function getFbConfig()
        {
            $config = array(
                            array('fieldType'=>'primaryKey','fieldName'=>'fbconfig_id','label'=>'FB Config ID','showInEdit'=>true,'showInInsert'=>false),
                            array('fieldName'=>'fb_app_name','label'=>'App Name'),
//                            array('fieldType'=>'dateTime','fieldName'=>'start_time','label'=>'Start Time'),
//                            array('fieldType'=>'dateTime','fieldName'=>'end_time','label'=>'End Time'),
                            array('fieldName'=>'fb_app_id','label'=>'FB App ID'),
                            array('fieldName'=>'fb_app_secret','label'=>'FB App Secret'),
                            array('fieldName'=>'app_namespace','label'=>'FB App Namespace'),
                            array('fieldName'=>'app_scope','label'=>'FB App Scope'),
                            array('fieldName'=>'fanspage_id','label'=>'FB Fanspage ID'),
                            array('fieldName'=>'invite_msg','label'=>'Invite Message'),
                            array('fieldName'=>'invite_msg_mobile','label'=>'Invite Message Mobile'),
                            array('fieldType'=>'dateTime','fieldName'=>'last_update_time','label'=>'Last Update Time','showInInsert'=>false,'editable'=>false,'insertWithNow'=>true,'updateWithNow'=>true),
                        );
            return $config;
        }
        
        public static function getFbFeedConfig()
        {
            $config = array(
                            array('fieldType'=>'primaryKey','fieldName'=>'fbfeed_id','label'=>'FB Feed ID','showInEdit'=>true,'showInInsert'=>false),
                            array('fieldName'=>'fbconfig_id','label'=>'FB Config ID','regId'=>'fbConfigIdField'),
                            array('fieldName'=>'feed_name','label'=>'Feed Name'),
                            array('fieldName'=>'feed_caption','label'=>'Feed Caption'),
                            array('fieldName'=>'feed_description','label'=>'Feed Description'),
                            array('fieldName'=>'feed_action','label'=>'Feed Action'),
                            array('fieldName'=>'feed_link','label'=>'Feed Link'),
                            array('fieldType'=>'image','fieldName'=>'feed_picture','label'=>'Feed Picture','uploadPath' => '/resource/images/uploaded/fb','listViewfieldAttr' => array('style'=>'width:100px;'),'editViewfieldAttr' => array('style'=>'width:100px;')),
                            array('fieldType'=>'select','fieldName'=>'platform','label'=>'Status','optionArray' => array('desktop' => 'For Desktop','mobile' => 'For Mobile') ),
                        );
            return $config;
        }
        
        // Override Function       
        public function actionCreate()
        {
            $errors                         = array();
            $this->pageTitle               .= ' - Create Item';
            $this->render('detailView/create');
            // Error Handle
            if (sizeof($errors) > 0){
                $this->pageTitle                = 'Order Item Managment - Create Item Error';
                $this->render('detailView/error',array('errors'=>$errors));
            }
        }
        
        public function actionEdit()
        {
            $this->detailNavName    = 'edit';
            $primaryKeyValue        = (int)$_GET[$this->primaryKey];
            $thisRowData            = $this->model->findByPk($primaryKeyValue);
            if ($thisRowData){
                $this->render('detailView/edit',array('thisRowData'=>$thisRowData));
            }else{
                $errors[]    = 'No Item Found.';
            }
            // Error Handle
            if (sizeof($errors) > 0){
                $this->pageTitle                = 'Order Item Managment Error';
                $this->render('detailView/error',array('errors'=>$errors));
            }
        }

        // **********************************************************************************************************************************
        // Feed Related Code
        // **********************************************************************************************************************************    
        public function actionFeedIndex()
        {
            $this->initSetting('fbFeedConfig');
            $this->render('detailView/feed/index');
        }
        
        public function actionFeedCreate()
        {
            $this->initSetting('fbFeedConfig');
            $this->render('detailView/feed/create');
        }
        
        public function actionFeedLoadListContent()
        {
            $this->initSetting('fbFeedConfig');
            $this->templateArray['listViewContent']     = 'detailView/feed/listViewContent';
            $this->actionLoadListContent();
        }
        
        public function actionFeedDelete()
        {
            $this->initSetting('fbFeedConfig');
            $this->actionDelete();
        }
        
        public function actionFeedInsert()
        {
            $this->initSetting('fbFeedConfig');
            $this->actionInsert();
        }
        
        public function actionFeedEdit()
        {
            $this->initSetting('fbFeedConfig');
            $this->detailNavName    = 'edit';
            $primaryKeyValue        = (int)$_GET[$this->primaryKey];
            $thisRowData            = $this->model->findByPk($primaryKeyValue);
            if ($thisRowData){
                $this->render('detailView/feed/edit',array('thisRowData'=>$thisRowData));
            }else{
                $errors[]    = 'No Feed Found.';
            }
            // Error Handle
            if (sizeof($errors) > 0){
                $this->pageTitle                .= ' - Error';
                $this->render('detailView/error',array('errors'=>$errors));
            }
        }
        
        public function actionFeedUpdate()
        {
            $this->initSetting('fbFeedConfig');
            $this->actionUpdate();
        }

    }
?>
