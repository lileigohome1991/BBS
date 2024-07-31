<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Handlers\ImageUploadHandler;

class PagesController extends Controller
{
//    public function index($token='')
//     {
// 		if($token==''){
// 		 return $this->redirect('/login');
// 		} 
// 		//$key=config('chat.key');
// 		//echo decrypt($token,$key);	
// 		//var_dump($token);
// 		$this->assign('token',$token);
//         	return  $this->fetch();
//     }
   public function getFriend(){
		// $redis=Cache::store('redis')->handler();
		$id=session()->get('id');
		$user_info=Redis::hgetall('user:'.$id);
		//var_dump($user_info);
		$mine=[
			'username'=>$user_info['username'],
            'email'=>$user_info['email'],
			'sign'=>$user_info['sign'],
			'avatar'=>$user_info['avatar'],
			'status'=>$user_info['status'],
			'id'=>$user_info['id'],
		];
		$keys=Redis::keys('user:*');
		static $count = 0;
    //    dd($keys);
		foreach($keys as $key){

            if($key=='user:'.$id || $key=='user:id'){
				continue;
			}
           
			if(Redis::hgetall($key)  && Redis::hgetall($key)['status']=='online'){
				$count++;
			}	
           
			
          
           
			$list['username']= 	Redis::hgetall($key)['username'];
			$list['id']= 	Redis::hgetall($key)['id'];
			$list['avatar']= 	Redis::hgetall($key)['avatar'];
			$list['sign']= 	Redis::hgetall($key)['sign'];
			$list['status']= 	Redis::hgetall($key)['status'];
			$friend['list'][] = $list;
		}
       
		$friend['groupname'] =  '来者皆友';
        	$friend['id'] =  '1';
        	$friend['online'] = $count;
	
		$data['code'] = 0;
		$data['msg'] ='';	
		$data['data']['mine']=$mine ;
		$data['data']['friend'][0]=$friend ;
		return json_encode($data);
  }



  public function upload(Request $request,ImageUploadHandler $uploader){
	// $file = request()->file('file');
    // 	// 移动到框架应用根目录/uploads/ 目录下
    // 	$info = $file->move( 'uploads');
	// //var_dump($info->getSaveName());

	// return json_encode(['code'=>0,'msg'=>'success','data'=>['src'=>'/uploads/'.$info->getSaveName()]]);

     // 初始化返回数据，默认是失败的
     $data = [
        'data'   => [],
        'msg'       => '上传失败!',
        'code' => ''
    ];
    // 判断是否有上传文件，并赋值给 $file
    if ($file = $request->file) {
        // 保存图片到本地
        $result = $uploader->save($file, 'chat', \Auth::id());
        // 图片保存成功的话
        if ($result) {
            $data['data'] = ['src'=>$result['path']];
            $data['msg']       = "success";
            $data['code']   = 0;
        }
    }

    return json_encode($data);
    // return $data;

  }





  public function chatlog(Request $request){
	 $to_id=$request->id;
	 $from_id=session()->get('id');
	 $arr=[$to_id,$from_id];
	 sort($arr);

	//  $redis=Cache::store('redis')->handler();
	 $data=['code'=>0,'msg'=>'','data'=>''];
     if(Redis::exists("chat:{$arr[0]}:{$arr[1]}")){
		$chats=Redis::lrange("chat:{$arr[0]}:{$arr[1]}",0,-1);
		//var_dump($chats);
		foreach($chats as $chat){
			$msg[]=json_decode($chat,true);
		}
		$data=['code'=>0,'msg'=>'','data'=>$msg];
		
	}

	//$this->assign('chat',json_encode($data));
	
	return view('pages.chatlog', compact(['chat'=>json_encode($data)]));
  } 

  public function sign(Request $request){
  	$sign=$request->sign;
	 $user_id=session()->get('id');
	
	Redis::hmset("user:{$user_id}",['sign'=>$sign]);		
  }

  public function status(Request $request){
	$uid=$request->uid;
    // $redis=Cache::store('redis')->handler();
	$res=Redis::hmget("user:$uid",['status']);
	return json_encode($res);		
  }
  public function online(Request $request){
  	$status= $request->status;
	
	$uid=session()->get('id');
	
	// $redis=Cache::store('redis')->handler();
	// $redis->hMset("user:$uid",['status'=>$status]);
	Redis::hmset("user:$uid",['status'=>$status]);
	
  }
}