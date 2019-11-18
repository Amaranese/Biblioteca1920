<?php

namespace App\Http\Controllers;

use App\Category; 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use \Firebase\JWT\JWT;

class CategoryController extends Controller
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
            $userCategories = Category::where('user_id', $userLogged->id)->get();
            if (count($userCategories) > 0) {
                return response()->json([
                    'MESSAGE' => $userCategories], 200
                );
            }
            return response()->json([
                'MESSAGE' => 'Dont have any category created yet'], 404
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
            if (empty($request->name)) {
                return response()->json([
                    'MESSAGE' => 'You have to put a name for your category'], 400
                );
            }
            $repeatedCategory = Category::where('name', $request->name)->first();
            if (!is_null($repeatedCategory) && $repeatedCategory->user_id == $userLogged->id) {
                return response()->json([
                    'MESSAGE' => 'The specified category name already exists'], 400
                );
            }
            $category = new Category();
            $category->name = str_replace(' ', '', $request->name);
            $category->user_id = $userLogged->id;
            $category->save();
            return response()->json([
                'MESSAGE' => 'The category has been created correctly'], 200
            );
        }else{
            return response()->json([
                'MESSAGE' => 'You dont have enough permission'], 403
            );
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $header = getallheaders();
        if (!empty($header['Authorization'])) {
            $userLogged = JWT::decode($header['Authorization'], $this->key, array('HS256'));
            if (empty($request->name)) {
                return response()->json([
                    'MESSAGE' => 'You have to change the category name'], 400
                );
            }
            $userCategories = Category::where('user_id', $userLogged->id)->get();
            if (count($userCategories) == 0) {
                return response()->json([
                    'MESSAGE' => 'Dont have enough permission'], 403
                );
            }
            foreach ($userCategories as $key => $value) {
                if ($value->name == $request->name) {
                    return response()->json([
                        'MESSAGE' => 'The specified category name is already created'], 400
                    );
                }
                if ($value->user_id == $userLogged->id) {
                    $category->name = str_replace(' ', '', $request->name);
                    $category->save();
                    return response()->json([
                        'MESSAGE' => 'The category has been updated correctly'], 200
                    );
                }else{
                    return response()->json([
                        'MESSAGE' => 'Dont have enough permission'], 403
                    );
                }
            }
        }else{
            return response()->json([
                'MESSAGE' => 'Dont have enough permission'], 403
            );
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $header = getallheaders();
        if (!empty($header['Authorization'])) {
            $userLogged = JWT::decode($header['Authorization'], $this->key, array('HS256'));
            $userCategories = Category::where('user_id', $userLogged->id)->get();
            if (count($userCategories) == 0) {
                return response()->json([
                    'MESSAGE' => 'Dont have enough permission'], 403
                );
            }
            foreach ($userCategories as $key => $value) {
                if ($value->user_id == $userLogged->id) {
                    $category->delete();
                    return response()->json([
                        'MESSAGE' => 'The category has been deleted correctly'], 200
                    );
                }else{
                    return response()->json([
                        'MESSAGE' => 'Dont have enough permission'], 403
                    );
                }
            }
        }else{
            return response()->json([
                'MESSAGE' => 'Dont have enough permission'], 403
            );
        }
    }
}
