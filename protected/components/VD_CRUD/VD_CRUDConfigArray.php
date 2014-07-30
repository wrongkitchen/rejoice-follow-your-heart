<?php
    Class VD_CRUDConfigArray
    {
        private static function getDefaultArray()
        {
            $configArray    =  array(
                                /**
                                 *  Basic Setting
                                 */
                                'fieldType'             => 'text',              // [text,textarea,image,file,select,password,primaryKey,dateTime]
                                'fieldName'             => '',                  // Field name in database
                                'label'                 => '',                  // Label of the Field 
                                'defaultValue'          => '',                  // Optional
                                'dataType'              => '',                  // Optional, ['','integer']
                
                                /**
                                 *  Select Type Setting
                                 */       
                                'optionFromModel'       => array(                   // Conitional,if fieldType = select
                                                            'key'       => '',      // Reference Key, fieldName (Default Primary Key) ,e.g form_id, uid ....
                                                            'model'     => '',      // e.g. SubmitForm::model()
                                                            'fieldName' => '',      // e.g. name
                                                            'criteria'  => array(), // Array / CDbCriteria 
                                                        ),          
                                'optionArray'           => array(),             // Conitional,if fieldType = select
                                                                                // e.g. array('0'=>'Yes','1'=>'No')
                
                                /**
                                 *  Image OR File Setting
                                 */
                                'uploadPath'            => '/resource/images/uploaded',   // Must,if fieldType = Image / File 
                                'imageOption'           => array(),             // e.g. array(
                                                                                //          'width'     => '150',
                                                                                //          'height'    => '150',
                                                                                //          'option'    => 'auto'   // auto,crop,exact,portrait,landscape
                                                                                //      )
                                'allowedExtension'      => array(),             // e.g. array('jpg','jpeg','gif','png','pdf')
                                
                                /**
                                 *  Date Time Setting
                                 */
                                'insertWithNow'         => false,                // Insert with current time
                                'updateWithNow'         => false,                // Update with current time
                
                                /**
                                 * Render Function Setting 
                                 */
                                'renderFunction'       => '',                   // Custom render function name, must be a static function, e.g. 'SubmitForm::renderDateTime'
                                                                                // Three params will pass in, 
                                                                                // $renderMode ['create','edit','list','export','search'], 
                                                                                // $fieldConfig = config array
                                                                                // $rowData     = (object)current active record 
                                                                                // Example:         
                                                                                // public static function renderDateTime($renderMode,$fieldConfig,$rowData)
                                                                                // {
                                                                                //     $html = '';
                                                                                //     $fieldValue  = $rowData->{$fieldConfig['fieldName']};
                                                                                //     if ($renderMode == 'list'){
                                                                                //         $html = date('YmdHis',strtotime($fieldValue));
                                                                                //     }else{
                                                                                //         $html = date('YmdHis',strtotime($fieldValue));
                                                                                //     }
                                                                                //     return $html;
                                                                                // }
                                'functionParams'       => array(),              // array of parameters
                
                                /**
                                 *  List View Control
                                 */
                                'showInList'            => true,                // True or False, to Control Show or Not
                                'searchable'            => true,                //
                                'sortable'              => true,                //
                                'listViewfieldAttr'     => array(),             // Optional, e.g array('class'=>'ckeditor','style'=>'margin-top:100px');
                
                                /**
                                 *  Edit View Control
                                 */
                                'showInEdit'            => true,                // True or False, to Control show field or not
                                'editable'              => true,                // True or False, to Control show input field or text
                                'editViewfieldAttr'     => array('class' => 'form-control'),             // Optional, e.g array('class'=>'ckeditor','style'=>'margin-top:100px');
                
                                /**
                                 *  Create View Control
                                 */
                                'showInInsert'          => true,                // True or False, to Control show field or not
                                'insertable'            => true,                // True or False, to Control show field or not   
                                'createViewfieldAttr'   => array('class' => 'form-control'),             // Optional, e.g array('class'=>'ckeditor','style'=>'margin-top:100px');
                                /**
                                 * Search View Control
                                 */
                                'searchViewfieldAttr'   => array(),

                                /**
                                 *  Export View Control
                                 */
                                'showInExport'          => true
                            );
            return $configArray;
        }
        
        public static function create($config)
        {
            $defaultArray = self::getDefaultArray();
            if (!is_array($config)){
                $newConfig = array();
                $newConfig['fieldName'] = $config;
                $newConfig['label']     = ucwords(str_replace('_',' ',$config));
                $config                 = $newConfig;
            }else{

                switch ($config['fieldType']){
                    case 'primaryKey':
                            $primaryKeyTypeConfing  = array('insertable'=>false,'editable'=>false,'showInEdit'=>false);
                            $defaultArray           = array_merge($defaultArray,$primaryKeyTypeConfing);
                        break;
                    case 'textarea':
                            $textareaTypeConfig     = array('editViewfieldAttr'=>array('class'=>'ckeditor'),'createViewfieldAttr'=>array('class'=>'ckeditor'));
                            $defaultArray           = array_merge($defaultArray,$textareaTypeConfig);
                        break;
                    case 'password':
                            $passwordTypeConfig     = array('showInList'=>false);
                            $defaultArray           = array_merge($defaultArray,$passwordTypeConfig);
                        break;
                    case 'image':
                            $imageTypeConfig        = array('allowedExtension'=>array('jpg','jpeg','gif','png'),'imageOption'=>array('width'=>'','height'=>'','option'=>'auto'),'searchable'=>false);
                            $defaultArray           = array_merge($defaultArray,$imageTypeConfig);
                        break;
                    case 'file':
                            $fileTypeConfig        = array('searchable'=>false);
                            $defaultArray           = array_merge($defaultArray,$fileTypeConfig);
                        break;
                    case 'dateTime':
                            $dateTimeTypeConfig     = array('editViewfieldAttr'=>array('class'=>'dateTimePicker form-control','readonly'=>'readonly'),'createViewfieldAttr'=>array('class'=>'dateTimePicker form-control','readonly'=>'readonly'));
                            $defaultArray           = array_merge($defaultArray,$dateTimeTypeConfig);
                        break;
                }
            }
            if ($config['label'] == ''){
                $config['label'] = ucwords(str_replace('_',' ',$config['fieldName']));
            }
            return array_merge($defaultArray,$config);
        }
    }
?>
