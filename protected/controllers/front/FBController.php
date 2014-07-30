<?php
class FBController Extends FrontendController
{
    public $facebook;
    public $fbConfig;
	public $isMobile;
    public $signedData;
    public $accessToken;
    public $uid;
    public $me;
    public $appData;
    public $cmsData;
    
    public function init()
    {
        parent::init();
        $this->fbConfigInit();
        $this->facebook     = new Facebook(
                                    array(
                                        'appId' 		=> $this->fbConfig->fb_app_id,
                                        'secret' 		=> $this->fbConfig->fb_app_secret,
                                        'cookie'		=> true,
                                        'fileUpload' 	=> true
                                    )
                                );
        $this->accessToken  = ($_REQUEST['accessToken']) ? $_REQUEST['accessToken'] : '' ;
        $this->signedData   = ($_REQUEST['signed_request']) 
                            ? $this->parse_signed_request($_REQUEST['signed_request'], $this->fbConfig->fb_app_secret)
                            : array() ;
        $this->appData      = json_decode(base64_decode( $this->signedData['app_data']));
        if($this->accessToken){
            $this->facebook->setAccessToken($this->accessToken);
        }       
        $this->facebookDataInit();
        $this->layout   = 'fb';
    }
    
	public function actionRedirect()
	{
		$url	= 'https://apps.facebook.com/'.$this->fbConfig->app_namespace;
		header('Location:'.$url);
		die();
	}

    public function actionIndex()
    {
		$this->render('index');
//		$this->checkDevice();
//		if ($this->isMobile){
//			$this->redirect($this->createUrl('FB/MobileStart'));
//		}else{
//			$_SESSION['isMobile']   = false;
//			if (!$this->signedData['page']){
//				$this->notInTabRedirect();
//				die();
//			}
			
//			if($this->signedData['page']['liked']){
//				$this->render('afterLike');
//			}else{
//				$this->render('beforeLike');
//			}
//		}
    }
    
	public function actionDownloadCoupon()
	{
		if ($this->uid){
			$coupon  = Coupon::model()->find(' `uid` = :uid ',array(':uid' => $this->uid ));
			if (!$coupon){
				$coupon					= new Coupon;
				$coupon->uid			= $this->uid;
				$coupon->fb_name		= $this->me['name'];
				$coupon->create_time	= new CDbExpression('NOW()');
				if ($coupon->save()){
					$coupon->coupon_no	= str_pad($coupon->coupon_id, 6, '0', STR_PAD_LEFT).'-'.$coupon->uid;
					$coupon->update();
				}else{
					$this->redirect($this->createUrl('FB/index'));
				}
			}
			$basePath       = realpath(dirname(__FILE__).'/../../../').'/resource';
			$bgPath         = $basePath.'/images/coupon.jpg';
			$bgImg          = @imagecreatefromjpeg($bgPath);
			$font           = $basePath.'/fonts/msjh.ttf';
			$fontColor      = imagecolorallocate($bgImg, 0, 0, 0);
			imagettftext($bgImg, 11, 0, 50, 50, $fontColor, $font, $coupon->coupon_no);
//			$filepath       = 'images/uploaded/coupon/'.$coupon->coupon_no.'.jpg';
//			header('Content-type: image/jpeg');
			header('Content-Description: File Transfer');
			header('Content-Type: image/jpeg');
			header('Content-Disposition: attachment; filename=coupon.jpg');
			imagejpeg($bgImg);
			imagedestroy($bgImg);
		}else{
			$this->redirect($this->createUrl('FB/index'));
		}
	}
	

    public function actionMobileStart()
    {
        $this->layout = 'fbmobile';
        $this->render( 'mobile/start' );   
    }
    
    public function actionMobile()
    {
        $_SESSION['isMobile']   = true;
		$this->isMobile			= true;
        $this->layout           = 'fbmobile';
        $redirectUrl            = $this->createAbsoluteUrl('FB/Mobile'); 
//        $loginUrl               = 'https://m.facebook.com/dialog/oauth?client_id='.$this->fbConfig->fb_app_id.'&redirect_uri='.urlencode($redirectUrl).'&scope='.$this->fbConfig->app_scope.',user_likes&state='.md5(date('YmdHis'));
        $loginUrl               = 'https://m.facebook.com/dialog/oauth?client_id='.$this->fbConfig->fb_app_id.'&redirect_uri='.urlencode($redirectUrl).'&scope='.$this->fbConfig->app_scope.'&state='.md5(date('YmdHis'));
        if($_GET['code'] == null){
            $this->redirect($loginUrl);
        }else{
            if ($this->accessToken){
                try{
                    $accessTokenCallObj  = $this->facebook->api('/oauth/access_token_info?client_id='.$this->fbConfig->fb_app_id.'&access_token='.$this->accessToken);
                }catch(Exception $e){
                }
                if (!$accessTokenCallObj || !$accessTokenCallObj['expires_in']){
                    $_SESSION['accessToken']    = '';
                    $_SESSION['fbMe']           = '';
                    $this->redirect($loginUrl);
                    die();
                }
            }
            if (!$this->accessToken){
                $exchange   = 'https://graph.facebook.com/oauth/access_token?client_id='.$this->fbConfig->fb_app_id.'&redirect_uri='.urlencode($redirectUrl).'&client_secret='.$this->fbConfig->fb_app_secret.'&code='.$_GET['code'];
                $data       = @file_get_contents($exchange);
                parse_str($data,$data);
                $this->accessToken          = $data['access_token'];
                $_SESSION['accessToken']    = $data['access_token'];
            }
            $me                 = json_decode(@file_get_contents('https://graph.facebook.com/me?access_token='.$this->accessToken));
            $this->me           = $me;
            $_SESSION['me']     = $me;
            $uid                = $this->me->id;

            if (!$uid){
                $this->render('mobile/redirectPage',array('redirectUrl'=>$loginUrl));
                die();
            }
//            try{
//                $this->facebook->setAccessToken($this->accessToken);
//                $memberCallObj  = $this->facebook->api('/me/likes?fields=id&limit=5000' );
//            }catch(Exception $e){
//                $memberCallObj = array();
//            }
			$isLiked	= false;
//			if (is_array($memberCallObj)){
//				foreach($memberCallObj['data'] as $like){
//					if ( $like['id'] == $this->fbConfig->fanspage_id ) {
//						$isLiked = true;
//						break;
//					}
//				}
//			}
			$isLiked	= true;
            if(!$isLiked){   
                $this->render('mobile/beforeLike');
            }else{           
                $this->render('mobile/afterLike',array('thisForm'=>$thisForm));
            }
        }
       
    }
    
