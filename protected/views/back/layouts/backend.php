<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Admin Panel</title>
        <script type="text/javascript" src="<?php echo $this->resourceUrl;?>/js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="<?php echo $this->resourceUrl; ?>/js/jqueryui/jquery-ui.min.js"></script>
        <script type="text/javascript" src="<?php echo $this->resourceUrl; ?>/js/jqueryui/jquery-ui-timepicker-addon.js"></script>
        <script type="text/javascript" src="<?php echo $this->resourceUrl;?>/js/jquery.form.js"></script>
        <script type="text/javascript" src="<?php echo $this->resourceUrl; ?>/js/ckeditor/ckeditor.js"></script>
        <script type="text/javascript" src="<?php echo $this->resourceUrl;?>/js/bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo $this->resourceUrl; ?>/js/jquery.mousewheel.min.js"></script>
        <script type="text/javascript" src="<?php echo $this->resourceUrl; ?>/js/mScroller/jquery.mCustomScrollbar.min.js"></script>
        <script type="text/javascript" src="<?php echo $this->resourceUrl; ?>/js/fancybox/jquery.fancybox-1.3.4.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo $this->resourceUrl;?>/js/bootstrap/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $this->resourceUrl; ?>/css/backend/style.css?v=<?php echo date('YmdHis')?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->resourceUrl; ?>/js/jqueryui/themes/base/jquery-ui.css" >
        <link rel="stylesheet" type="text/css" href="<?php echo $this->resourceUrl; ?>/js/jqueryui/themes/base/jquery-ui-timepicker-addon.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $this->resourceUrl; ?>/js/mScroller/jquery.mCustomScrollbar.css" >
        <link rel="stylesheet" type="text/css" href="<?php echo $this->resourceUrl; ?>/js/fancybox/jquery.fancybox-1.3.4.css" />
        <script>
            $(function(){
                $('#leftMenuBtn').click(function(){
                    if ($(this).hasClass('v-open-menu')){
                        $('#leftHiddenMenu').animate({'left':'-=200px'},800,'easeInOutQuart');$('#contentContainer').animate({'margin-left':'-=200px'},800,'easeInOutQuart');
                        $(this).removeClass('v-open-menu');
                    }else{
                        $('#leftHiddenMenu').animate({'left':'+=200px'},800,'easeInOutQuart');$('#contentContainer').animate({'margin-left':'+=200px'},800,'easeInOutQuart');
                        $(this).addClass('v-open-menu');
                    }
                })
                $('#leftHiddenMenu').css({'height':$(document).height()+'px'});
                <?php if(strtolower(Yii::app()->controller->id) == 'site' && ($this->actionId) == 'index'):?>
                    $('#leftHiddenMenu').animate({'left':'+=200px'},800,'easeInOutQuart');$('#contentContainer').animate({'margin-left':'+=200px'},800,'easeInOutQuart');
                    $(this).addClass('v-open-menu');
                <?php endif; ?>
            });
        </script>
    </head>
    <body>
        <div id="pageOverlay"></div>
        <?php if ($this->showMenu): ?>
            <div id="leftHiddenMenu">
                <div id="leftMenuBtn" title="Menu">&nbsp;&lt;</div>
                <?php $this->renderPartial('//site/section/navBar'); ?>
            </div>
        <?php endif; ?>
        <div id="contentContainer" class="container">
            <?php echo $content; ?>
            <?php if ($this->showMenu): ?>
            <hr class="v-divider">
            <footer>
                <p class="pull-right"><a href="#">Back to top</a></p>
            </footer>
            <?php endif; ?>
        </div>     
        <script>
        var showFLoader   = function(){
            $('#pageOverlay').show();
            $.fancybox.showActivity();
        }
        var hideFLoader   = function(){
            $('#pageOverlay').hide();
            $.fancybox.hideActivity();
        }
        </script>
    </body>
</html>
