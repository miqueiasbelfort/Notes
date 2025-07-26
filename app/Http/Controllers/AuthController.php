<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'text_username' => 'required|email',
            'text_password' => 'required|min:6|max:16'
        ], [
            'text_username.required' => 'O username é obrigatorio',
            'text_username.email' => 'O username é um email',
            'text_password.required' => 'A senha é obrigatoria',
            'text_password.min' => 'A senha deve ter pelo menos :min caracteres',
            'text_password.max' => 'A senha deve ter no maximo :max caracteres',
        ]);

        $username = $request->get('text_username');
        $password = $request->get('text_password');

        $user = User::where('username', $username)->whereNull('deleted_at')->first();
        
        if(empty($user) || !password_verify($password, $user->password)){
            return redirect()->back()->withInput()->with('loginError', 'Username ou password incorretos.');
        }

        $user->last_login = date('Y-m-d H:i:s');
        $user->save();

        session([
            'user' => [
                'id' => $user->id,
                'username' => $user->username
            ]
        ]);

        return redirect('/');
    }   

    public function logout()
    {
        session()->forget('user');
        return redirect()->to('/login');
    }
}
