<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Activity;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user','roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate(['role_id' => 'required|exists:roles,id']);

        $user->update(['role_id' => $request->role_id]);

        return redirect('/admin/users')->with('success','Role updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect('/admin/users')->with('success','User deleted successfully.');
    }

    public function suspend(User $user)
    {
        $user->is_suspended = true;
        $user->save();

        return redirect()->back()->with('success', 'User has been suspended.');
    }

    public function activate(User $user)
    {
        $user->is_suspended = false;
        $user->save();

        return redirect()->back()->with('success', 'User has been reactivated.');
    }
}
