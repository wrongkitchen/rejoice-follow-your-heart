<?php
    class Coupon extends CActiveRecord
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
            return '{{coupon}}';
        }
        
        public function primaryKey()
        {
            return 'coupon_id';
        }

    }
?>