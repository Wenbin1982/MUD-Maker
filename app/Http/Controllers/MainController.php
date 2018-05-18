<?php namespace App\Http\Controllers;

use App\Http\Requests\LogInRequest;
use App\MudFile;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class MainController extends Controller
{
    public function index()
    {
        if (Auth::check())
            return Redirect::to('/');

        return view('main.index');
    }

    public function showAdmin()
    {
        if (Auth::check()) {
            return response(User::find(Auth::id()));
        };
        return null;
    }

    public function app()
    {
        return view('main.index');
    }

}
