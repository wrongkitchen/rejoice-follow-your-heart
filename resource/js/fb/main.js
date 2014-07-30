$(document).ready(function(){

});
var activeDonwloadBtn = function(){
	$('#downloadBtn').css({'cursor':'pointer','background':'#EEE'}).click(function(){
		downloadCoupon();
	});
}

var downloadCoupon	= function(){
	window.fb.fbLogin(function(){
		var url        = window.site.baseUrl + '/FB/downloadCoupon?accessToken='+window.fb.accessToken;
		location.href  = url;
	},function(){});

}

var tncPopUp = function(){
    $.fancybox({
        modal   : true,
        href    : '#tnc-popup',
        padding : 0,
        centerOnScroll : true
    });
//    ga('send', 'pageview', '/tnc');
}

var errorPrompt    = function(msg){
	$.fancybox({
//		'modal':true,
//		'centerOnScroll':true,
        'width' : 407,
        'height' : 231,
		'padding':0,
        'autoDimensions' : false,
        'autoSize' : false,
        'fitToView' : false,
		'overlayOpacity':0.9,
		'content':'<div style="padding:20px;background:#FFF;border:2px solid #BBB"><a href="javascript:void(0)" onclick="$.fancybox.close()"><div class="closeBtn"></div></a><div>'+msg+'</div></div>'
	});
}
