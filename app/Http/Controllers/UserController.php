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

        Activity::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'model' => 'User',
            'model_id' => $user->id,
            'description' => "Updated user by: {$user->name}"
        ]);

        return redirect('/admin/users')->with('success','Role updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        Activity::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'model' => 'User',
            'model_id' => $user->id,
            'description' => "Deleted user by: {$user->name}"
        ]);

        return redirect('/admin/users')->with('success','User deleted successfully.');
    }
}
