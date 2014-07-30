<?php
    Class ErrorCodeHelper
    {
        public static function gerErrorMessage($type)
        {
            switch ($type){
                case 'register'     : return self::registerErrorMessage();break;
                case 'login'        : return self::loginErrorMessage();break;
                case 'submitDesign' : return self::submitDesignErrorMessage();break;
                case 'forgotPw'     : return self::forgotPwErrorMessage();break;
                case 'addAddress'   : return self::addAddressErrorMessage();break;
                default:
                    return array();
            }
        }
        
        public static function registerErrorMessage()
        {
            $errorArray = array();
            $errorArray['email_required']               = array ('en' => 'Email required' , 'tc' => '填上電郵地址' , 'sc' => '填上电邮地址');
            $errorArray['email_format']                 = array ('en' => 'Incorrect Email format' , 'tc' => '電郵地址格式不正確' , 'sc' => '电邮地址格式不正确');     
            $errorArray['email_exist']                  = array ('en' => 'Email already registered' , 'tc' => '電郵地址已用作註冊' , 'sc' => '电邮地址已用作注册');  
            $errorArray['password_required']            = array ('en' => 'Password required' , 'tc' => '填上密碼' , 'sc' => '填上密码');       
            $errorArray['password_length']              = array ('en' => 'Password cannot be less than 6 characters' , 'tc' => '密碼必須有六位或以上' , 'sc' => '密码必须有六位或以上');        
            $errorArray['password_not_match']           = array ('en' => 'Retype password not match' , 'tc' => '重填密碼不正確' , 'sc' => '重填密码不正确');  
            $errorArray['password_repeat_required']     = array ('en' => 'Retype your password' , 'tc' => '重填一次密碼' , 'sc' => '重填一次密码');        
            $errorArray['username_required']            = array ('en' => 'User name required' , 'tc' => '填上您的用戶名' , 'sc' => '');       
            $errorArray['username_length']              = array ('en' => 'User name cannot be less than 6 characters' , 'tc' => '用戶名必須有六位以上' , 'sc' => '用户名必须有六位以上');       
            $errorArray['birthday_required']            = array ('en' => 'birthday_required' , 'tc' => 'birthday_required' , 'sc' => 'birthday_required'); // make this optional
            $errorArray['gender_required']              = array ('en' => 'gender_required' , 'tc' => 'gender_required' , 'sc' => 'gender_required'); // make this optional
            $errorArray['accept_tnc_required']          = array ('en' => 'You must accept our terms and conditions to register' , 'tc' => '您必須同意本網站條款才可註冊' , 'sc' => '您必须同意本网站条款才可注册');
            return $errorArray;
        }
        
        public static function loginErrorMessage()
        {
            $errorArray = array();
            $errorArray['loginfail']               = array ('en' => 'No such account and password combination' , 'tc' => '帳戶與密碼不相符' , 'sc' => '帐户与密码不相符');
            return $errorArray;
        }
        
        public static function submitDesignErrorMessage()
        {
            $errorArray = array();
            $errorArray['source_type']                      = array ('en' => 'Source file format must be JEPG, PNG, PSD or AI' , 'tc' => '原始檔的格式必須為 JEPG, PNG, PSD 或 AI' , 'sc' => '原始档的格式必须为 JEPG, PNG, PSD 或 AI');
            $errorArray['source_not_exist']                 = array ('en' => 'Source file not exist' , 'tc' => '原始檔不存在' , 'sc' => '原始档不存在');
            $errorArray['source_not_uploaded']              = array ('en' => 'Source file not uploaded' , 'tc' => '原始檔還未上載' , 'sc' => '原始档还未上载');
            $errorArray['source_fail_upload']               = array ('en' => 'Source file uplaod failed' , 'tc' => '原始檔上載失敗' , 'sc' => '原始档上载失败');
            $errorArray['thumb_type']                       = array ('en' => 'Thumbnail file format must be JPEG or PNG' , 'tc' => '縮圖的格式必須為 JEPG 或 PNG' , 'sc' => '');
            $errorArray['thumb_not_exist']                  = array ('en' => 'Thumbnail not exist' , 'tc' => '縮圖不存在' , 'sc' => '缩图不存在');
            $errorArray['thumb_not_uploaded']               = array ('en' => 'Thumbnail not uplaoded' , 'tc' => '縮圖還未上載' , 'sc' => '缩图还未上载');
            $errorArray['thumb_fail_upload']                = array ('en' => 'Thumbnail upload failed' , 'tc' => '縮圖上載失敗' , 'sc' => '缩图上载失败');
            $errorArray['tags_required']                    = array ('en' => 'tags_required' , 'tc' => 'tags_required' , 'sc' => 'tags_required'); // this is optional
            $errorArray['campaign_id_required']             = array ('en' => 'Select a campaign' , 'tc' => '選擇參與活動' , 'sc' => '选择参与活动');
            $errorArray['name_required']                    = array ('en' => 'Name of design required' , 'tc' => '填上設計的大名' , 'sc' => '填上设计的大名');
            $errorArray['desc_required']                    = array ('en' => 'Description required' , 'tc' => '填上描述' , 'sc' => '填上描述');
            $errorArray['accept_tnc_required']              = array ('en' => 'You must accept our terms and conditions to submit design' , 'tc' => '您必須同意設計條款才可提交設計' , 'sc' => '您必须同意设计条款才可提交设计');
            return $errorArray;
        }
        
        public static function forgotPwErrorMessage()
        {
            $errorArray = array();
            $errorArray['email_required']               = array ('en' => 'Email required' , 'tc' => '填上電郵地址' , 'sc' => '填上电邮地址');
            $errorArray['email_format']                 = array ('en' => 'Incorrect Email format' , 'tc' => '電郵地址格式不正確' , 'sc' => '电邮地址格式不正确');    
            return $errorArray;
        }
        
        public static function addAddressErrorMessage()
        {
            $errorArray = array();
            $errorArray['recipient_name_required']      = array ('en' => 'Recipient name required' , 'tc' => '填上收件人/收款人姓名' , 'sc' => '填上收件人/收款人姓名');  
            $errorArray['province_required']            = array ('en' => 'Province required' , 'tc' => '選擇省市或地區' , 'sc' => '选择省市或地区');  
            $errorArray['address_required']             = array ('en' => 'Address required' , 'tc' => '填上地址' , 'sc' => '填上地址');  
            $errorArray['mobile_required']              = array ('en' => 'Phone number required' , 'tc' => '填上聯絡電話' , 'sc' => '填上联络电话');  
            $errorArray['mobile_format']                = array ('en' => 'Incorrect Phone number format' , 'tc' => '聯絡電話格式不正確' , 'sc' => '联络电话格式不正确');  
            $errorArray['password_not_match']           = array ('en' => 'Password not match' , 'tc' => '密碼不正確' , 'sc' => '密码不正确');  
            return $errorArray;
        }
    }
?>
