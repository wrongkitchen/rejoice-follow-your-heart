<?php
    class Lang extends CActiveRecord
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
            return '{{lang}}';
        }
        
        public function primaryKey()
        {
            return 'lang_id';
        }
        
        public function getDefaultLang()
        {
            $defaultLang    = Lang::model()->find(array('condition'=>' `default` = 1','order' => '`lang_id` ASC'));
            if ($defaultLang){
                return $defaultLang;
            }else{
                return false;
            }
        }
        
        public function getLangBySuffix($suffix)
        {
            $thisLang   = Lang::model()->find(' `suffix` = :suffix AND `status` = 1 ORDER BY `lang_id` ASC', array(':suffix' => $suffix));
            if ($thisLang){
                return $thisLang;
            }else{
                return false;
            }
        }
    }
?>