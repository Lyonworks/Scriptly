<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function save(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'You must login to save project.'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'html' => 'nullable|string',
            'css' => 'nullable|string',
            'js' => 'nullable|string',
        ]);

        $project = Project::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'title' => $validated['title'],
            ],
            [
                'html' => $validated['html'] ?? '',
                'css' => $validated['css'] ?? '',
                'js' => $validated['js'] ?? '',
            ]
        );

        return response()->json([
            'success' => true,
            'project' => $project,
            'message' => 'Project saved successfully!'
        ]);
    }

    public function myProjects()
    {
        $projects = Project::where('user_id', Auth::id())->latest()->get();
        return view('projects', compact('projects'));
    }

    public function edit($id)
    {
        $project = Project::findOrFail($id);
        $this->authorize('view', $project);
        return view('editor', compact('project'));
    }
}
