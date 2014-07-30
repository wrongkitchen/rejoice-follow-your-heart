window.fb   = {
    accessToken     : '',
    signedRequest   : '',
    pageId          : '<?php echo $this->fbConfig->fanspage_id; ?>',
    pageLink        : 'https://www.facebook.com/<?php echo $this->fbConfig->fanspage_id; ?>',
    fbAppId         : '<?php echo $this->fbConfig->fb_app_id; ?>',
    appScope        : '<?php echo $this->fbConfig->app_scope; ?>',
    inviteMsg       : {
                        'desktop'   : '<?php echo $this->fbConfig->invite_msg; ?>',
                        'mobile'    : '<?php echo $this->fbConfig->invite_msg_mobile; ?>'
                    },
    feeds           :{
                        <?php $feeds    = array();?>
                        <?php if ($desktopFeeds): ?>
                            <?php foreach($desktopFeeds as $_thisFeed): ?>
                                <?php 
                                    $feeds[]    = array(
                                        'name'              => $_thisFeed->feed_name,
                                        'caption'           => $_thisFeed->feed_caption,
                                        'description'       => $_thisFeed->feed_description,
                                        'action'            => $_thisFeed->feed_action,
                                        'link'              => $_thisFeed->feed_link,
                                        'picture'           => 'https://'.$_SERVER['HTTP_HOST'].Yii::app()->request->baseUrl.'/'.$_thisFeed->feed_picture,
                                    )
                                ?>
                            <?php endforeach;?>
                        <?php endif; ?> 
                        'desktop' : <?php echo json_encode($feeds);?>,
                        <?php $feeds    = array();?>
                        <?php if ($mobileFeeds): ?>
                            <?php $feeds    = array();?>
                            <?php foreach($mobileFeeds as $_thisFeed): ?>
                                <?php 
                                    $feeds[]    = array(
                                        'name'              => $_thisFeed->feed_name,
                                        'caption'           => $_thisFeed->feed_caption,
                                        'description'       => $_thisFeed->feed_description,
                                        'action'            => $_thisFeed->feed_action,
                                        'link'              => $_thisFeed->feed_link,
                                        'picture'           => 'https://'.$_SERVER['HTTP_HOST'].Yii::app()->request->baseUrl.'/'.$_thisFeed->feed_picture,
                                    )
                                ?>
                            <?php endforeach;?>
                        <?php endif; ?>
                        'mobile' : <?php echo json_encode($feeds);?>
                    }
}