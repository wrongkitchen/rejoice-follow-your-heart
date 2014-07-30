<ul class="nav nav-tabs">
    <li><a href="<?php echo $this->createUrl(Yii::app()->controller->id.'/index');?>">&lt;&lt; Back</a></li>
    <li class="<?php echo ($this->detailNavName == 'edit') ? 'active' : ''; ?>"><a href="<?php echo $this->createUrl(Yii::app()->controller->id.'/edit',array($this->mainPrimaryKey=>$_GET[$this->mainPrimaryKey]));?>">Edit Content</a></li>
    <li class="dropdown <?php echo ($this->detailNavName == 'feed') ? 'active' : ''; ?> ">
        <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;">
            Feed <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
           <li><a href="<?php echo $this->createUrl(Yii::app()->controller->id.'/feedIndex',array($this->mainPrimaryKey=>$_GET[$this->mainPrimaryKey]));?>">Listing</a></li>
           <li><a href="<?php echo $this->createUrl(Yii::app()->controller->id.'/feedCreate',array($this->mainPrimaryKey=>$_GET[$this->mainPrimaryKey]));?>">Create</a></li>
        </ul>
    </li>
</ul>