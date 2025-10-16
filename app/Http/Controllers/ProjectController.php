<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        // Ambil pengguna yang sedang login
        $user = Auth::user();

        // Ambil semua proyek milik pengguna tersebut, urutkan dari yang terbaru
        $projects = $user->projects()->latest()->get();

        // Kirim data proyek ke view
        return view('projects', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'thumbnail_url' => 'nullable|url',
            'tech_stack' => 'required|string', // Diterima sebagai string dipisah koma
        ]);

        // Ubah string tech_stack menjadi array
        $techStackArray = array_map('trim', explode(',', $validated['tech_stack']));

        // Buat proyek baru yang berelasi dengan user yang login
        Auth::user()->projects()->create([
            'title' => $validated['title'],
            'thumbnail_url' => $validated['thumbnail_url'],
            'tech_stack' => $techStackArray,
        ]);

        return redirect()->route('projects.index')->with('success', 'Project created successfully!');
    }

    public function editor()
    {
        $projectFiles = [
            'index.html' => '<h1>Hello, Scriptly!</h1>\n<p>Edit the code to see changes.</p>',
            'style.css' => 'body { \n  background-color: #f0f0f0;\n  font-family: sans-serif;\n}',
            'script.js' => 'console.log("Welcome to the Scriptly editor!");'
        ];

        return view('editor', compact('projectFiles'));
    }
}
