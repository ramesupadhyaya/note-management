<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.
        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->header('Authorization')) {
                if(strpos($request->header('Authorization'),'Bearer ',0) !== false) {
                    $key = str_replace('Bearer ', '', $request->header('Authorization'));
                    $current_timestamp = date('Y-m-d H:i:s');
                    $user = User::where([['api_token', $key], ['token_expired_at', '>', $current_timestamp]])->first();
                    if (!empty($user)) {
                        $expired_at = date('Y-m-d H:i:s', strtotime('+2 hours'));
                        User::where('api_token', $key)->update(['token_expired_at' => $expired_at]);// extending token expiration time
                        $request->request->add(['user_id' => $user->id]);
                    }
                    return $user;
                }
                return null;
            }
                return null;
            });
    }
}
