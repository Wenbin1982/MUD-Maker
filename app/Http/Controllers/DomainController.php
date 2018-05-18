<?php

namespace App\Http\Controllers;

use App\Domain;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DomainController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function show()
    {
        $user = User::find(Auth::id());
        $domains = Domain::with('user')->get();
        return response()->json(['domains' => $domains, 'userCompany' => $user]);
    }

    public function store(Request $request) {
        $user = User::find(Auth::id());
        $domain = new Domain();
        $domain->domain = $request->domain;
        $domain->company = $request->company;
        $domain->user_id = Auth::id();
        $user->registration_company = null;
        $user->update();
        $domain->save();
        $domain->loadMissing('user');

        return $domain;
    }

    public function destroy(Request $request, $id) {
        Domain::find($id)->delete();
        return response()->json(['domains' => Domain::with('user')->get()]);

    }
}
