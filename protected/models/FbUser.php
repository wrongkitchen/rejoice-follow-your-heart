<?php
    class FbUser extends CActiveRecord
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
            return '{{fb_user}}';
        }
        
        public function primaryKey()
        {
            return 'uid';
        }

    }
?>