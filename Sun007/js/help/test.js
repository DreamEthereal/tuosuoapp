
function main(){
	var name = document.getElementById("name");
	var index = document.getElementById("indexName");
	var btnArray = ['取消', '确定'];
	index.addEventListener('tap', function(e) {		
		mui.prompt('请输入一个昵称', '', '', btnArray, function(e) {
			if (e.index == 1) {
				if(e.value == ''){
					mui.alert("昵称不能为空")
				}else{
					name.innerHTML = e.value;
				}				
			} else {
				name.innerHTML = name.innerHTML;
			}
		})
	});

	var phone = document.getElementById("phone");
	phone.addEventListener('tap', function(e) {
		var reg = /^1(3|4|5|7|8)\d{9}$/;
		mui.prompt('请输入您的手机号', '', '',btnArray,function(e) {
			if(e.index == 1){
				if(e.value == '') {
					mui.alert("请输入手机号")
				} else if(!reg.test(e.value)) {
					mui.alert('请输入正确的手机号')
				} else {
					phone.innerHTML = e.value;
				}
			}else{
				phone.innerHTML = phone.innerHTML;
			}
			
		})
	})

	var sex = document.getElementById("sex");
	var indexSex = document.getElementById("indexSex");
	indexSex.addEventListener('tap', function() {
		var btnArray = ['男', '女'];
		mui.confirm('请选择性别', '', btnArray, function(e) {
			if (e.index == 1) {
				sex.innerHTML = "女";
			} else {
				sex.innerHTML = "男";
			}
		})
	}); 

	var email = document.getElementById("email");
	email.addEventListener('tap',function(){
		var reg = /^[A-Za-z0-9\u4e00-\u9fa5]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/;;
		mui.prompt('请输入您的邮箱', '','',btnArray,function(e) {
			if(e.index == 1){
				if(e.value == '') {
					mui.alert("请输入邮箱")
				} else if(!reg.test(e.value)) {
					mui.alert('请输入正确的邮箱号')
				} else {
					email.innerHTML = e.value;
				}
			}else{
				email.innerHTML = email.innerHTML;
			}
			
		})
	})

	var job = document.getElementById("job");
	job.addEventListener('tap',function(){
		mui.prompt('输入您的职业', '','',btnArray,function(e) {
			if(e.index == 1){
				if(e.value == '') {
					mui.alert("请输入您的职业")
				} else {
					job.innerHTML = e.value;
				}
			}else{
				job.innerHTML = job.innerHTML;
			}
		})
	})

	var userName = document.getElementById("userName");
	userName.addEventListener('tap',function(){
		var reg = /[\u4e00-\u9fa5]{1,20}|[a-zA-Z\.\s]{1,20}/;
		mui.prompt('输入您的姓名', '','',btnArray,function(e) {
			if(e.index == 1){
				if(e.value == '') {
					mui.alert("请输入您的姓名")
				} else if(!reg.test(e.value)) {
					mui.alert('请输入正确的姓名')
				} else {
					userName.innerHTML = e.value;
				}
			}else{
				userName.innerHTML = userName.innerHTML;
			}
		})
	})

	var idCard = document.getElementById("idCard");
	idCard.addEventListener('tap',function(){
		var reg = /(^\d{15}$)|(^\d{17}([0-9]|X)$)/;
		mui.prompt('输入您的身份证号', '','',btnArray,function(e) {
			if(e.index == 1){
				if(e.value == '') {
					mui.alert("身份证号码不能为空")
				} else if(!reg.test(e.value)) {
					mui.alert('请输入正确的身份证号码')
				} else {
					idCard.innerHTML = e.value;
				}
			}else{
				idCard.innerHTML = idCard.innerHTML;
			}
		})
	})

	var address = document.getElementById("address");
	address.addEventListener('tap',function(){
		var reg = /[\u4e00-\u9fa5]{1,20}/; 				
		mui.prompt('请输入详细地址', '','',btnArray,function(e) {
			if(e.index == 1){
				if(e.value == '') {
					mui.alert("请输入地址")
				} else if(!reg.test(e.value)) {
					mui.alert('您的输入有误')
				} else {
					address.innerHTML = e.value;
				}
			}else{
				address.innerHTML = address.innerHTML;
			}
			
		})
	})
}
main();

