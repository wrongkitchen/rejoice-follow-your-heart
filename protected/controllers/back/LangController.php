<?php
    class LangController extends VD_CRUDController
    {
        public function init()
        {
            $this->model                = Lang::model();  
            $this->optionConfig         = array(
                                            'insertable'   => true,
                                            'editable'     => true,
                                            'deletable'    => false,
                                            'exportable'   => true,
                                            'searchable'   => true
                                        );
            $this->pageTitle            = 'Language Management';
            $this->fieldConfig          = array(
                                            array('fieldType'=>'primaryKey','fieldName'=>'lang_id','label'=>'Lang ID','showInEdit'=>true),
                                            'name',
                                            'suffix',
                                            array('fieldType'=>'select','fieldName'=>'default','label'=>'Default','optionArray' => array('0' => 'No','1' => 'Yes') ),                          
                                            array('fieldType'=>'select','fieldName'=>'status','label'=>'Status','optionArray' => array('0' => 'In Active','1' => 'Active') ),                          
                                        );
            parent::init();
        }
        
    }
?>
