<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=640" />
        <meta name="viewport" content="user-scalable=YES" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <meta name="apple-mobile-web-app-capable" content="YES" />
        <meta name="apple-touch-fullscreen" content="YES" />
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="<?php echo $this->resourceUrl; ?>/css/fb/normalize.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->resourceUrl; ?>/js/fancybox/jquery.fancybox-1.3.4.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->resourceUrl; ?>/css/fb/style.css?v=<?php echo date('YmdHis'); ?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->resourceUrl; ?>/css/fb/style_custom.css?v=<?php echo date('YmdHis'); ?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->resourceUrl; ?>/js/bower_components/bootstrap-css/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $this->resourceUrl; ?>/js/bower_components/magnific-popup/dist/magnific-popup.css">
        <script type="text/javascript" language="javascript" src="<?php echo $this->resourceUrl; ?>/js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" language="javascript" src="<?php echo $this->resourceUrl; ?>/js/jqueryui/jquery-ui.min.js"></script>
        <script type="text/javascript" language="javascript" src="<?php echo $this->resourceUrl; ?>/js/fancybox/jquery.fancybox-1.3.4.js"></script>
        <script type="text/javascript" language="javascript" src="<?php echo $this->resourceUrl; ?>/js/jquery.form.js"></script>
        <script type="text/javascript" language="javascript" src="<?php echo $this->resourceUrl; ?>/js/bower_components/magnific-popup/dist/jquery.magnific-popup.min.js"></script>
        <script type="text/javascript" language="javascript" src="<?php echo $this->createUrl('FB/FBJSConfig')?>"></script>
        <script type="text/javascript" language="javascript" src="<?php echo $this->resourceUrl; ?>/js/fb/fb.js"></script>
        <script type="text/javascript" language="javascript" src="<?php echo $this->resourceUrl; ?>/js/fb/main.js?v=<?php echo date('YmdHis'); ?>"></script>
    </head>
<body class="desktop">
<div id="page-overlay"></div>
<div class="wrapper">
    <?php echo $content; ?>
</div>
<script>
    window.fbAsyncInit = function() {
        // init the FB JS SDK
        FB.init({
            appId       : window.fb.fbAppId, 
            status      : true,
            cookie      : true,
            xfbml       : true
        });

        // Additional initialization code such as adding Event Listeners goes here
        FB.Canvas.setAutoGrow();
		FB.Event.subscribe('edge.create',
			function() {
	            $.fancybox.close();
				top.location.href = window.fb.pageLink+'?v=app_'+window.fb.fbAppId;           
			}
		);
        FB.getLoginStatus(
            function(response)
            {
                if (response.authResponse) {
                    window.fb.accessToken     = response.authResponse.accessToken;
                    window.fb.signedRequest   = response.authResponse.signedRequest;
                }
            }
        );   
        $.fancybox.center = function() {
            FB.Canvas.getPageInfo( function( info ) {
                var left = Math.max( parseInt( $( window ).scrollLeft() ) + ( ( $( window ).outerWidth( true ) - $( '#fancybox-wrap' ).outerWidth( true ) ) / 2 ), 0 );
                var top = Math.max( parseInt( info.scrollTop ) + ( ( info.clientHeight - $( '#fancybox-content' ).outerHeight( true ) ) / 2 ) - parseInt( info.offsetTop ), 0 );
                if (top < 150){
                    top = 150;
                }
                $('#fancybox-wrap').css({'visibility':'visible'}).css( 'top', top + 'px' ).css( 'left', left + 'px' ).show();
            } );
        };
    };

    // Load the SDK asynchronously
    (function(d, debug){
        var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement('script'); js.id = id; js.async = true;
        js.src = "//connect.facebook.net/en_US/all" + (debug ? "/debug" : "") + ".js";
        ref.parentNode.insertBefore(js, ref);
    }(document, /*debug*/ false));
    
    $(function(){    
        // Added a user define function to facnybox loading cancel
        $.fancybox.userDefineCancel   = function(){ $('#page-overlay').hide();$('#fancybox-wrap').css({'visibility':'hidden'}); }

        //--------------- SITE OBJ START --------------------//
        window.site     = (window.site) ? window.site : {};
        _site           = window.site;
        _site.baseUrl   = '<?php echo Yii::app()->request->baseUrl; ?>';
        _site.platform  = 'desktop';
        // Loader
        _site.showFLoader   = function(){
            $('#page-overlay').show();
            $.fancybox.showActivity();
        }
        _site.hideFLoader   = function(){
            $('#page-overlay').hide();
            $.fancybox.hideActivity();
        }

    })
</script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', '', '');
  ga('send', 'pageview');

</script>
</body>
</html>
