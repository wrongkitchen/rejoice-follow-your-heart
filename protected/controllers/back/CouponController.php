<?php
    class CouponController extends VD_CRUDController
    {
        public function init()
        {
            $this->model                = Coupon::model();  
            $this->optionConfig         = array(
                                            'insertable'   => true,
                                            'editable'     => true,
                                            'deletable'    => true,
                                            'exportable'   => true,
                                            'searchable'   => true
                                        );
            $this->pageTitle            = 'Form Management';
            $this->fieldConfig          = array(
                                            array('fieldType'=>'primaryKey','fieldName'=>'coupon_id','label'=>'Coupon ID','showInEdit'=>true),
											'uid',
											'fb_name',
											'coupon_no',                     
                                            array('fieldType'=>'dateTime','fieldName'=>'create_time','label'=>'Create Time'),                   
                                        );
            parent::init();
        }
        
    }
?>
