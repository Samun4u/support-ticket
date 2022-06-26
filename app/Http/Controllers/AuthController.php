<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponser;
class AuthController extends Controller
{

  
    use ApiResponser;
    public function register(Request $request)
    {
        $validatedData= $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

   

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']) ,
        ]);

        return $this->success([
            'token' => $user->createToken($validatedData['email'])->plainTextToken
        ]);


    }

    public function login(Request $request)
    {
        $validatedData= $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);

        if(!Auth::attempt($validatedData))
        {
            return response()->json('Not Match');
        }

        return $this->success([
            'token' =>auth()->user()->createToken($validatedData['email'])->plainTextToken
        ]);
    }


    public function users()
    {
       $users = User::all();

       return $this->success([
        $users
       ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return $this->success([
             "Successfully Logout" 
        ]);
    }
}