    public function actionSubmitForm()
    {
        $response           = array();
        $response['status'] = false;
        $errors             = array();
        if ($_POST){
            $thisFormCount                   = Form::model()->count('`fb_uid` = :uid',array(':uid'=>$this->uid));
			if ($thisFormCount > 0){
				$errors['rule'][]   = '每位用戶可參加一次。';
			}
            $thisForm                   = new Form();
            $thisForm->fb_uid           = $this->uid;
            $thisForm->fb_name          = $this->me['name'];
            $thisForm->name             = $_POST['name'];
            $thisForm->mobile           = $_POST['mobile'];
            $thisForm->email            = $_POST['email'];
            $thisForm->hkid				= $_POST['hkid'];
            $thisForm->mc_ans1			= $_POST['mc_ans1'];
            $thisForm->mc_ans2			= $_POST['mc_ans2'];
            $thisForm->mc_ans3			= $_POST['mc_ans3'];
            $thisForm->mc_ans4			= $_POST['mc_ans4'];
            $thisForm->mc_ans5			= $_POST['mc_ans5'];
            $thisForm->mc_ans6			= $_POST['mc_ans6'];
            $thisForm->mc_ans7			= $_POST['mc_ans7'];
            $thisForm->mc_ans8			= $_POST['mc_ans8'];
            $thisForm->edm              = ($_POST['edm'] == 1) ? 1 : 0;
            $thisForm->tnc              = ($_POST['tnc'] == 1) ? 1 : '' ;
            $thisForm->create_time      = new CDbExpression('NOW()');
            $status                     = $thisForm->validate();
            if($status && sizeof($errors) <= 0){
				$thisForm->save();
				$response['status']         = true;   
            }else{
                $modelErrors        = $thisForm->getErrors();
                foreach($modelErrors as $fieldName => $_thisError){
                    $errors[$fieldName] = $_thisError;
                }
                $response['errors'] = $errors;
            }

        }
        die(json_encode($response));
    }
    
    public function actionReferralRedirect()
    {
        $url = 'http://apps.facebook.com/' .$this->fbConfig->app_namespace;
        if ( $_GET ) {
            $url .= '?' . http_build_query( $_GET );
        }
        if ($this->isMobile){
            $this->createAbsoluteUrl('FB/MobileStart',$_GET);
        }
        header( 'Location:' . $url );
    }
    
   public function actionFBJSConfig()
    {
        header('Content-Type: application/javascript');
        $desktopFeeds    = FbFeedConfig::model()->findAll(' `fbconfig_id` = :fbconfig_id AND `platform` = :platform ORDER BY `fbfeed_id` ASC',array(':fbconfig_id' => $this->fbConfig->fbconfig_id,':platform'=>'desktop'));
        $mobileFeeds     = FbFeedConfig::model()->findAll(' `fbconfig_id` = :fbconfig_id AND `platform` = :platform ORDER BY `fbfeed_id` ASC',array(':fbconfig_id' => $this->fbConfig->fbconfig_id,':platform'=>'mobile'));
        $this->renderPartial('fbjsConfig',array('desktopFeeds'=>$desktopFeeds,'mobileFeeds'=>$mobileFeeds));
    }
  
    public function notInTabRedirect()
    {
        $this->render('notInTabRedirect');
    }
	
	public function checkDevice()
	{
	    $userAgent  = $_SERVER['HTTP_USER_AGENT'];
		if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$userAgent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($userAgent,0,4))){
            $this->isMobile = true;
        }else{
            $this->isMobile = false;
        }    
	}
    
    public function fbConfigInit()
    {
        $thisFbConfig    = FbConfig::model()->find(array('order'=>'`fbconfig_id` DESC'));
        if ($thisFbConfig){
            $this->fbConfig = $thisFbConfig;
        }else{
            die('Facebook Setting Is Not Initialized.');
        }
    }
    
    public function facebookDataInit()
    {
        $this->uid      = $this->facebook->getUser();
        if($this->uid){
            try {
                $this->me           = $this->facebook->api('/me');
                $this->accessToken  = $this->facebook->getAccessToken();
            } catch (Exception $exc) {
                $this->uid = null;
                $this->me  = array();
            }
        }else{
            $this->uid = null;
        }
    }
	
	private function parse_signed_request($signed_request) {
	  list($encoded_sig, $payload) = explode('.', $signed_request, 2); 
	  $sig = $this->base64_url_decode($encoded_sig);
	  $data = json_decode($this->base64_url_decode($payload), true);

	  return $data;
	}

	private function base64_url_decode($input) {
	  return base64_decode(strtr($input, '-_', '+/'));
	}
    
    
}

?>
