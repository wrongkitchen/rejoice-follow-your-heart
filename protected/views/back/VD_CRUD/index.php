<script>
        
    var searchFieldVal          = new Object;
    var pageSize                = '';
    var sortField               = '';
    var currentSortMode         = 'asc';
    var currentPage             = 1;
    
    $(function(){
        loadListContent();
//        $("#dataContainer").mCustomScrollbar({
//            horizontalScroll:true,
//            theme:"dark-thick",
//            scrollButtons:{
//                enable:true
//            },    
//            advanced:{
//                updateOnContentResize: true
//            }
//        }); 
        $('.sortHeader').click(function(e){
            var sortClassName   = (currentSortMode == 'asc') ? 'sortAsc'  : 'sortDesc' ;
            sortField           = $(this).attr('id').replace('fieldHeader_','');
            if ($(this).hasClass(sortClassName)){
                // Change sort mode
                $(this).removeClass(sortClassName);
                currentSortMode = (currentSortMode == 'asc') ? 'desc' : 'asc';
                // Reset 'sortClassName'
                sortClassName   = (currentSortMode == 'asc') ? 'sortAsc'  : 'sortDesc' ;
                $(this).addClass(sortClassName);
                loadListContent(currentPage);
            }else{
                $('.sortHeader').removeClass(sortClassName);
                $(this).addClass(sortClassName);
                loadListContent(currentPage);
            }
        })
    })

    function CKupdate(){
        for (var instance in CKEDITOR.instances ){
            CKEDITOR.instances[instance].updateElement();
        }
    }
    
    function loadListContent(targetPage){
		var thisCurrentPage = (targetPage) ? targetPage : 1 ;
        var url             = '<?php echo $this->createUrl(Yii::app()->controller->id.'/LoadListContent'); ?>';
        var postData        = {
                                    page            : thisCurrentPage,
                                    searchFieldVal  : searchFieldVal,
                                    pageSize        : pageSize,
                                    sortField       : sortField,
                                    sortMode        : currentSortMode
                              }
        $.ajax(
            {
                url         : url,
                cache       : false,
                data        : postData,
                type        : 'post',
                dataType    : 'json',
                error        : function(XMLHttpRequest, textStatus, errorThrown) {
                    if (textStatus == 'parsererror'){
                        $('#errorContent').html(XMLHttpRequest.responseText);
                        $('#errorPanel').show();
                    }  
                }
            }
        ).done(
            function(response){
                if (response.status){
                    currentPage = thisCurrentPage;
                    $('#listTableRowContainer').html(response.listTableContent);
                    $('#paginationContainer').html(response.pagingContent); 
                }
            }
        )
    }    
    
    function showCreatePanel()
    {
        $('#createFormAlert').hide();
        $('#createFormModal').modal({
            show        : true, 
            backdrop    : 'static'
        });
        $('.dateTimePicker').datetimepicker({
            dateFormat: 'yy-mm-dd',
            timeFormat: 'HH:mm:ss'
        });
        $('#createFormModal').find('.typeaheadIcon').removeClass('icon-ok icon-remove');
        $('#createFormModal').find('.typeaheadCaution').html('');
        $('#createForm')[0].reset();
    }
    
    function showEditPanel(primaryKeyValue)
    {
        $('#editFormAlert').hide();
        var url         = '<?php echo $this->createUrl(Yii::app()->controller->id.'/LoadEditForm');?>';
        var postData    = {<?php echo $this->primaryKey; ?>:primaryKeyValue};
        $.ajax(
            {
                url         : url,
                cache       : false,
                data        : postData,
                type        : 'post',
                dataType    : 'json',
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    if (textStatus == 'parsererror'){
                        $('#errorContent').html(XMLHttpRequest.responseText);
                        $('#errorPanel').show();
                    }  
                }
            }
        ).done(
            function(response){
                if (response.status){
                    $('#editFormContent').html(response.editFormContent); 
                    $('#editFormModal').modal({
                        show        : true, 
                        backdrop    : 'static'
                    });
                    $('#editFormContent').find('.ckeditor').each(function(){
                        var thisID = $(this).attr('id');
                        CKEDITOR.replace(thisID);
                    })
                    $('.dateTimePicker').datetimepicker({
                        dateFormat: 'yy-mm-dd',
                        timeFormat: 'HH:mm:ss'
                    });
                }
            }
        )

    }
    
    function saveCreateForm()
    {
        CKupdate();
        $('#createFromAlertContent').html('');
        $('#createForm' ).ajaxSubmit( {
            url         : '<?php echo $this->createUrl(Yii::app()->controller->id.'/insert');?>',
            type        : 'post',
            dataType    : 'json',
            success: function( response ) {
                if ( response.status ) {
                    $('#createFormModal').modal('hide');
                    $('#createForm' )[0].reset();
                    loadListContent();
                } else {
                    var content='';
                    for(var key in response.errors)
                    {    
                        content += response.errors[key][0]+'<br />';
                        break;
                    }
                    $('#createFromAlertContent').html(content)
                    $('#createFormAlert').show();
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                if (textStatus == 'parsererror'){
                    $('#errorContent').html(XMLHttpRequest.responseText);
                    $('#errorPanel').show();
                }    
            }
        } );    
    }
    
    function saveEditForm()
    {
        CKupdate();
        $('#editFromAlertContent').html('');
        $('#editForm' ).ajaxSubmit( {
            type        : 'post',
            dataType    : 'json',
            success: function( response ) {
                if ( response.status ) {
                    $('#editFormModal').modal('hide');
                    $('#editFormContent').html('');
                    loadListContent();
                } else {
                    var content='';
                    for(var key in response.errors)
                    {    
                        content += response.errors[key][0]+'<br />';
                        break;
                    }
                    $('#editFromAlertContent').html(content)
                    $('#editFormAlert').show();
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                if (textStatus == 'parsererror'){
                    $('#errorContent').html(XMLHttpRequest.responseText);
                    $('#errorPanel').show();
                }  
            }
        } );    
    }
    
    function deleteRecord(primaryKeyValue)
    {
        var result  = confirm('Are you sure to delete this record?');
        if (result){
           var url = '<?php echo $this->createUrl(Yii::app()->controller->id.'/delete'); ?>';
           var data = {<?php echo $this->primaryKey; ?> : primaryKeyValue };
           $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: 'json',
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    if (textStatus == 'parsererror'){
                        $('#errorContent').html(XMLHttpRequest.responseText);
                        $('#errorPanel').show();
                    }  
                }
            }).done(
                function(response){
                    loadListContent();
                }
            );
        }
    }
    
    function changePageSize(newPageSize)
    {
        pageSize = newPageSize;
        loadListContent();
    }
    
    function searchResult(fieldName,value)
    {
        searchFieldVal[fieldName] = value;
        loadListContent();
    }
    
    function exportData()
    {
        location.href = '<?php echo $this->createUrl(Yii::app()->controller->id.'/export'); ?>';
    }
