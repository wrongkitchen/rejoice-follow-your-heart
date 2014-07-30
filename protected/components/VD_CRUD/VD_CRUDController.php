<?php
    class VD_CRUDController Extends BackendController
    {
        public $model;
        public $primaryKey;
        public $pageSize            = 100;
        public $pageTitle           = '';
        public $optionConfig        = array(
                                            'insertable'   => true,
                                            'editable'     => true,
                                            'deletable'    => true,
                                            'exportable'   => true,
                                            'searchable'   => true
                                        );
        public $exportFileName      = 'exprtData';
        public $condition           = '';
        public $order               = '';
        public $fieldOptionCache    = array();
        public $fieldArray          = array();
        public $fieldConfig         = array();
        public $templateArray       = array(
                                        'index'             => '//VD_CRUD/index',
                                        'editForm'          => '//VD_CRUD/editFormContent',
                                        'listViewContent'   => '//VD_CRUD/listViewContent',
                                        'listViewPaging'    => '//VD_CRUD/listViewPaging',
                                    );
        
        
        public function init()
        {
            parent::init();          
            $this->primaryKey           = ($this->model) ? $this->model->tableSchema->primaryKey : '' ;
            $this->fieldArray           = $this->getFieldArray();
        }
        
        public function getFieldArray()
        {
            $fieldArray = array();
            if ($this->fieldConfig){
                foreach ($this->fieldConfig as $thisConfig){
                    $fieldArray[]   = VD_CRUDConfigArray::create($thisConfig);
                }
            }
            
            return $fieldArray;
        }
        
        public function getListData($condition = '',$targetPage = 1,$order = '')
        {
            $criteria               = new CDbCriteria;
            if ($condition != ''){
                $gateCheck  = trim(strtolower(substr(trim($condition), 0, 3)));
                if (strpos($gateCheck,'and') === false && strpos($gateCheck,'or') === false){
                    $condition = '1 = 1 AND '.$condition.' ';
                }else{
                    $condition .= ' '.$condition;
                }
            }else{
                $condition  = ' 1 = 1 ';
            }
			$criteria->condition    = $condition;
			$pageSize               = $this->pageSize;

			##### PAGE SETTING
			$totalRec       = $this->model->count($criteria);
			$noOfPage       = ceil($totalRec/$pageSize);
			$cuurentPage    = (!isset($targetPage) || !is_numeric($targetPage)) 
							? 1            
							: $targetPage;
			$currentPage    = ($cuurentPage > $noOfPage)   
							? ($noOfPage)  
							: ($cuurentPage);
			$currentPage    = ($cuurentPage < 1)           
							? 1            
							: ($cuurentPage);
			$recStart       = ($currentPage - 1) * $pageSize;
			##### GENERATE LISTING
			$criteria->limit    = $pageSize;
			$criteria->offset   = $recStart;
			$criteria->order    = ($order == '') ? $this->primaryKey.' ASC '  : $order ;
			$rowsData 			= $this->model->findAll($criteria);
			$paging 			= UtilityHelper::pagination($currentPage,$noOfPage,5);
			return array('rowsData'=> $rowsData,'paging' => $paging);            
        }
        
        public function HTMLFieldCreator($createMode,$fieldIdx,$thisRowData,$extendfieldConfig = array())
        {
            $thisRowData    = (!is_object($thisRowData)) ? new stdClass() : $thisRowData ;
            $fieldConfig    = array_merge($this->fieldArray[$fieldIdx],(array)$extendfieldConfig);
            $fieldType      = $fieldConfig['fieldType'];
            $fieldName      = $fieldConfig['fieldName'];
            $defaultValue   = $fieldConfig['defaultValue'];
            $fieldValue     = ($createMode == 'create') ? $defaultValue : $thisRowData->{$fieldName} ;
            $defaultAttr    = array_merge(
                                    (array)$fieldConfig[$createMode.'ViewfieldAttr'],
                                    array(
                                        'id'    => $createMode.'Input'.ucwords($fieldName),
                                        'name'  => $fieldName,
                                    )
                            );
            $currentAttr    = $this->buildHtmlAttr($defaultAttr);
            $html = '';
            if ( $fieldConfig['renderFunction'] ){
                $html   = call_user_func($fieldConfig['renderFunction'],$createMode,$fieldConfig,$thisRowData);
            }else{
                switch ($fieldType){
                    case 'text':
                            $html   = ($fieldValue || $fieldValue == '0') ? $fieldValue : '' ;
                            if ($createMode == 'create' || $createMode == 'edit'){
                                $html   = '<input type="text" value="'.$fieldValue.'" '.$currentAttr.'/>';
                            }else if($createMode == 'export'){
                                $html   = str_replace("'",'',$fieldValue);
                            }
                        break;
                    case 'textarea':
                            $html   = ($fieldValue || $fieldValue == '0') ? $fieldValue : '' ;
                            if ($createMode == 'create' || $createMode == 'edit'){
                                $html   = '<textarea '.$currentAttr.'>'.$fieldValue.'</textarea>';
                            }else if($createMode == 'export'){
                                $html   = str_replace("'",'',$fieldValue);
                            }
                        break;
                    case 'image':
                            $link   = str_replace('/restricted','',$this->baseUrl.$fieldConfig['uploadPath'].str_replace($fieldConfig['uploadPath'],'',$fieldValue));
                            $html   = ($fieldValue) ? '<a href="'.$link.'" target="_blank"><img src="'.$link.'" '.$currentAttr.' /></a>' : '' ;
                            if ($createMode == 'create' || $createMode == 'edit'){
                                $html   = '<input type="file" '.$currentAttr.' />';
                                if ($createMode == 'edit' && $fieldValue){
                                    $html   .= '<input type="checkbox" name="removeImage[]" value="'.$fieldName.'" /> Remove<br><a href="'.$link.'" target="_blank">'.$fieldValue.'</a>';
                                }
                            }else if($createMode == 'export'){
                                $html   = $link;
                            }
                        break;
                    case 'file':
                            $link   = str_replace('/restricted','',$this->baseUrl.$fieldConfig['uploadPath'].str_replace($fieldConfig['uploadPath'],'',$fieldValue));
                            $html   = ($fieldValue) ? '<a href="'.$link.'" target="_blank" '.$currentAttr.'>'.$link.'</a>' : '' ;
                            if ($createMode == 'create' || $createMode == 'edit'){
                                $html   = '<input type="file" '.$currentAttr.' />';
                                if ($createMode == 'edit' && $fieldValue){
                                    $html   .= '<input type="checkbox" name="removeImage[]" value="'.$fieldName.'" /> Remove<br><a href="'.$link.'" target="_blank">'.$fieldValue.'</a>';
                                }
                            }else if($createMode == 'export'){
                                $html   = $link;
                            }
                        break;
                    case 'select':
                            $displayValue           = '';
                            $displayMode            = 'array';
                            $thisFieldOption        = array();
                            $modelConfig            = $fieldConfig['optionFromModel'];
                            $optionModel            = $modelConfig['model']; 
                            $optionFieldName        = $modelConfig['fieldName'];
                            $optionModelRefKey      = ($modelConfig['key'] == '' ) ? $optionModel->tableSchema->primaryKey : $modelConfig['key'] ;

                            // Array Caching 
                            if (sizeof($this->fieldOptionCache[$fieldIdx]) <= 0){
                                $optionMode = ($optionModel != '') ? 'model' : 'array' ;
                                if ($optionMode == 'model'){
                                    $options                = $optionModel->findAll($modelConfig['criteria']);
                                    if($options){
                                        foreach($options as $thisOptions){
                                            $thisFieldOption[$thisOptions->{$optionModelRefKey}] = $thisOptions->{$optionFieldName};
                                        }
                                    }
                                    $this->fieldOptionCache[$fieldIdx] = $thisFieldOption;
                                }else{
                                    $this->fieldOptionCache[$fieldIdx] = $fieldConfig['optionArray'];
                                }
                            }
                            $thisFieldOption = $this->fieldOptionCache[$fieldIdx];

                            // Printing Dropdown
                            $displayValue       = $thisFieldOption[$fieldValue];
                            $optionHtml         = '';
                            foreach ($thisFieldOption as $primaryKeyValue => $displayText){
                                if ($fieldValue != ''){
                                    $selected       = ($primaryKeyValue == $fieldValue) ? 'selected' : '' ;
                                }
                                $optionHtml     .= '<option value="'.$primaryKeyValue.'" '.$selected.'>'.$displayText.'</option>'."\r\n";
                            }

                            /*
                             * Create HTML process
                             */
                            $html           = ($fieldValue || $fieldValue == '0') ? $displayValue : '';
                            if (in_array($createMode,array('create','edit','search')) == 'create' || $createMode == 'edit' ){
                                if ($displayMode == 'array'){
                                    $spaceOption    = ($createMode == 'search') ? '<option></option>'."\r\n" : '' ;
                                    $html   = '<select '.$currentAttr.'>'."\r\n".$spaceOption.$optionHtml.'</select>'."\r\n";
                                }else{
                                    $html   = '<input type="text" class="searchField" style="margin-bottom: 0px;padding:0px;width:100%" onKeyUp="searchResult(\''.$fieldName.'\',this.value)">';
                                }
                            }else if($createMode == 'export'){
                                $html   = str_replace("'",'',$displayValue);
                            }                 
                        break;
                    case 'password':
                            $html   = '<input type="password" '.$currentAttr.'/>';
                        break;
                    case 'primaryKey':
                            $html   = ($fieldValue || $fieldValue == '0') ? $fieldValue : '' ;
                            if ($createMode == 'create' || $createMode == 'edit'){
                                $html   = '<input type="text" '.$currentAttr.'/>';
                            }else if($createMode == 'export'){
                                $html   = str_replace("'",'',$fieldValue);
                            }
                        break;    
                    case 'dateTime':
                            $html   = ($fieldValue || $fieldValue == '0') ? $fieldValue : '' ;
                            if ($createMode == 'create' ||$createMode == 'edit' ){
                                $html   = '<input type="text" '.$currentAttr.' value="'.$fieldValue.'" />';
                            }else if($createMode == 'export'){
                                $html   = str_replace("'",'',$fieldValue);
                            }
                        break;    
                    default:
                        $html = '';
                }
            }
            
            return $html;
        }
        
        public function buildHtmlAttr($attrArray = array())
        {
            $htmlAttr = '';
            if (is_array($attrArray)){
                foreach($attrArray as $attrName => $attrVal){
                    $htmlAttr .= ' '.$attrName.'="'.$attrVal.'"';
                }
            }
            return $htmlAttr;
        }
        
        public function actionIndex()
        {
            $this->render($this->templateArray['index']);
        }

        public function actionLoadListContent()
        {
            try{
                $reply      = array();
                $condition  = '1 = 1 ';
                if ($this->condition != ''){
                    $gateCheck  = trim(strtolower(substr(trim($this->condition), 0, 3)));
                    if (strpos($gateCheck,'and') === false && strpos($gateCheck,'or') === false){
                        $condition .= ' AND '.$this->condition.' ';
                    }else{
                        $condition .= ' '.$this->condition.' ';
                    }
                }
                if ($_POST['searchFieldVal']){
                    $condition .= $this->genCondition($_POST['searchFieldVal']);
                }
                if (trim($_POST['pageSize']) != ''){
                    $this->pageSize = (int)$_POST['pageSize'];
                }
                if (trim($_POST['sortField']) != ''){
                    $fieldName      = $_POST['sortField'];
                    $sortOrder      = ($_POST['sortMode'] == 'asc') ? 'ASC' : 'DESC';
                    $this->order    = " `t`.`{$fieldName}` {$sortOrder} ";
                }
                $listData                       = $this->getListData($condition, $_POST['page'], $this->order);
                $reply['status']                = true;
                $reply['listTableContent']      = $this->renderPartial($this->templateArray['listViewContent'],array('listData'=>$listData),true);
                $reply['pagingContent']         = $this->renderPartial($this->templateArray['listViewPaging'],array('listData'=>$listData),true);
            }catch(Exception $e){
                echo $e->getMessage();
            }
            die(json_encode($reply));
        }
        
        public function genCondition($searchFieldVal)
        {
            $condition = '';
            foreach($this->fieldArray as $thisField){
                if($thisField['searchable']){
                    $fieldName  = $thisField['fieldName']; 
                    $searchVal  = trim($searchFieldVal[$fieldName]);
                    $fieldType  = strtolower($this->model->tableSchema->getColumn($fieldName)->type);
                    if ($searchVal != ''){
                        if ($thisField['fieldType'] == 'select'){
                            if ($thisField['optionFromModel']['model'] != ''){
                                $optionModel                = $thisField['optionFromModel']['model']; 
                                $optionModelRelatedField    = $thisField['optionFromModel']['fieldName'];
                                $optionModelTable           = $optionModel->tableName();
                                $optionModelPrimaryKey      = ($fieldConfig['optionRefKey'] == '' ) ? $optionModel->tableSchema->primaryKey : $fieldConfig['optionRefKey'] ;
                                $condition .= " AND `{$fieldName}` IN ( SELECT `{$optionModelPrimaryKey}` FROM {$optionModelTable} WHERE `{$optionModelRelatedField}` LIKE '%{$searchVal}%' ) ";
                            }else{
                                $condition .= " AND `{$fieldName}` = '{$searchVal}' ";
                            }
                        }else{
                            if ($fieldType == 'string'){
                                $condition .= " AND `{$fieldName}` LIKE '%{$searchVal}%' ";
                            }else{
                                $condition .= " AND `{$fieldName}` = '{$searchVal}' ";
                            }
                        }
                    }
                }
            }    
            return $condition;
        }   
        
        public function actionLoadEditForm()
        {
            $reply              = array();
            $reply['status']    = true;
            if ($_POST[$this->primaryKey]){
                $primaryKeyValue            = (int)$_POST[$this->primaryKey];
                $thisRowData                = $this->model->findByPk($primaryKeyValue);
                $reply['editFormContent']   = $this->renderPartial($this->templateArray['editForm'],array('thisRowData'=>$thisRowData),true);
            }else{
                $reply['status']    = false;
            }
            die(json_encode($reply));
        }
        
        public function actionInsert()
        {
            $responseArray  = array();
            $errorArray     = array();
            try{
                if ($_POST){
                    $thisModel              = new $this->model();
                    $thisModel->scenario    = 'backend'; 
                    foreach($this->fieldArray as $thisField){
                        if($thisField['insertable']){
                            $fieldName = $thisField['fieldName']; 
                            if (in_array($thisField['fieldType'],array('image','file'))){
                                $fileInfo   = $_FILES[$fieldName];
                                if ($fileInfo){
                                    $path       = $this->uploadFile($fileInfo,$thisField);
                                    if ($path){
                                        $thisModel->{$fieldName}    = $path;
                                    }
                                }
                            }else if($thisField['fieldType'] == 'password'){
                                if (trim($_POST[$fieldName]) == ''){
                                    $errorArray[$fieldName][] = $thisField['label'].' can not be empty.';
                                }else{
                                    $thisModel->{$fieldName}    = md5($_POST[$fieldName].Yii::app()->params['md5Salt']);
                                }
                            }else if($thisField['fieldType'] == 'dateTime'){
                                if ($thisField['insertWithNow']){
                                    $thisModel->{$fieldName}    = new CDbExpression('NOW()');
                                }else{
                                    if ($_POST[$fieldName]){
                                        $thisModel->{$fieldName}    = $_POST[$fieldName];
                                    }
                                }
                            }else{
                                if ($thisField['dataType'] == 'integer'){
                                    $thisModel->{$fieldName}    = (int)$_POST[$fieldName];
                                }else{
                                    $thisModel->{$fieldName}    = $_POST[$fieldName];
                                }
                            }
                        }
                    }
                    if ($thisModel->validate() && sizeof($errorArray) <= 0){
                        if ($thisModel->save()){
                            $responseArray['status'] = true;
                            die(json_encode($responseArray));
                        }else{
                            if (sizeof($errorArray) > 0){
                                foreach($errorArray as $fieldName => $thisFieldErrors){
                                    $thisModel->addError($fieldName,$thisFieldErrors[0]);
                                }
                            }
                            $responseArray['status'] = false;
                            $responseArray['errors'] = $thisModel->getErrors();
                            die(json_encode($responseArray));
                        }
                    }else{
                        if (sizeof($errorArray) > 0){
                            foreach($errorArray as $fieldName => $thisFieldErrors){
                                $thisModel->addError($fieldName,$thisFieldErrors[0]);
                            }
                        }
                        $responseArray['status'] = false;
                        $responseArray['errors'] = $thisModel->getErrors();
                        die(json_encode($responseArray));   
                    }
                }
            }catch(Exception $e){
                echo $e->getMessage();
            }
        }
        
        public function actionUpdate()
        {
            if ($_POST){
                $primaryKeyValue        = (int)$_GET[$this->primaryKey];
                $thisModel              = $this->model->findByPk($primaryKeyValue);
                if ($thisModel){
                    $thisModel->scenario    = 'backend';
                    $removeImageArray       = (sizeof($_POST['removeImage']) > 0) ? $_POST['removeImage'] : array();
                    foreach($this->fieldArray as $thisField){
                        if($thisField['editable']){
                            $fieldName = $thisField['fieldName']; 
                            if (in_array($thisField['fieldType'],array('image','file'))){
                                if (in_array($fieldName,$removeImageArray)){
                                     $thisModel->{$fieldName}    = '';
                                }
                                $fileInfo   = $_FILES[$fieldName];
                                if ($fileInfo){
                                    $path       = $this->uploadFile($fileInfo,$thisField);
                                    if ($path){
                                        $thisModel->{$fieldName}    = $path;
                                    }
                                }
                            }else if($thisField['fieldType'] == 'password'){
                                if (trim($_POST[$fieldName]) != ''){
                                    $thisModel->{$fieldName}    = md5($_POST[$fieldName].Yii::app()->params['md5Salt']);
                                }
                            }else if($thisField['fieldType'] == 'dateTime'){
                                if ($thisField['updateWithNow']){
                                    $thisModel->{$fieldName}    = new CDbExpression('NOW()');
                                }else{
                                    if ($_POST[$fieldName]){
                                        $thisModel->{$fieldName}    = $_POST[$fieldName];
                                    }
                                }
                            }else{
                                if ($thisField['dataType'] == 'integer'){
                                    $thisModel->{$fieldName}    = (int)$_POST[$fieldName];
                                }else{
                                    $thisModel->{$fieldName}    = $_POST[$fieldName];
                                }
                            }
                        }
                    }
                    if ($thisModel->save()){
                        $responseArray['status'] = true;
                        die(json_encode($responseArray));
                    }else{
                        $responseArray['status'] = false;
                        $responseArray['errors'] = $thisModel->getErrors();
                        die(json_encode($responseArray));
                    }                  
                }else{
                    // echo 'No Record';
                    $responseArray['status'] = false;
                    die(json_encode($responseArray));
                }
            }else{
                // echo 'POST ERROR';
                $responseArray['status'] = false;
                die(json_encode($responseArray));
            }
        }
        
        public function actionDelete()
        {
            if ($_POST[$this->primaryKey]){
                 $primaryKeyValue    = (int)$_POST[$this->primaryKey];
                 $status             = $this->model->deleteByPk($primaryKeyValue);
                 die(json_encode(array('status'=>$status)));
            }else{
                 die(json_encode(array('status'=>false)));
            }
        }

        public function uploadFile($fileInfo,$fieldSetting)
        {
            $fieldType      = $fieldSetting['fieldType'];
            $fieldName      = $fieldSetting['fieldName'];
            $uploadPath     = $fieldSetting['uploadPath'];
            $basePath       = realpath(dirname(__FILE__).'/../../../');
            $tempFile       = explode('.',$fileInfo['name']);
            $extension      = strtolower(end($tempFile));
            $newFileName    = date('YmdHis').rand(1000,9999).'_'.$fieldName.'.'.$extension;
            $fileUploadPath = $basePath.'/'.$uploadPath.'/'.$newFileName;
            $extensionArray = ($fieldSetting['allowedExtension']) ? $fieldSetting['allowedExtension'] : array('jpg','jpeg','gif','png','pdf');
            if (in_array($extension,$extensionArray)){
                if (move_uploaded_file($fileInfo['tmp_name'],$fileUploadPath)){
//                    if ($fieldType == 'image'){
//                        $imageOption    = $fieldSetting['imageOption'];
//                        if ($imageOption['width'] != '' ||  $imageOption['height'] != ''){
//                            // Re-initialize Width
//                            if ($imageOption['width'] == ''){
//                                $imageOption['width'] = $imageOption['height'];
//                            }
//                            // Re-initialize Height
//                            if ($imageOption['height'] == ''){
//                                $imageOption['height'] = $imageOption['width'];
//                            }
//                            $resizeOption   = (in_array($imageOption['option'],array('auto','crop','exact','portrait','landscape'))) ? $imageOption['option'] : 'auto';
//                            $resizeObj      = new Resize($fileUploadPath);
//                            $resizeObj->resizeImage($imageOption['width'], $imageOption['height'], $resizeOption);
//                            $resizeObj->saveImage($fileUploadPath, 100);
//                        }
//                    }
                    return $uploadPath.'/'.$newFileName;
                }else{ // UPLOAD FAIL
                    return false;
                }
            }else{  // INCORRECT EXTENSION
                return false;
            }
        }
        
        public function actionExport()
        {
            $filename   = $this->exportFileName;  

            header('Expires: 0');
            header('Cache-control: private');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Content-Description: File Transfer');
            header('Content-Type: application/vnd.ms-excel; charset=utf-8');
            header('Content-disposition: attachment; filename='.$filename.'.csv');
            
            $condition  = '1 = 1 ';
            if ($this->condition != ''){
                $gateCheck  = trim(strtolower(substr(trim($this->condition), 0, 3)));
                if (strpos($gateCheck,'and') === false && strpos($gateCheck,'or') === false){
                    $condition .= ' AND '.$this->condition.' ';
                }else{
                    $condition .= ' '.$this->condition.' ';
                }
            }

            $criteria            = new CDbCriteria;
            $criteria->condition = $condition;
            $criteria->order     = ($this->primaryKey) ? $this->primaryKey.' ASC ' : '' ;

            echo "\xEF\xBB\xBF";
            $headerArray = array();
            if($this->fieldArray){
                foreach($this->fieldArray as $thisConfig){
                    if ($thisConfig['showInExport']){
                            $headerArray[] = $thisConfig['label'] ;
                    }
                }
            }
            $header = '';
            foreach($headerArray as $thisHeader){
                $header .= '"'.$thisHeader.'",';
            } 
            echo substr($header,0,-1)."\r\n";

            $pageSize       = 200;
            $noOfData       = $this->model->count($criteria);
            for ($i = 0;$i < $noOfData;){
                $criteria->limit    = $pageSize;
                $criteria->offset   = $i;
                $data  = $this->model->findAll($criteria);
                foreach($data as $thisData){
                    $row = '';
                    $tempArray = array();
                    if($this->fieldArray){
                        foreach($this->fieldArray as $fieldIdx => $thisConfig){
                            if ($thisConfig['showInExport']){
                                    $tempArray[] = $this->HTMLFieldCreator('export',$fieldIdx,$thisData);
                            }
                        }
                    }
                    foreach($tempArray as $tempValue){
                        $row .= '"'.$tempValue.'",';
                    }     
                    echo substr($row,0,-1)."\r\n";
                    $i++;
                }
            }
        }
    }
?>
