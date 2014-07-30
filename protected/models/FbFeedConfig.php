<?php
    class FbFeedConfig extends CActiveRecord
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
            return '{{fbfeed_config}}';
        }
        
        public function primaryKey()
        {
            return 'fbfeed_id';
        }

    }
?>