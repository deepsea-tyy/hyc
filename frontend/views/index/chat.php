<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <title>聊天框架</title>
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta content="telephone=no" name="format-detection">
    <link href="<?=Yii::getAlias('@web')?>/assets/chat/style.css" rel="stylesheet" type="text/css">
	<style type="text/css">
		input,button{outline:none;}
		.wenwen-footer{width:100%;position: relative;bottom:0px;left:0;background:#f2f0f0;padding:1px;border-top:solid 1px #ddd;box-sizing:border-box;}
		.wenwen_btn,.wenwen_help{width:50px; text-align:center;}
		.wenwen_btn img,.wenwen_help img{height:25px;}
		.wenwen_text{height:85px;border-radius:5px;border:solid 1px #636162;width:80%;text-align:left;overflow:auto;margin-left:3px;}
		.circle-button{padding:0 5px;}
		.wenwen_text .circle-button{font-size:14px;color:#666;line-height:38px;}
		.wenwen_help button{width:95%;background:lightgray;color:#000000;border-radius:5px;border:1;height:40px;display:none;}

		.speak_window{overflow-y:scroll;height:100%;width:100%;position:fixed;top:0;left:0;}
		.speak_box{margin-bottom:70px;padding:10px;}
		.question,.answer{margin-bottom:1rem;}
		.question{text-align:right;}
		.question div{display:inline-block;}
		.left{float:left;}
		.right{float:right;}
		.clear{clear:both;}
		.heard_img{height:40px;width:40px;border-radius:5px;overflow:hidden;background:#ddd;}
		.heard_img img{width:100%;height:100%}
		.question_text,.answer_text{box-sizing:border-box;position:relative;display:table-cell;min-height:25px; max-width:240px;}
		.question_text{padding-right:18px;}
		.answer_text{padding-left:18px;}
		.question_text p,.answer_text p{border-radius:10px;padding:.5rem;margin:0;font-size:14px;line-height:28px;box-sizing:border-box;vertical-align:middle;display:table-cell;height:40px;word-wrap:break-word;}
		.answer_text p{background:#fdd;}
		.question_text p{background:#42eeee;color:#000000;text-align:left;}
		.question_text i,.answer_text i{width:0;height:0;border-top:5px solid transparent;border-bottom:5px solid transparent;position:absolute;top:10px;}
		.answer_text i{border-right:10px solid #fdd;left:10px;}
		.question_text i{border-left:10px solid #42eeee;right:10px;}
		.answer_text p a{color:#000000;display:inline-block;}
	</style>
</head>
<body>
<!--  右上角菜单部分  -->
<section class="aui-flexView">
    <header class="aui-navBar">
        <a href="javascript:;">
            <i class="icon"></i>
        </a>
        <div class="aui-center">
            <span class="aui-center-title" id ='title'>会话列表</span>
        </div>
    </header>
	
	<!-- tab1 列表 -->
    <div class="aui-scrollView">
        <div class="aui-flex-list" id='chatlist'>
            <a href="javascript:;" class="aui-flex-list-item">
                <div class="aui-flex-list-image">
                    <img src="<?=Yii::getAlias('@web')?>/assets/chat/icon-logo07.png" alt="">
                    <span class="aui-badge"></span>
                </div>
                <div class="aui-flex-list-text">
                    <h4>张佳宁同学</h4>
                    <p class="aui-red-text">[语音]</p>
                </div>
                <span class="aui-flex-list-right">11:28</span>
                <span class="aui-flex-list-mute">
                    <i class="icon icon-mute"></i>
                </span>
            </a>
          
        </div>
    </div>
	
	<!-- tab2 列表 -->
    <div class="aui-scrollView">
		<div class="aui-flex-list" id='chatPanel'></div>

		<!--   输入框  -->
		<div class="wenwen-footer">
			<textarea  class="wenwen_text" type="text"   placeholder="请输入文本" ></textarea>

			<div class="wenwen_help right">
				<button class="right" style="display: inline-block;">发送</button>
			</div>
		</div>
	</div>
	
	
		
	<!--   下面部分是底部 -->
    <footer class="aui-footer">
        <a href="javascript:;" class="aui-tabBar-item aui-tabBar-item-active">
            <span class="aui-tabBar-item-icon">
                <i class="icon icon-mail"></i>
            </span>
            <span class="aui-tabBar-item-text">会话列表
            </span>
        </a>
        <a href="javascript:;" class="aui-tabBar-item ">
            <span class="aui-tabBar-item-icon">
                <i class="icon icon-find"></i>
            </span>
            <span class="aui-tabBar-item-text">聊天
            </span>
        </a>

    </footer>
</section>
<!--   下面部分结束 -->
		
<script type="text/javascript" src="<?=Yii::getAlias('@web')?>/assets/chat/jquery.js"></script>

<script type="text/javascript">
$(function() {
	var ws = new WebSocket("ws://127.0.0.1:8282?access-token=<?=$token?>");
    ws.onopen = function(){
		setInterval(show,3000);
	}
	function show(){
		ws.send(JSON.stringify({type:'heart'}));
	}	
  	ws.onConnect = function(e){

	}

	var avatar_cust = "<?=Yii::getAlias('@web')?>/assets/chat/cust.png";
	var avatar_buss = "<?=Yii::getAlias('@web')?>/assets/chat/icon-logo01.png";
	ws.onmessage = function(e){
		var res = eval("("+e.data+")");
		switch(res.type)
		{
			case 'bind':
			  $.post('http://api.hyc.com/test/bind?access-token=<?=$token?>',{uid:<?=$uuid?>,sid:res.data.sid},function (res) {
				console.log(res);
			  });
			  break;
			case 'wechat_applet_kefu'://接收消息
				var row = res.data;
				$("button").attr("data-platform",1);
				$("button").attr("data-touser",row.fromuser);
				$('#chatPanel').append('<div class="answer"><div class="heard_img left"><img src="'+ avatar_cust +'"/></div><div class="answer_text"><p><a>' + new Date(parseInt(row.created_at) * 1000).format("hh:mm") +'</a><br>' + row.content +'</p><i></i></div></div>');
			  break;
			case 'chat_message'://接收消息
				var row = res.data;
				$("button").attr("data-platform",0);
				$("button").attr("data-touser",row.fromuser);
				$('#chatPanel').append('<div class="answer"><div class="heard_img left"><img src="'+ avatar_cust +'"/></div><div class="answer_text"><p><a>' + new Date(parseInt(row.created_at) * 1000).format("hh:mm") +'</a><br>' + row.content +'</p><i></i></div></div>');
			  break;
			default:
				console.log(e.data);
		}
	}
	ws.onclose = function(e){
	 console.log(e);
	}

	$.post("http://api.hyc.com/chat/dialoguelist?access-token=<?=$token?>", {}, function (res) {
		if (res.status == 1) {
			$(res.data).each(function (i) {
				var row = res.data[i];
				$("#chatlist").append('<div class="aui-flex-list-item" data-fromuser="' + row.fromuser + '" data-touser="' + row.touser + '" data-avatar="' + row.avatar + '" data-platform="' + row.platform + '"><div class="aui-flex-list-image"><img src="' + row.avatar + '" alt=""></div><div class="aui-flex-list-text"><h4>' + row.nickname + '</h4><p>' + row.content + '</p></div><span class="aui-flex-list-right">' + new Date(parseInt(row.created_at) * 1000).format("hh:mm") + '</span></div>');
			});
		}
	});

	$(".aui-scrollView").each(function (i) {
		if (i == 0) {
			$(this).show();
		}else{
			$(this).hide();
		}
	});
	$(".aui-footer a").click(function () {
		var index = $(this).index();
		$("#title").text($(this).text());
		$(this).addClass("aui-tabBar-item-active").siblings().removeClass("aui-tabBar-item-active")
		$(".aui-scrollView").each(function (i) {
			if (i == index) {
				$(this).show();
			}else{
				$(this).hide();
			}
		});
	});
	$(document).on("click","#chatlist .aui-flex-list-item",function () {
		$(".aui-scrollView").hide();
		$("#chatPanel").parent().show();
		$("#title").text("聊天");
		$(".aui-tabBar-item").removeClass("aui-tabBar-item-active");
		$(".aui-tabBar-item").eq(1).addClass("aui-tabBar-item-active");
		var fromuser = $(this).data("fromuser");
		var avatar = $(this).data("avatar");
		var platform = $(this).data("platform");

		$("button").attr("data-platform",platform);
		$("button").attr("data-touser",fromuser);
		$('#chatPanel').html("");
		$.post("http://api.hyc.com/chat/dialoguemsg?access-token=<?=$token?>", {fromuser:fromuser}, function (res) {
			if (res.status == 1) {
				$(res.data).each(function (i) {
					var row = res.data[i];
					if (row.fromuser == fromuser) {
						$('#chatPanel').append('<div class="answer"><div class="heard_img left"><img src="'+ avatar_cust +'"/></div><div class="answer_text"><p><a>' + new Date(parseInt(row.created_at) * 1000).format("hh:mm") +'</a><br>' + row.content +'</p><i></i></div></div>');
					}else{
						$('#chatPanel').append('<div class="question"><div class="heard_img right"><img src="'+ avatar_buss +'"/></div><div class="question_text"><p><a>' + new Date(parseInt(row.created_at) * 1000).format("hh:mm") +'</a><br>' + row.content +'</p><i></i></div></div>');
					}
				});
			}
		})
	})
	$("button").click(function () {
		var content = $("textarea").val();
		var touser = $(this).data("touser");
		var platform = $(this).data("platform");
		if (platform == 1) {//发送微信
			$.post("http://api.hyc.com/chat/sendmsg?access-token=<?=$token?>", {touser:touser,content:content,type:1}, function (res) {
				if (res.status == 1) {
					$('#chatPanel').append('<div class="question"><div class="heard_img right"><img src="'+ avatar_buss +'"/></div><div class="question_text"><p><a>' + (new Date()).format("hh:mm")  +'</a><br>' + content +'</p><i></i></div></div>');
					$("textarea").val("");
				}
			});
		}else{//发送web
			$.post("http://api.hyc.com/chat/sendmsg?access-token=<?=$token?>", {touser:touser,content:content,type:1}, function (res) {
				if (res.status == 1) {
					$('#chatPanel').append('<div class="question"><div class="heard_img right"><img src="'+ avatar_buss +'"/></div><div class="question_text"><p><a>' + (new Date()).format("hh:mm")  +'</a><br>' + content +'</p><i></i></div></div>');
					$("textarea").val("");
				}
			});
		}

	});
});
Date.prototype.format = function(format)
{
	var o = {
	"M+" : this.getMonth()+1, //month
	"d+" : this.getDate(),    //day
	"h+" : this.getHours(),   //hour
	"m+" : this.getMinutes(), //minute
	"s+" : this.getSeconds(), //second
	"q+" : Math.floor((this.getMonth()+3)/3),  //quarter
	"S" : this.getMilliseconds() //millisecond
	}
	if(/(y+)/.test(format)) format=format.replace(RegExp.$1,
	(this.getFullYear()+"").substr(4 - RegExp.$1.length));
	for(var k in o)if(new RegExp("("+ k +")").test(format))
	format = format.replace(RegExp.$1,
	RegExp.$1.length==1 ? o[k] :
	("00"+ o[k]).substr((""+ o[k]).length));
	return format;
}
</script>
    

</body>
</html>