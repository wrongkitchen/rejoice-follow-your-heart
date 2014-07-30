<tr>
    <th>Option</th>
    <?php if($this->fieldArray): ?>
        <?php foreach($this->fieldArray as $thisConfig):?>
            <?php if ($thisConfig['showInList']):?>
                <?php $sortHeader = ($thisConfig['sortable']) ? 'sortHeader' : '' ;?>
                <th class="<?php echo $sortHeader; ?>" id="fieldHeader_<?php echo $thisConfig['fieldName'] ;?>" nowrap><?php echo $thisConfig['label'] ;?></th>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</tr>
<?php if ($this->optionConfig['searchable']): ?>
<tr>
    <th></th>
    <?php if($this->fieldArray): ?>
        <?php foreach($this->fieldArray as $fieldIdx => $thisConfig):?>
            <?php if ($thisConfig['showInList']):?>
                <th>
                    <?php if ($thisConfig['searchable']):?>
                        <?php if($thisConfig['fieldType'] == 'select' ): ?>
                            <?php $extendArray['searchViewfieldAttr'] = array('style' => 'margin-bottom: 0px;padding:0px;width:100%;height:22px','onchange'  => 'searchResult(\''.$thisConfig['fieldName'].'\',this.value)','class' => 'searchField'); ?>
                            <?php echo $this->HTMLFieldCreator('search',$fieldIdx,'',$extendArray);  ?>
                        <?php else: ?>
                            <input type="text" class="searchField" style="margin-bottom: 0px;padding:0px;width:100%" onKeyUp="searchResult('<?php echo $thisConfig['fieldName']; ?>',this.value)">
                        <?php endif; ?>
                    <?php endif; ?>
                </th>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</tr>
<?php endif; ?>