<?php
// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        $projects = $user->projects()->latest()->get();

        return view('profile', compact('user', 'projects'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'bio'      => ['nullable', 'string', 'max:1000'],
            'location' => ['nullable', 'string', 'max:255'],
            'link1'    => ['nullable', 'string', 'max:255'],
            'link2'    => ['nullable', 'string', 'max:255'],
            'link3'    => ['nullable', 'string', 'max:255'],
        ]);

        // gunakan transaction agar create/update konsisten
        DB::transaction(function () use ($user, $validatedData) {
            // selalu perbarui fields pada users (name)
            $user->fill(Arr::only($validatedData, ['name']));
            $user->save();

            $profileData = Arr::only($validatedData, ['bio', 'location', 'link1', 'link2', 'link3']);

            // hanya gunakan relasi profiles jika tabel profiles ada
            if (Schema::hasTable('profiles') && method_exists($user, 'profile')) {
                $user->profile()->updateOrCreate(
                    ['user_id' => $user->id],
                    $profileData
                );
            } else {
                // fallback: simpan fields di tabel users jika tidak ada tabel profiles
                $user->fill($profileData);
                $user->save();
            }
        });

        return redirect()->route('profile.index')->with('status', 'Profile updated successfully!');
    }
}
