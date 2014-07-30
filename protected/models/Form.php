<?php
    class Form extends CActiveRecord
    {
        public static function model($className=__CLASS__)
        {
            return parent::model($className);
        }
        
        public function rules()
        {
            
            return array(
				array('mc_ans1','required','message' => '請選擇mc_ans1'),
				array('mc_ans2','required','message' => '請選擇mc_ans2'),
				array('mc_ans3','required','message' => '請選擇mc_ans3'),
				array('mc_ans4','required','message' => '請選擇mc_ans4'),
				array('mc_ans5','required','message' => '請選擇mc_ans5'),
				array('mc_ans6','required','message' => '請選擇mc_ans6'),
				array('mc_ans7','required','message' => '請選擇mc_ans7'),
				array('mc_ans8','required','message' => '請選擇mc_ans8'),
				array('name','required','message' => '請填上姓名。'),
				array('name','match','pattern'=>'/^[a-zA-Z\s]+[a-zA-Z]$/','message' => '請以英文填寫姓名。'),
				array('mobile','required','message' => '請填上電話。'),
				array('mobile','match','pattern'=>'/^[0-9]{8}$/','message' => '請輸入香港手機號碼。'),
				array('email','required','message' => '請填上電郵地址。'),
				array('hkid','required','message' => '請填上身份證首4位數字。'),
				array('hkid','match','pattern'=>'/^[0-9]{4}$/','message' => '請輸入身份證首4位數字。'),
				array('email','email','message' => '電郵地址格式錯誤。'),
				array('tnc','required','message' => '請同意並接受條款及細則。')
             );
        }

        public function relations()
        {
            return array();
        }
        
        public function tableName()
        {
            return '{{form}}';
        }
        
        public function primaryKey()
        {
            return 'form_id';
        }

    }
?>