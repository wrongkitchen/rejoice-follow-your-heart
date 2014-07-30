<script>
    <?php
        $url = 'https://www.facebook.com/'.$this->fbConfig->fanspage_id.'?v=app_'.$this->fbConfig->fb_app_id;
        if ( $_GET ) {
            $url .= '&app_data=' . urlencode( base64_encode( json_encode($_GET ) ) );
        }
    ?>;
    top.location.href = '<?php echo $url?>';  
</script>
