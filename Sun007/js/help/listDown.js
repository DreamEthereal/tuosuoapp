var mask = mui.createMask();

//		$(".mui-backdrop").on('mousemove',function(){
//			console.log(1)
//		})	
$('body').click(function(e){
	var target = $(e.target);
	if(target.closest('.list-down').length == 0){
		$(".list-down").removeClass('mui-active');	
		mask.close();
	}
})
$(".list-down").on("tap",function(){
	if($(".list-down").hasClass('mui-active')){
		mask.close();
	}else{
		mask.show();
	}
})
$(".list-up p").each(function(){			
	$(this).on("click",function(){
		var a =	$(this).html();
		console.log(a);
		$("#where").html(a);
		$(".list-down").removeClass('mui-active');
		mask.close();				
	})	
})
$(".list-up4 .up4-one-right li").each(function(){			
	$(this).on("click",function(){
		var a =	$(this).html();
		console.log(a);
		$("#other").html(a);
		$(".list-down").removeClass('mui-active');
	})	
})
$(".list-up4 .up4-two-right li").each(function(){			
	$(this).on("click",function(){
		var a =	$(this).html();
		console.log(a);
		$("#other").html(a);
		$(".list-down").removeClass('mui-active');
	})	
})