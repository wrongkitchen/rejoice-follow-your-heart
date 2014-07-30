<?php
    class FrontendController Extends BaseController
    {
        public $lang;
        public $imgLangResUrl;
        public $pageCmsData;
        public $userAccount;
        
        public function init() 
        {
            parent::init();
            $this->initLanguage();
            $this->initUserAccount();
            $this->imgLangResUrl    = $this->resourceUrl.'/images/'.$this->lang->suffix;
            $this->pageCmsData      = array();
            $this->layout = 'frontend';
        }
        
        public function initLanguage()
        {
             $langSuffix = strtolower(trim($_GET['language']));
             if ($langSuffix == ''){
                 $langSuffix = $_SESSION['lang']['suffix'];
             }
             $thisLang   = Lang::model()->getLangBySuffix($langSuffix);
             if ($thisLang){ // For Language Suffix Exist
                 $this->lang                    = $thisLang;
                 $_SESSION['lang']['suffix']    = $this->lang->suffix;
             }else{ // For Language Suffix Not Exist
                 $thisLang = Lang::model()->getDefaultLang();
                 if ($thisLang){ 
                    $this->lang                    = $thisLang;
                    $_SESSION['lang']['suffix']    = $this->lang->suffix;
                    if ($langSuffix != ''){
                        // If Default Language Exist Redirect To Default Language
                        $this->redirect($this->createUrl('site/index',array('language'=>$thisLang->suffix)));
                    }
                 }else{
                     throw new CException('No language is set.');
                 }
             }
        }
        
        public function initUserAccount()
        {
            $accountArray               = array();
            $accountArray['logined']    = false;
            $accountId                  = (int)$_SESSION['user']['account_id'];
            if ($accountId){
                $thisAccount    = Account::model()->findByPk($accountId);
                if ($thisAccount){
                    $accountArray['logined']        = true;
                    $accountArray['accountData']    = $_SESSION['user'];
                }
            }
            $this->userAccount  = $accountArray;
        }
        
    }
?>
