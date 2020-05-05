<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function authenticate(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        try{
            $user = User::where('email', $request->input('email'))->first();
            if(password_verify($request->input('password'),$user->password)){
                $apikey = base64_encode(Str::random(40));
                $expired_at = date('Y-m-d H:i:s',strtotime('+2 hours'));
                User::where('email', $request->input('email'))->update(['api_token' => "$apikey","token_expired_at"=>$expired_at]);
                return response()->json(['status' => 'success','api_key' => $apikey
                ]);
            }else{
                return response()->json(["Error"=>"your email or password don't match with our records!"],401);
            }
        }catch (\Exception $e){
            return response()->json(["Error"=>"your email or password don't match with our records!"],401);
        }
    }

    public function register(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|max:128|unique:users',
            'password' => 'required',
        ]);
        $name = $request->input('name');
        $email = $request->input('email');
        $password = password_hash($request->input('password'),PASSWORD_DEFAULT);
        $current_timestamp = date('Y-m-d H:i:s');
        $user_entry = ['name'=>$name,'email'=>$email,'password'=>$password,"created_at"=>$current_timestamp,
            "updated_at"=>$current_timestamp,"token_expired_at"=>$current_timestamp,'api_token'=>''];
        if(User::create($user_entry)){
              return  response()->json(['msg'=>$name . "! you have successfully signed up for an account, kindly login with your credintials now"]);
        }else{
            return response()->json(['msg'=>"sign up failed!"]);
        }
    }

}
