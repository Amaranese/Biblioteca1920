<?php

namespace App\Http\Controllers;

use App\Libro;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use \Firebase\JWT\JWT;
class LibroController extends Controller
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
            $userLibros = Libro::where('user_id', $userLogged->id)->get();
            if (count($userLibros) > 0) {
                return response()->json([
                    'MESSAGE' => $userLibros], 200
                );
            }
            return response()->json([
                'MESSAGE' => 'Dont have any book created yet'], 404
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
                    'MESSAGE' => 'You have to put a name for your book'], 400
                );
            }
            $repeatedBook = Book::where('name', $request->name)->first();
            if (!is_null($repeatedBook) && $repeatedBook->user_id == $userLogged->id) {
                return response()->json([
                    'MESSAGE' => 'The specified book name already exists'], 400
                );
            }
            $book = new Book();
            $book->name = str_replace(' ', '', $request->name);
            $book->user_id = $userLogged->id;
            $book->save();
            return response()->json([
                'MESSAGE' => 'The book has been created correctly'], 200
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
     * @param  \App\Libro  $libro
     * @return \Illuminate\Http\Response
     */
    public function show(Libro $libro)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Libro  $libro
     * @return \Illuminate\Http\Response
     */
    public function edit(Libro $libro)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Libro  $libro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Libro $libro)
    {
        $header = getallheaders();
        if (!empty($header['Authorization'])) {
            $userLogged = JWT::decode($header['Authorization'], $this->key, array('HS256'));
            if (empty($request->name)) {
                return response()->json([
                    'MESSAGE' => 'You have to change the libro name'], 400
                );
            }
            $userLibros = Libro::where('user_id', $userLogged->id)->get();
            if (count($userLibros) == 0) {
                return response()->json([
                    'MESSAGE' => 'Dont have enough permission'], 403
                );
            }
            foreach ($userLibros as $key => $value) {
                if ($value->name == $request->name) {
                    return response()->json([
                        'MESSAGE' => 'The specified book name is already created'], 400
                    );
                }
                if ($value->user_id == $userLogged->id) {
                    $book->name = str_replace(' ', '', $request->name);
                    $book->save();
                    return response()->json([
                        'MESSAGE' => 'The book has been updated correctly'], 200
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
     * @param  \App\Libro  $libro
     * @return \Illuminate\Http\Response
     */
    public function destroy(Libro $libro)
    {
         $header = getallheaders();
        if (!empty($header['Authorization'])) {
            $userLogged = JWT::decode($header['Authorization'], $this->key, array('HS256'));
            $userLibros = Libro::where('user_id', $userLogged->id)->get();
            if (count($userLibros) == 0) {
                return response()->json([
                    'MESSAGE' => 'Dont have enough permission'], 403
                );
            }
            foreach ($userLibros as $key => $value) {
                if ($value->user_id == $userLogged->id) {
                    $libro->delete();
                    return response()->json([
                        'MESSAGE' => 'The libro has been deleted correctly'], 200
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
