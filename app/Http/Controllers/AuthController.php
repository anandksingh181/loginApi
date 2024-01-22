<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;


class AuthController extends Controller
{

    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if($validator->fails()){
             $response = [
                'success' => false,
                 'massage'=> $validator->errors()
             ];
             return response()->json($response,400);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name'] =  $user->name;

        $response = [
            'success' => true,
             'data'=>$success,
             'massage'=> 'user register successfully'
         ];

         return response()->json($response,200);

    }
    public function login(Request $request): JsonResponse
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['name'] =  $user->name;


        $response = [
            'success' => true,
             'data'=>$success,
             'massage'=> 'user login successfully'
         ];
         return response()->json($response,200);
        }
        else{
            $response = [
                'success' => false,
                 'massage'=> 'Unauthorised'
             ];
             return response()->json($response);
        }
    }
}
