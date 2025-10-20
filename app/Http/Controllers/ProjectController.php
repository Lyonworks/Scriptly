<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectLike;
use App\Models\ProjectComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    // Explore public projects
    public function explore(Request $request)
    {
        $q = Project::where('is_public', true);

        if ($request->query('sort') === 'trending') {
            $q->orderByRaw('(likes_count * 3 + forks_count * 5 + views) DESC');
        } else {
            $q->latest('created_at');
        }

        $projects = $q->with('user')->paginate(12)->withQueryString();

        return view('explore', compact('projects'));
    }

    // Show project
    public function show($id)
    {
        $project = Project::with('user', 'comments.user')->findOrFail($id);

        if (!$project->is_public && Auth::id() !== $project->user_id) {
            abort(404);
        }

        $project->increment('views');
        return view('projects.show', compact('project'));
    }

    // Like toggle
    public function like(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['message' => 'Unauthorized'], 401);

        $project = Project::findOrFail($id);
        $existing = ProjectLike::where('project_id', $id)->where('user_id', $user->id)->first();

        if ($existing) {
            $existing->delete();
            $project->decrement('likes_count');
            return response()->json(['liked' => false, 'likes_count' => $project->likes_count]);
        }

        $projects = Project::with('user')
            ->withCount('likes')
            ->latest()
            ->paginate(12);

        ProjectLike::create(['project_id' => $id, 'user_id' => $user->id]);
        $project->increment('likes_count');

        return response()->json(['liked' => true, 'likes_count' => $project->likes_count]);
    }

    // Fork project
    public function fork(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['message' => 'Unauthorized'], 401);

        $project = Project::findOrFail($id);

        DB::transaction(function () use ($project, $user, &$new) {
            $new = Project::create([
                'user_id' => $user->id,
                'title' => $project->title . ' (fork)',
                'html' => $project->html,
                'css' => $project->css,
                'js' => $project->js,
                'is_public' => true,
                'forked_from' => $project->id,
            ]);
            $project->increment('forks_count');
        });

        return response()->json(['message' => 'Fork created', 'project_id' => $new->id]);
    }

    // Comment
    public function comment(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['message' => 'Unauthorized'], 401);

        $data = $request->validate(['body' => 'required|string|max:2000']);
        $project = Project::findOrFail($id);

        $comment = ProjectComment::create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'body' => $data['body'],
        ]);

        return response()->json(['message' => 'Comment posted', 'comment' => $comment->load('user')]);
    }

    // Check duplicate name
    public function checkName(Request $request)
    {
        $request->validate(['name' => ['required', 'string', 'max:255']]);

        $name = $request->query('name');
        $exists = Project::where('user_id', Auth::id())
            ->whereRaw('LOWER(title) = ?', [Str::lower($name)])
            ->exists();

        return response()->json(['exists' => (bool) $exists]);
    }

    // Save project (for AJAX)
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['message' => 'Unauthorized'], 401);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'html' => ['nullable', 'string'],
            'css' => ['nullable', 'string'],
            'js' => ['nullable', 'string'],
        ]);

        try {
            DB::beginTransaction();

            $base = Str::slug($data['title'] ?: 'project');
            $slug = $base ?: 'project';
            $i = 1;
            while (Project::where('slug', $slug)->exists()) {
                $slug = ($base ?: 'project') . '-' . $i++;
            }

            $project = Project::create([
                'user_id' => $user->id,
                'title' => $data['title'],
                'slug' => $slug,
                'description' => $data['description'] ?? 'Created using Scriptly',
                'html' => $data['html'] ?? '',
                'css' => $data['css'] ?? '',
                'js' => $data['js'] ?? '',
                'is_public' => true,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Project saved successfully!',
                'project_id' => $project->id
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Project save error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server error'], 500);
        }
    }

    // My Projects page
    public function myProjects()
    {
        $projects = Project::where('user_id', Auth::id())->latest()->get();
        return view('projects', compact('projects'));
    }

    // Edit project
    public function edit($id)
    {
        $project = Project::findOrFail($id);
        $this->authorize('view', $project);
        return view('editor', compact('project'));
    }

    // Delete project (support AJAX)
    public function delete($id)
    {
        $project = Project::findOrFail($id);
        $this->authorize('delete', $project);
        $project->delete();

        // Jika request dari AJAX, kirim JSON.
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Project deleted successfully!']);
        }

        // Jika dari browser biasa (form POST), redirect balik ke halaman projects.
        return redirect()->route('projects')->with('success', 'Project deleted successfully!');
    }
}
