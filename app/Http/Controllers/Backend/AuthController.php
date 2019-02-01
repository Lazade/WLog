<?php

/**
 * Created By Lazade with artisan
 * Created At 2018-10-29
 * */ 

namespace App\Http\Controllers\Backend;

use Socialite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\User;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{

    public function showLogin() 
    {
        return view('auth.login');
    }
    
    public function authenticate(Request $request) 
    {
        $post = $request->input();
        if (!array_key_exists('remember', $post)) {
            $post = array_merge($post, [
                'remember' => 'off',
            ]);;
        }
        if (Auth::attempt(['email' => $post['emailorname'], 'password' => $post['password']], $post['remember']) 
            || Auth::attempt(['name' => $post['emailorname'], 'password' => $post['password']], $post['remember'])
        ) {
            return redirect()->intended('dashboard');
        } else {
            $data = [
                'status' => 403,
                'info' => 'Incorrect Email/Name Or Password.'
            ];
            // return response()->json($data);
            return redirect()->back()->withInput()->withErrors($data['info']);
        }
    }

    public function check()
    {
        if (Auth::check()) {
            return ['auth' => 'Authenticated'];
        }
        return ['auth' => 'Unauthenticated'];
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    // google oauth

    public function redirectToGoogleProvider()
    {
        $parameters = ['access_type' => 'offline'];
        return Socialite::driver('google')->scopes(['https://www.googleapis.com/auth/drive'])->with($parameters)->redirect();
    }

    public function handleProviderGoogleCallBack()
    {
        
        try {
            $auth_user = Socialite::drive('google')->user();
        } catch (InvalidStateException $e) {
            $auth_user = Socialite::driver('google')->stateless()->user();
        }

        $token = $auth_user->token;
        $expiresAt = now()->addSeconds($auth_user->expiresIn);
        Cache::put('refresh_token', $token, $expiresAt);
        return redirect()->to('/avalon/drive');
    }

}
