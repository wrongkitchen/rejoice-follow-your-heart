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
    
    function saveCreateForm()
    {
        showFLoader();
        CKupdate();
        $('#createFromAlertContent').html('');
        $('#createForm' ).ajaxSubmit( {
            url         : '<?php echo $this->createUrl(Yii::app()->controller->id.'/feedInsert');?>',
            type        : 'post',
            dataType    : 'json',
            success: function( response ) {
                if ( response.status ) {
                    $('#createFormAlert').hide();
                    location.href   = '<?php echo $this->createUrl(Yii::app()->controller->id.'/feedIndex',$_GET);?>';
                } else {
                    hideFLoader();
                    var content='';
                    for(var key in response.errors)
                    {    
                        content += '-&nbsp;'+response.errors[key][0]+'<br />';
                    }
                    $('#createFromAlertContent').html(content)
                    $('#createFormAlert').show();
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
        <div class="alert alert-danger" id="createFormAlert" style="display:none">
            <button type="button" class="close" onClick="$('#createFormAlert').hide()">×</button>
            <div id="createFromAlertContent">

            </div>
        </div>
        <form method="POST" enctype="multipart/form-data" autocomplete="off" class="form-horizontal" onSubmit="return false;" id="createForm" role="form">
            <?php if ($this->fieldArray): ?>
                <?php foreach($this->fieldArray as $fieldIdx => $thisField):?>
                    <?php if($thisField['insertable']): ?>
                        <?php if($thisField['showInInsert']): ?>
                            <div class="form-group">
                                <label class="col-lg-3 control-label" for="<?php echo 'input'.ucwords($thisField['fieldName']); ?>"><?php echo $thisField['label']; ?></label>
                                <div class="col-lg-9">
                                    <?php  if ($thisField['regId'] == 'fbConfigIdField'): ?>
                                        <div class="form-plain-text"><?php echo (int)$_GET[$thisField['fieldName']]; ?></div>
                                        <input type="hidden" value="<?php echo (int)$_GET[$thisField['fieldName']]; ?>" class="form-control" id="createInput<?php echo ucwords($thisField['fieldName']); ?>" name="<?php echo $thisField['fieldName']; ?>">
                                     <?php  else: ?>
                                        <?php echo $this->HTMLFieldCreator('create',$fieldIdx,'');?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
            <div class="form-group">
                <label class="col-lg-3 control-label" for=""></label>
                <div class="col-lg-9">
                    <button type="button" class="btn btn-primary" onClick="saveCreateForm()">Save</</button>
                </div>
            </div>
        </form>
    </div>
</div>