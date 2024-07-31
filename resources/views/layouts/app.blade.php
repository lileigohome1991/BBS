<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', 'BBS') - 社区</title>
  <meta name="description" content="@yield('description', 'BBS 爱好者社区')" />

  <!-- Styles -->
  <link href="{{ mix('css/app.css') }}" rel="stylesheet">
  <link href="/dist/css/layui.css" rel="stylesheet">
  @yield('styles')
</head>
<body>
  <div id="app" class="{{ route_class() }}-page">

    @include('layouts._header')

    <div class="container">

      @include('shared._messages')

      @yield('content')

    </div>

    @include('layouts._footer')
  </div>

  <!-- Scripts -->
  <script src="{{ mix('js/app.js') }}"></script>
  @yield('scripts')
  <script src="https://cdn.bootcdn.net/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <script src="/dist/layui.js"></script>

<script>

layui.use('layim',function(layim){
	
	ws=new WebSocket("wss://limuyi.shop:9501?uid={{Auth::id()}}");
	ws.onopen=function(){
		console.log('connect  success......');
	}
	

	layim.config({
    brief:false,
    minRight:'300px',
		title:'家人们聊天',
		isgroup:false,
		copyright:true,
		init:{		
			"url":"{{ env('APP_URL') }}/getFriend",
			"type":"get",
			"data":{}
		},

		uploadImage:{
			url:"{{ env('APP_URL') }}/upload"
		},
		

		//以下为我们内置的模版，也可以换成你的任意页面。若不开启，剔除该项即可
		//   chatLog:  "{{ env('APP_URL') }}/chatlog"

	})

	$.get("/getFriend",function(res){
    res=JSON.parse(res)
		console.log('friends---:',res.data.friend.length);
		for(var i=0;i<res.data.friend.length;i++){
			for(var j=0;j<res.data.friend[i].list.length;j++){
				if(res.data.friend[i].list[j].status == 'online'){
					layim.setFriendStatus(res.data.friend[i].list[j].id, 'online');
				}else{
					layim.setFriendStatus(res.data.friend[i].list[j].id, 'offline');
				}
			}	
			//	res.data.friend[i].list[0];
		}
	});
	
	layim.on('sendMessage', function(res){
		console.log('send data--',res)
		 ws.send(JSON.stringify({
   			 type: 'chatMessage' //随便定义，用于在服务端区分消息类型
    			,data: res
 		 })); 
	})


	layim.on('sign', function(value){
		 console.log(value); //获得新的签名
  		$.post('/sign',{sign:value},function(res){
							
		})
 		 //此时，你就可以通过Ajax将新的签名同步到数据库中了。
	}); 

	//每次窗口打开或切换，即更新对方的状态
	// layim.on('chatChange', function(res){

	// 	$.post('/status',{uid:res.data.id},function(result){
	// 		//result=JSON.parse(result);
	// 		console.log(result);
	// 		if(result.status == 'online'){
    // 				layim.setChatStatus('<span style="color:#00ff00;">在线</span>'); //模拟标注好友在线状态
	// 		}else{

    // 				layim.setChatStatus('<span style="color:#dcdcdc;">离线</span>'); //模拟标注好友在线状态
	// 		}	
	// 	})
    // });

	layim.on('online', function(status){
  		console.log(status); //获得online或者hide
  		
		$.post('/online',{status:status},function(res){
								
		})
		
 		 //此时，你就可以通过Ajax将这个状态值记录到数据库中了。
  		//服务端接口需自写。
	}); 

		
	ws.onmessage=function(res){
		res=JSON.parse(res.data)
		if(res.type == 'friend'){
			layim.getMessage(res);	
		}
	}
	ws.onclose = function (e) {
		if(e.code=='1006'){
			ws=new WebSocket("wss://limuyi.shop:9501?uid={{Auth::id()}}");
		}else{
			console.log('websocket 断开: ' + e.code + ' ' + e.reason + ' ' + e.wasClean)
			console.log(e)

		}
		
	}

	
})


</script>

</body>

</html>