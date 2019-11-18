<?php

namespace App\Http\Controllers;

use App\Password;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \Firebase\JWT\JWT;

class PasswordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $header = getallheaders();
        if (!empty($header['Authorization'])) {
            $userLogged = JWT::decode($header['Authorization'], $this->key, array('HS256'));
            $userPasswords = Password::where('user_id', $userLogged->id)->get();
            if (count($userPasswords) > 0) {
                return response()->json([
                    'MESSAGE' => $userPasswords], 200
                );
            }
            return response()->json([
                'MESSAGE' => 'Dont have any password created yet'], 404
            );
        }else{
            return response()->json([
                'MESSAGE' => 'You dont have enough permission'], 403
            );
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $header = getallheaders();
        if (!empty($header['Authorization'])) {
            $userLogged = JWT::decode($header['Authorization'], $this->key, array('HS256'));
            $password = New Password();
            $password->title = $request->title;
            $passwords = Password::all();
            foreach ($passwords as $key => $value) {
                if (decrypt($value->password) == $request->password && $userLogged->id == $value->user_id) {
                    return response()->json([
                        'MESSAGE' => 'The specified password is already in use'], 400
                    );
                }
            }
            $password->password = encrypt($request->password);
            $password->user_id = $userLogged->id;
            $selectedCategory = Category::where('name', $request->category_name)->first();
            if (!empty($selectedCategory)) {
                $password->category_id = $selectedCategory->id;
             } 
             $password->save();
             return response()->json([
                'MESSAGE' => 'The password has been created correctly'], 200
            );
        }else{
            return response()->json([
                'MESSAGE' => 'Dont have enough permission'], 403
            );
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Password  $password
     * @return \Illuminate\Http\Response
     */
    public function show(Password $password)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Password  $password
     * @return \Illuminate\Http\Response
     */
    public function edit(Password $password)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Password  $password
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Password $password)
    {
        $header = getallheaders();
        if (!empty($header['Authorization'])) {
            $userLogged = JWT::decode($header['Authorization'], $this->key, array('HS256'));
            if (empty($request->title) || empty($request->password)) {
                return response()->json([
                    'MESSAGE' => 'You have to change the title and password'], 400
                );
            }

            $password->title = str_replace(' ', '', $request->title);
            $userPasswords = Password::where('user_id', $userLogged->id)->get();
            if (count($userPasswords) >= 0) {
                foreach ($userPasswords as $key => $value) {
                    if (decrypt($value->password) == $request->password) {
                        return response()->json([
                            'MESSAGE' => 'The password is already in use'], 400
                        );
                    }
                }
            }
            if ($userLogged->id != $password->user_id) {
                return response()->json([
                    'MESSAGE' => 'Dont have enough permission'], 403
                );
            }
            $password->password = encrypt($request->password);
            $password->save();
            return response()->json([
                'MESSAGE' => 'The password has been updated correctly'], 200
            );

        }else{
            return response()->json([
                'MESSAGE' => 'Dont have enough permission'], 403
            );
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Password  $password
     * @return \Illuminate\Http\Response
     */
    public function destroy(Password $password)
    {
        $header = getallheaders();
        if (!empty($header['Authorization'])) {
            $userLogged = JWT::decode($header['Authorization'], $this->key, array('HS256'));
            $password->delete();
            return response()->json([
                'MESSAGE' => 'The password has been deleted correctly'], 200
            );
            
        }else{
            return response()->json([
                'MESSAGE' => 'Dont have enough permission'], 403
            );
        }
    }
}
