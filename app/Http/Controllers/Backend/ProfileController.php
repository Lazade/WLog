<?php 

/**
 * Created By Lazade with artisan
 * Created At 2018-10-29
 * */ 

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class ProfileController extends Controller
{
    public function profile()
    {
        $old_email = Auth::user()->email;
        $old_username = Auth::user()->name;
        return view('backend.profile', compact('old_email', 'old_username'));
    }

    public function resetPassword(Request $request)
    {
        if (!Auth::check()) {
            return redirect('avalon/profile')->withInput()->withErrors('Auth FAIL! Please Operate it After login');
        }
        $password = $request->get('password');
        $rules = [
            'password'=>'required|between:5,20|confirmed',
        ];
        $messages = [
            'required' => 'Password should not be empty',
            'between' => 'length must be between 5 to 20',
            'confirmed' => '"Confirm" does not match "Password"'
        ];
        $validator = $this->validate($request, $rules, $messages);
        $user_auth = Auth::user();
        $user_auth->password = bcrypt($password);
        $user_auth->save();
        Auth::logout();
        return redirect('/avalon');
    }

    public function resetEmail(Request $request)
    {
        if (!Auth::check()) {
            return redirect('avalon/profile')->withInput()->withErrors('Auth FAIL! Please Operate it After login');
        }
        $email = $request->get('email');
        $rules = [
            'email' => 'required',
        ];
        $messages = [
            'required' => 'Email should not be empty',
        ];
        $validator = $this->validate($request, $rules, $messages);
        $user_auth = Auth::user();
        $user_auth->email = $email;
        $user_auth->save();
        Auth::logout();
        return redirect('/avalon');
    }

    public function resetUsername(Request $request)
    {
        if (!Auth::check()) {
            return redirect('avalon/profile')->withInput()->withErrors('Auth FAIL! Please Operate it After login');
        }
        $username = $request->get('username');
        $rules = [
            'username' => 'required',
        ];
        $messages = [
            'required' => 'Username should not be empty',
        ];
        $validator = $this->validate($request, $rules, $messages);
        $user_auth = Auth::user();
        $user_auth->name = $username;
        $user_auth->save();
        Auth::logout();
        return redirect('/avalon');
    }
}