<?php if ($listData['rowsData']): ?>
    <?php foreach ($listData['rowsData'] as $thisRows): ?>
        <?php $primaryKeyValue  = $thisRows->{$this->primaryKey}; ?>
        <tr>
            <td>
                <?php if ($this->optionConfig['editable'] ):?>
                   <i class="glyphicon glyphicon-edit" title="Edit" style="cursor:pointer" onClick="location.href='<?php echo $this->createUrl(Yii::app()->controller->id.'/edit',array($this->primaryKey => $primaryKeyValue)); ?>'"></i>
                <?php endif; ?>
                <?php if ($this->optionConfig['deletable'] ):?>
                    <i class="glyphicon glyphicon-remove" title="Delete" style="cursor:pointer" onClick="deleteRecord('<?php echo $primaryKeyValue; ?>')"></i>
                <?php endif; ?>
            </td>
            <?php foreach($this->fieldArray as $fieldIdx => $thisField):?>
                <?php if($thisField['showInList']): ?>
                <td <?php echo $this->buildHtmlAttr($thisField['listViewfieldAttr']); ?>>
                    <?php echo $this->HTMLFieldCreator('list',$fieldIdx,$thisRows); ?>
                </td>
                <?php endif; ?>
            <?php endforeach; ?>
        </tr>    
    <?php endforeach; ?>
<?php else: ?>
    No Data
<?php endif; ?>