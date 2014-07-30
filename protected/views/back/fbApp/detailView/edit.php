<script>
    $(function(){
        $('.dateTimePicker').datetimepicker({
            dateFormat: 'yy-mm-dd',
            timeFormat: 'HH:mm:ss'
        });   
    })
    
    function CKupdate(){
        for (var instance in CKEDITOR.instances ){
            CKEDITOR.instances[instance].updateElement();
        }
    }
    
    function saveEditForm()
    {
        showFLoader();
        CKupdate();
        $('#editFromAlertContent').html('');
        $('#editFormAlert').hide();
        $('#editFormSuccess').hide();
        $('#editForm' ).ajaxSubmit( {
            type        : 'post',
            dataType    : 'json',
            success: function( response ) {
                if ( response.status ) {
                    $('#editFormAlert').hide();
                    $('#editFormSuccess').show();
                     window.scrollTo(0,0);
                     hideFLoader();
                } else {
                    hideFLoader();
                    var content='';
                    for(var key in response.errors)
                    {    
                        content += '-&nbsp;'+response.errors[key][0]+'<br />';
                    }
                    $('#editFromAlertContent').html(content)
                    $('#editFormAlert').show();
                    window.scrollTo(0,0);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                if (textStatus == 'parsererror'){
//                    $('#errorContent').html(XMLHttpRequest.responseText);
//                    $('#errorPanel').show();
                }    
            }
        } );    
    }    
</script>
<div class="container">
    <div class="page-header">
        <h2><?php echo $this->pageTitle?></h2>
    </div>
    <div class="container">
        <?php $this->renderPartial('detailView/navMenu'); ?>
    </div>
    <div class="detail-view-pad-top">&nbsp;</div>
    <div id="dataContainer" class="container" style="overflow-x:auto">
        <div class="alert alert-danger" id="editFormAlert" style="display:none;">
            <button type="button" class="close" onClick="$('#editFormAlert').hide()">×</button>
            <div id="editFromAlertContent">

            </div>
        </div>
        <div class="alert alert-info" id="editFormSuccess" style="display:none;">
            <button type="button" class="close" onClick="$('#editFormSuccess').hide()">×</button>
            <div>
                Save Success!
            </div>
        </div>
        <form action="<?php echo $this->createUrl(Yii::app()->controller->id.'/update',array($this->primaryKey => $thisRowData->{$this->primaryKey}));?>" method="POST" enctype="multipart/form-data" class="form-horizontal" onSubmit="return false;" id="editForm"  role="form">
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
            <div class="form-group">
                <label class="col-lg-3 control-label" for=""></label>
                <div class="col-lg-9">
                    <button type="button" class="btn btn-primary" onClick="saveEditForm()">Save</</button>
                </div>
            </div>
        </form>
    </div>
</div>