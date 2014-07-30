window.fb               = (window.fb) ? window.fb : {};
window.fb.inviteFriend  = function(){
	var message = (window.site.platform == 'desktop') ? window.fb.inviteMsg.desktop : window.fb.inviteMsg.mobile;
	FB.ui(
		{ 
			method  : 'apprequests', 
			message : message
		},
		function(response)
		{
			if (response && response.request){
			}else{
			}
		}
	);
}
window.fb.shareByObject  = function (obj, sCallback, cCallback){
    FB.ui(
        {
            method          : 'feed',
            name            : obj.name,
            caption         : obj.caption,
            description     : obj.description,
            link            : obj.link,
            picture         : obj.picture,
            actions         : [{
                name : obj.action,
                link : obj.link 
            }]
        },
        function(response) {
            if (response && response.post_id) {
                if (sCallback){
                    sCallback();           
                }
            } else {
                if (cCallback){
                    cCallback();           
                }
            }
        }
    );  
}
window.fb.fbLogin  = function (sCallback, fCallback){
    if (window.fb.accessToken != ''){
        sCallback();
    }else{
        FB.login(
            function(response) 
            {
                if (response.authResponse) {
                    window.fb.accessToken     = response.authResponse.accessToken;
                    window.fb.signedRequest   = response.authResponse.signedRequest;
                    if (sCallback){
                        sCallback();
                    }
                } else {
                    if (fCallback){
                        fCallback();
                    }
                }
            },{scope:window.fb.appScope}
        );
    }
}

