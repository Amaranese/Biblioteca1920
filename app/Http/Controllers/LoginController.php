<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \Firebase\JWT\JWT;

class LoginController extends Controller
{
    public function login()
    {
        $user = User::where('email', $_POST['email'])->first();

        if (empty($_POST['email']) || empty($_POST['password'])) {
            return response()->json([
                'MESSAGE' => 'Some fields are empty'], 400
            );
        }
        
        if (!is_null($user)) 
        {
            if (decrypt($user->password) != $_POST['password']) {
                return response()->json([
                    'MESSAGE' => 'Wrong password'], 400
                );
            }
            $tokenParams = [
                'id' => $user->id,
                'password' => $_POST['password'],
                'email' => $_POST['email'],
            ];
            $token = JWT::encode($tokenParams, $this->key);
            return response()->json([
                'MESSAGE' => $token,
            ]);
        }else 
        {
            return response()->json([
                'MESSAGE' => 'Wrong email'], 400
            );
        }
    }
}
