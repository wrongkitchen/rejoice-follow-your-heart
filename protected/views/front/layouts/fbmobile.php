<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=640" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <meta name="apple-mobile-web-app-capable" content="YES" />
        <meta name="apple-touch-fullscreen" content="YES" />
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="<?php echo $this->resourceUrl; ?>/css/fb/normalize.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->resourceUrl; ?>/css/fb/mobile_style.css?v=<?php echo date('YmdHis'); ?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->resourceUrl; ?>/css/fb/mobile_style_custom.css?v=<?php echo date('YmdHis'); ?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->resourceUrl; ?>/js/fancybox/jquery.fancybox-1.3.4.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->resourceUrl; ?>/js/bower_components/bootstrap-css/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $this->resourceUrl; ?>/js/bower_components/magnific-popup/dist/magnific-popup.css">
        <script type="text/javascript" language="javascript" src="<?php echo $this->resourceUrl; ?>/js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" language="javascript" src="<?php echo $this->resourceUrl; ?>/js/fancybox/jquery.fancybox-1.3.4.js"></script>
        <script type="text/javascript" language="javascript" src="<?php echo $this->resourceUrl; ?>/js/jquery.form.js"></script>
        <script type="text/javascript" language="javascript" src="<?php echo $this->resourceUrl; ?>/js/bower_components/magnific-popup/dist/jquery.magnific-popup.min.js"></script>
        <script type="text/javascript" language="javascript" src="<?php echo $this->createUrl('FB/FBJSConfig')?>"></script>
        <script type="text/javascript" language="javascript" src="<?php echo $this->resourceUrl; ?>/js/fb/fb.js"></script>
        <script type="text/javascript" language="javascript" src="<?php echo $this->resourceUrl; ?>/js/fb/main.js?v=<?php echo date('YmdHis'); ?>"></script>
        <script>
             <?php if($this->isMobile):?>
				$(function(){
					var deviceWidth = 0;
					deviceWidth = window.innerWidth;
					document.styleSheets[0].addRule(".FB_UI_Dialog", "width:"+deviceWidth+"px !important");

					$(window).bind('resize', function () {
						deviceWidth = window.innerWidth;
						document.styleSheets[0].addRule(".FB_UI_Dialog", "width:"+deviceWidth+"px !important");
					});

				});
			<?php endif; ?>
        </script>
    </head>
    <body class="mobile">
        <div id="page-overlay"></div>
        <div class="wrapper">
            <?php echo $content; ?>
            <div style="display:none;">
                <div id="tnc-popup" style="background:none;">
					TNC
                </div>
            </div>
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
//                FB.Canvas.setAutoGrow();
				FB.Event.subscribe('edge.create',
					function() {
                        window.site.showFLoader();
						top.location.href = '<?php echo $this->createUrl('FB/Mobile',$_GET); ?>';           
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
                $.fancybox.userDefineCancel   = function(){ $('#page-overlay').hide(); }
                // For mobile
                window.fb.accessToken   = '<?php echo $this->accessToken; ?>';
                //--------------- SITE OBJ START --------------------//
                window.site     = (window.site) ? window.site : {};
                _site           = window.site;
                _site.baseUrl   = '<?php echo Yii::app()->request->baseUrl; ?>';
                _site.platform   = 'mobile';
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
