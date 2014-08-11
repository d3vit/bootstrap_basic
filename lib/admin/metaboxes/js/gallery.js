jQuery( document ).ready( function($) {

	pID = $("#post_ID").val();
	
	$(".rw-upload-button").live("click",function(){
		tb_show("", "media-upload.php?post_id="+pID+"&type=image&TB_iframe=true");
	    return false;
	});
	
	$(".eachthumbs").live("click",function(){
		tb_show("", "media-upload.php?post_id="+pID+"&tab=gallery&TB_iframe=true");
		return false;
	}); 
	
});