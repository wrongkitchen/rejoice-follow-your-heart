<?php
    Class MailHelper
    {
        public static function sendEmail($sender,$receiver,$subject,$content)
        {
//            $subject = "=?utf-8?B?" . base64_encode($subject) . "?=";
//            $headers  = 'MIME-Version: 1.0' . "\r\n";
//            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
//            $headers .= 'From: <'.$sender.'>' . "\r\n" .
//            'Reply-To: '.$sender;
//            mail($receiver, $subject, $content, $headers);    
            

                $mail = Yii::app()->Smtpmail;
                $mail->SetFrom($sender, 'From '.Yii::app()->params['emailSenderName']);
                $mail->Subject    = $subject;
                $mail->MsgHTML($content);
                $mail->AddAddress($receiver, "");
                if(!$mail->Send()) {
//                    echo "Mailer Error: " . $mail->ErrorInfo;
                }else {
//                    echo "Message sent!";
                }

        }
        
        
        public static function sendPInvoiceConfirmEmail($invId)
        {
            $thisPInvoice   = PorderInvoice::model()->findByPk($invId);
            if ($thisPInvoice){
                $langId             = $thisPInvoice->lang_id;
                $thisLang           = Lang::model()->findByPk($langId);
                $thisPBasket        = PorderBasket::model()->findByPk($thisPInvoice->pbasket_id);
                $thisInvoiceCoupon  = PorderInvoiceCoupon::model()->find('`inv_id` = :inv_id ',array(':inv_id' => $invId));
                $basePath           = realpath(dirname(__FILE__).'/../');
                $templatePath       = $basePath.'/views/front/mailTemplate/'.$thisLang->suffix.'/porder';
                $mainContent        = file_get_contents($templatePath.'/pOrderConfirm.php');
                $tableItemContent   = file_get_contents($templatePath.'/productTableItem.php');
                $tableCouponContent = file_get_contents($templatePath.'/productTableCoupon.php');
                $forWhoOption       = LangOptionHelper::getOption('forwho');
                $thisSize           = Size::model()->findByPk($thisPBasket->size_id);
                $thisColor          = Color::model()->findByPk($thisPBasket->color_id);
                $pItemThumb         = PitemImageList::model()->find(' `pitem_id` = :pitem_id AND `image_type` = :image_type ORDER BY `sort_order` ASC ',array(':pitem_id'=>$thisPBasket->pitem_id,':image_type'=>'thumbnail'));
                // Replace Content
                $tableItemContent   = str_replace('{{image_link}}','<img src="http://'.$_SERVER['HTTP_HOST'].Yii::app()->request->baseUrl.$pItemThumb->image_path.$pItemThumb->image_name.'" width="100" style="display:block"/>',$tableItemContent);
                $tableItemContent   = str_replace('{{product_name}}',$thisPBasket->{'pitem_name_'.$thisLang->suffix},$tableItemContent);
                $tableItemContent   = str_replace('{{color}}',$thisColor->color_code,$tableItemContent);
                $tableItemContent   = str_replace('{{for_who}}',$forWhoOption[$thisPBasket->for_who][$thisLang->suffix],$tableItemContent);
                $tableItemContent   = str_replace('{{size}}',$thisSize->size_name,$tableItemContent);
                $tableItemContent   = str_replace('{{price}}','HKD$ '.$thisPBasket->selling_price_hkd,$tableItemContent);
                $tableItemContent   = str_replace('{{qty}}',$thisPBasket->quantity,$tableItemContent);
                $tableItemContent   = str_replace('{{subtotal}}','HKD$ '.$thisPBasket->total_price_hkd,$tableItemContent);
                
                if ($thisInvoiceCoupon){
                    $thisCoupon         = Coupon::model()->findbyPk($thisInvoiceCoupon->coupon_id);
                    $tableCouponContent = str_replace('{{coupon_name}}',$thisCoupon->{'name_'.$thisLang->suffix},$tableCouponContent);
                }else{
                    $tableCouponContent = '';
                }
                
                $mainContent        = str_replace('{{inv_no}}',$thisPInvoice->inv_no,$mainContent);
                $mainContent        = str_replace('{{total_amount}}','HKD$ '.$thisPInvoice->inv_amount_hkd,$mainContent);
                $mainContent        = str_replace('{{product_table_item}}',$tableItemContent,$mainContent);
                $mainContent        = str_replace('{{product_table_coupon}}',$tableCouponContent,$mainContent);

                // Send Email
                $senderName     = Yii::app()->params['emailSender'];
                $subject        = Yii::app()->params['email']['preorder']['orderConfirm'][$thisLang->suffix];
                $subject        = str_replace('{{inv_no}}',$thisPInvoice->inv_no,$subject);
                $receiver       = $thisPInvoice->email;
                $emailContent   = $mainContent;
                MailHelper::sendEmail($senderName , $receiver, $subject , $emailContent);

            }
        }
    }
?>
