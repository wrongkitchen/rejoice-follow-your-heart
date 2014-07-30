<!DOCTYPE html>
<html>
    <head>
		<meta name="viewport" content="width=640" />
        <meta name="viewport" content="user-scalable=YES" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <meta name="apple-mobile-web-app-capable" content="YES" />
        <meta name="apple-touch-fullscreen" content="YES" />
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <link rel="shortcut icon" href="<?php echo $this->resourceUrl; ?>/images/favicon.ico">
        <title></title>
        <link rel="stylesheet" type="text/css" href="<?php echo $this->resourceUrl; ?>/css/<?php echo $this->lang->suffix;?>/style.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->resourceUrl; ?>/js/fancybox/jquery.fancybox-1.3.4.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->resourceUrl; ?>/js/jqueryui/themes/base/jquery-ui.css" >
        <link rel="stylesheet" type="text/css" href="<?php echo $this->resourceUrl; ?>/js/jqueryui/themes/base/jquery-ui-timepicker-addon.css">
        <script type="text/javascript" language="javascript" src="<?php echo $this->resourceUrl; ?>/js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" language="javascript" src="<?php echo $this->resourceUrl; ?>/js/main.js"></script>
        <script type="text/javascript" language="javascript" src="<?php echo $this->resourceUrl; ?>/js/fancybox/jquery.fancybox-1.3.4.js"></script>
        <script type="text/javascript" language="javascript" src="<?php echo $this->resourceUrl; ?>/js/jqueryui/jquery-ui.min.js"></script>
        <script type="text/javascript" language="javascript" src="<?php echo $this->resourceUrl; ?>/js/jqueryui/jquery-ui-timepicker-addon.js"></script>
        <script type="text/javascript">
            $(function(){
                window.site.setLanguage('<?php echo $this->lang->suffix; ?>');
                window.site.setBaseUrl('<?php echo Yii::app()->request->baseUrl; ?>');
            });
        </script> 
    </head>
    <body class="<?php echo ($this->campaign->debug_mode == '1') ? 'debug' : '' ;?>">
        <div id="pageOverlay"></div>
		<?php echo $content; ?>
        <script>
            window.fbAsyncInit = function() {
                // init the FB JS SDK
                FB.init({
                    appId       : <?php echo Yii::app()->params['facebook']['appId']; ?>, 
                    status      : true,
                    cookie      : true,
                    xfbml       : true
                });

                // Additional initialization code such as adding Event Listeners goes here
            };

            // Load the SDK asynchronously
            (function(d, debug){
                var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
                if (d.getElementById(id)) {return;}
                js = d.createElement('script'); js.id = id; js.async = true;
                js.src = "//connect.facebook.net/en_US/all" + (debug ? "/debug" : "") + ".js";
                ref.parentNode.insertBefore(js, ref);
            }(document, /*debug*/ false));
        </script>
        <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-37456538-4', 'kfccampaign.com');
  ga('send', 'pageview');

</script>
    </body>
</html>
