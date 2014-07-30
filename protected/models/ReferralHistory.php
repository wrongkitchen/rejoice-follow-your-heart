<?php
    class ReferralHistory extends CActiveRecord
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
            return '{{referral_history}}';
        }
        
        public function primaryKey()
        {
            return array('from_form_id','to_form_id');
        }

    }
?>