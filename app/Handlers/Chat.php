<?php

namespace App\Handlers;

use GatewayWorker\Lib\Gateway;
use App\Services\WorkerService;

class Chat{
	public static function onMessage($client_id,$data){
		$data=json_decode($data,true);
		//var_dump($data);
		if($data['type']==='chatMessage'){
			//***********拼接  layim  需要的接受消息格式
			$info['username'] = $data['data']['mine']['username'];
			$info['avatar'] = $data['data']['mine']['avatar'];
			$info['id'] = $data['data']['mine']['id'];
			$info['type'] = $data['data']['to']['type'];
			$info['content'] = $data['data']['mine']['content'];
			$info['mine'] = false;
			$info['fromid'] = $data['data']['mine']['id'];
			$info['timestamp'] = time()*1000;

			//***********存聊天记录
			//拼接layim 需要的格式  可以看看  cahtlog.html  中的res 数据
			$chat_log=$data['data']['mine'];
			unset($chat_log['mine']);
			$chat_log['timestamp']=time()*1000;
			
			//聊天记录存储规则   chat:1:2=>['','','']
			$redis = Cache::store('redis')->handler();
			$from_id=$data['data']['mine']['id'];
			$to_id=$data['data']['to']['id'];
			
			//if($redis->exists("chat:{$to_id}:{$from_id}")){
			//	$redis->rPush("chat:{$to_id}:{$from_id}",json_encode($chat_log));	
			//}else{
			//	$redis->rPush("chat:{$from_id}:{$to_id}",json_encode($chat_log));	
			//}

			//第二种设置redis键的方式，，永远把id最小的放在前面
			$arr=[$from_id,$to_id];
			sort($arr);
			$redis->rPush("chat:{$arr[0]}:{$arr[1]}",json_encode($chat_log));	

			Gateway::sendToUid($data['data']['to']['id'],json_encode($info));		
			
		}
	}

	public static function onWebSocketConnect($client_id,$data){
		var_dump($data);
		// $uid=decrypt($data['get']['token'],config('chat.key'));
		Gateway::bindUid($client_id,$uid);
	}
	
	
}
