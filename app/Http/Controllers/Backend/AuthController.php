<?php

/**
 * Created By Lazade with artisan
 * Created At 2018-10-29
 * */ 

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{

    public function showLogin() 
    {
        return view('auth.login');
    }
    
    public function authenticate(Request $request) 
    {
        // dd($request);
        $post = $request->input();
        if (!array_key_exists('remember', $post)) {
            $post = array_merge($post, [
                'remember' => 'off',
            ]);;
        }
        // dd($post);
        // !$post['remember'] ? 'off' : 'on';
        if (Auth::attempt(['email' => $post['emailorname'], 'password' => $post['password']], $post['remember']) 
            || Auth::attempt(['name' => $post['emailorname'], 'password' => $post['password']], $post['remember'])
        ) {
            return redirect()->intended('dashboard');
            // return redirect('/avalon/');
        } else {
            $data = [
                'status' => 403,
                'info' => 'Incorrect Email/Name Or Password.'
            ];
            return response()->json($data);
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

}
