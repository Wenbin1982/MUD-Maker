<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function show()
    {
        $data = User::all();
        return response($data);
    }

    public function isAdmin() {
        $data = User::find(Auth::id());
        return response($data);
    }

    public function update(Request $request) {
        $user =  User::find(Auth::id());
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->update();
        return response($user);
    }
}
