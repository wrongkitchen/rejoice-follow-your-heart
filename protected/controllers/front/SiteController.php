<?php
class SiteController Extends FrontendController
{
    public function init()
    {
        parent::init();
    }
    
    public function actionIndex()
    { 
        die();
    }

    public function actionError()
    {
        $error  = Yii::app()->errorHandler->error;
        if($error){
            if(Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }
}

?>
