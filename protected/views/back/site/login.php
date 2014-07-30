<div class="container row">
    <form id="loginForm" class="form-signin" onSubmit="return false;">
        <h2 class="form-signin-heading">Please Sign In:</h2>
        <input type="text" name="username" class="form-control" placeholder="Email address" autofocus>
        <input type="password" name="password" class="form-control" placeholder="Password">
        <button class="btn btn-lg btn-primary btn-block" type="submit" onClick="login();">Login</button>
    </form>
</div>
<div class="modal fade" id="loginErrorModal" tabindex="-1" role="dialog" aria-labelledby="loginErrorModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Errors:</h4>
            </div>
            <div class="modal-body">
                <p id="errorMessage" class="text-error"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
    function login()
    {
        $('#errorMessage').html('');
        $( '#loginForm' ).ajaxSubmit( {
            url         : '<?php echo $this->createUrl('Site/Login'); ?>',
            type        : 'post',
            dataType    : 'json',
            success: function( response ) {
                if ( response.status ) {
                    location.href = response.redirectUrl;
                } else {
                    $('#errorMessage').html(response.errorMessage);
                    $('#loginErrorModal').modal({
                        show        : true, 
                        backdrop    : 'static'
                    });
                }
            }
        } ); 
    }


</script>