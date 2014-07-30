<form action="<?php echo $this->createUrl(Yii::app()->controller->id.'/update',array($this->primaryKey => $_POST[$this->primaryKey]));?>" method="POST" enctype="multipart/form-data" class="form-horizontal" onSubmit="return false;" id="editForm"  role="form">
    <?php if ($this->fieldArray): ?>
        <?php foreach($this->fieldArray as $fieldIdx => $thisField):?>
            <?php if($thisField['showInEdit']): ?>
                <div class="form-group">
                    <label class="col-lg-3 control-label" for="<?php echo 'input'.ucwords($thisField['fieldName']); ?>"><?php echo $thisField['label']; ?></label>
                    <div class="col-lg-9">
                        <?php $createMode = $thisField['editable'] ? 'edit' : 'view' ;?>
                        <?php if ($createMode == 'view'): ?>
                            <div class="form-plain-text"><?php echo $this->HTMLFieldCreator($createMode,$fieldIdx,$thisRowData);?></div>
                        <?php else: ?>
                            <?php echo $this->HTMLFieldCreator($createMode,$fieldIdx,$thisRowData);?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</form>