</script>
<div class="container">
    <div class="page-header">
        <h2><?php echo $this->pageTitle?></h2>
    </div>
    <nav class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex2-collapse">
            <span class="sr-only"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"></a>
        </div>
        <div class="collapse navbar-collapse navbar-ex2-collapse">
            <?php if ($this->optionConfig['exportable']): ?>
                <button type="button" class="btn btn-default navbar-btn" onClick="exportData();">Export</button>
            <?php endif; ?>
            <?php if ($this->optionConfig['insertable']): ?>
                <button type="button" class="btn btn-default navbar-btn" onClick="showCreatePanel();">Create New</button>
            <?php endif; ?>  
        </div>
    </nav>
    <div id="dataContainer" class="container" style="overflow-x:auto">
        <table class="table table-striped table-hover table-condensed" id="listTable">
            <thead>
                <?php $this->renderPartial('//VD_CRUD/listViewTableHeader'); ?>
            </thead>
            <tbody id="listTableRowContainer">
                <!-- Ajax Content Here -->
            </tbody>
        </table>    
    </div>
    <nav class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex3-collapse">
            <span class="sr-only"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"></a>
        </div>
        <div class="collapse navbar-collapse navbar-ex3-collapse">
            <ul class="nav navbar-nav">
                <li>
                    <div id="paginationContainer">
                    </div>
                </li>
                <li>
                    <div style="margin:25px 10px" title="Page Size">
                        <select style="width:auto" onChange="changePageSize(this.value)">
                            <option value="1">1</option>
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="50">50</option>
                            <option value="100" selected>100</option>
                            <option value="200">200</option>
                        </select>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</div>
<!-- Create Form Modal -->
<div class="modal fade" id="createFormModal"  tabindex="-1" role="dialog" aria-labelledby="createFormModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4>Create New Record:</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" id="createFormAlert" style="display:none;">
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
                                            <?php echo $this->HTMLFieldCreator('create',$fieldIdx,'');?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onClick="saveCreateForm()">Save</</button>
                <button type="button" class="btn " data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Edit Form Modal -->
<div class="modal fade" id="editFormModal" tabindex="-1" role="dialog" aria-labelledby="editFormModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Update Record:</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" id="editFormAlert" style="display:none;">
                    <button type="button" class="close" onClick="$('#editFormAlert').hide()">×</button>
                    <div id="editFromAlertContent">

                    </div>
                </div>
                <div id="editFormContent">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onClick="saveEditForm()">Save Changes</button>
                <button type="button" class="btn " data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->