<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Redis;
use DB;
use Auth;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    
    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }
         //********登陆成功后 ，编写layim逻辑
         $username=Auth::user()->name;
         if(Redis::exists($username)){
            $user_id=Redis::get($username);
            // $pwd=Redis::hMget('user:'.$user_id,['password']);
            session()->put('id', $user_id);
            // $key = config('app.key');
            // $chat_token=encrypt($user_id,$key);
            // return json(['status'=>'success','token'=>$token]);
         }
               

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }



    // protected function sendLoginResponse(Request $request)
    // {
    //     $request->session()->regenerate();

    //     $this->clearLoginAttempts($request);

    //     if ($response = $this->authenticated($request, $this->guard()->user())) {
    //         return $response;
    //     }
    //     return $request->wantsJson()
    //                 ? new JsonResponse([], 204)
    //                 : redirect()->intended($this->redirectPath());
    // }

   


}
