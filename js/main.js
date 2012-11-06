
var chaton = false; var lookforchat = true;

$(window).unload(function () {
    $.ajax({
		async: false,
		type:'POST',
		url:'src/closeChat.php'
	});
});



$(document).ready(function(){

	

	
	$('.submitmsg').click(function(){
		
		if(chaton){
			
			$('.chat').append("<li>You: " + $('.msg').val());
			
			$.ajax({
				type:'POST',
				url:'src/newMessage.php',
				data: {msg: $('.msg').val()}
			});
			//$('.msg').val('');
			
		}
		
	});
	
	$('.stopchat').click(function(){
		
		$.ajax({
			type:'POST',
			url:'src/closeChat.php'
		});
		alert('disconnected from chat');
		lookforchat = false;
		
	});
	
	$('.imgupload').upload({
		name: 'file',
		action: 'src/imgupload.php',
		enctype: 'multipart/form-data',
		params: {},
		autoSubmit: true,
		onSubmit: function() {},
        onComplete: function() {
			alert("image uploaded!");
		}
	});
	
	$('.fblogin').click(function(){
	
		FB.login(function(response){
		
			if(response.authResponse){
				$.ajax({
					type: 'POST',
					url: 'src/fblogin.php'
				});
			}
		
		});
	
	});
	
	function pict(msg){
	
		if(msg.substring(0,4) == 'img:'){
			msg = "<img class='uimg' src='" + "src/upload/" + msg.substring(4,msg.length) + "' />";
		}
		return msg;
	
	}
	
	(function checkEvents(){
		//alert('a');
		/*if(!lookforchat || !ready){
			return 1;
		}*/
		//alert('f');
		
		if(!lookforchat){
			setTimeout(checkEvents, 2000);
			return;
		}
		
		$.ajax({
		type:'POST',
		dataType: 'JSON',
		url: 'src/handler.php',
		error: function(dat){
			//alert(dat + ' error');
		},
		success: function(data) {
			//alert(data.chat);
			//$('.chat').append("<li>Call done");
			if(data.messages){
				var i;
				for(i=0; i <data.messages.length;i++){
					var msg;
					msg = pict(data.messages[i].msg);
					$('.chat').append("<li>Other guy: " + msg);
				}
				//$('.chat').append("<li>----------------");
			}
			if(chaton != data.chat){
				if(data.chat){
					alert('connected to chat');
					chaton = true;
					lookforchat = true;
				}else{
					alert('disconnected from chat');
					chaton = false;
					lookforchat = false;
				}
			}
			setTimeout(checkEvents, 2000);
		}
			
		});
		
	}()); //setTimeout(checkEvents, 2000);
	
	//setInterval(checkEvents, 2000);
	

});