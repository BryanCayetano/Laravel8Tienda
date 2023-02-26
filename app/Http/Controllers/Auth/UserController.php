<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function makeAdmin(Request $request, $id)
    {
        $user = User::find($id);
        $user->admin = 1;
        $user->save();

        return redirect()->back()->with('success', 'Usuario ' . $user->name . ' ha sido promovido a administrador.');
    }
    
    public function removeAdmin(Request $request, $id)
    {
        $user = User::find($id);
        $user->admin = 0;
        $user->save();

        return redirect()->back()->with('success', 'Se ha eliminado el rol de administrador del usuario ' . $user->name . '.');
    }
}
