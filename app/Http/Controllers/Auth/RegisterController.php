<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'captcha' => ['required', 'captcha'],
        ],[
            'captcha.required' => '验证码不能为空',
            'captcha.captcha' => '请输入正确的验证码',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                //'chat_token'=> $chat_token,
            ]);

            $username=$data['name'];
            $password = Hash::make($data['password']);
            //$redis=Cache::store('redis')->handler();
            //redis 存用户信息   hash  user:1  key => ['username'=>'','id'=>1,'sign'=>'']
            $user_id = $user->id;
            Redis::set($username,$user_id);
            $user_info = [
                'username'=>$username,
                'email'=>$data['email'],
                'id'=>$user_id,
                'password'=>$password,
                'sign' => '多维好点，可以吗',
                'avatar'=>$user->avatar,
                'status'=>'online'		
            ];
            // $redis->hMset('user:'.$user_id,$user_info);
            Redis::hmset('user:'.$user_id,$user_info);
            session()->put('id',$user_id);
            $key = config('app.key');
    
            //$chat_token=encrypt($user_id,$key);
           // return json(['status'=>'success','token'=>$token]);

         
        

            return $user;
    }
}
