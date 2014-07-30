<?php
class BaseController extends CController
{
	public $layout      = '';
	public $menu        = array();
	public $breadcrumbs = array();
    public $baseUrl     = '';
    public $resourceUrl = '';
    public $sid         = '';
    
    public function init()
    {
        parent::init();
        if ($this->sid != ''){
            session_id ($this->sid);
            session_start();
        }else{
            session_start();
        }
        $this->baseUrl      = Yii::app()->request->baseUrl;
        $this->resourceUrl  = $this->baseUrl.'/resource';
    }
    
}