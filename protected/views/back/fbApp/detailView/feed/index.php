<script>
        
    var searchFieldVal          = new Object;
    var pageSize                = '';
    var sortField               = '';
    var currentSortMode         = 'asc';
    var currentPage             = 1;
    
    $(function(){
        loadListContent();
        $("#dataContainer").mCustomScrollbar({
            horizontalScroll:true,
            theme:"dark-thick",
            scrollButtons:{
                enable:true
            },    
            advanced:{
                updateOnContentResize: true
            }
        }); 
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
        var url             = '<?php echo $this->createUrl(Yii::app()->controller->id.'/FeedLoadListContent',$_GET); ?>';
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

    function deleteRecord(primaryKeyValue)
    {
        var result  = confirm('Are you sure to delete this record?');
        if (result){
           var url = '<?php echo $this->createUrl(Yii::app()->controller->id.'/feedDelete'); ?>';
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
        <table class="table table-striped table-hover table-condensed" id="listTable">
            <thead>
                <?php $this->renderPartial('listViewTableHeader'); ?>
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