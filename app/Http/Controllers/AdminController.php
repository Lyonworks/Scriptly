<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Project;
use App\Models\Execution;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AdminController extends Controller {
    public function loginForm() {
        return view('auth');
    }

    public function login(Request $request) {
        $credentials = $request->only('email','password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Hanya role_id 1 (superadmin) & 2 (admin) yang boleh masuk
            if (in_array((int) $user->role_id, [1, 2])) {
                return redirect()->route('admin.dashboard');
            }

            // Kalau role_id = 3 (user), tampilkan 404
            Auth::logout();
            return response()->view('errors.404', [], 404);
        }

        return back()->withErrors(['email'=>'Invalid admin credentials']);
    }

    public function index(){
        // --- fallback jika tabel tidak ada (mencegah error) ---
        $hasExecutionsTable = Schema::hasTable('executions');
        $hasActivitiesTable = Schema::hasTable('activities');
        $hasApiCallsTable   = Schema::hasTable('api_calls');    // optional
        $hasErrorLogsTable  = Schema::hasTable('error_logs');  // optional

        // --- basic counts (users/projects) ---
        $totalUsers    = User::count();
        $totalProjects = Project::count();

        // --- executions counts & average duration ---
        $totalExecutions = $hasExecutionsTable ? Execution::count() : 0;
        // average execution time in seconds (nullable)
        $avgExecutionMs = $hasExecutionsTable ? (float) (Execution::avg('duration_ms') ?? 0) : 0;

        // --- api calls (if you record them) ---
        $apiCalls = $hasApiCallsTable ? DB::table('api_calls')->count() : 0;

        // --- total public projects ---
        $totalPublic = Schema::hasTable('projects') ? Project::where('is_public', true)->count() : 0;

        // --- recent activity logs (latest 50) ---
        $recentActivities = $hasActivitiesTable
            ? Activity::with('user')->latest('created_at')->limit(50)->get()
            : collect();

        // --- error logs (latest 50) ---
        $errorLogs = $hasErrorLogsTable
            ? DB::table('error_logs')->orderByDesc('created_at')->limit(50)->get()
            : collect();

        // --- executions per day: last 30 days (labels + counts) ---
        $days = 30;
        $start = Carbon::now()->startOfDay()->subDays($days - 1);

        $executionCountsQuery = $hasExecutionsTable
            ? DB::table('executions')
                ->select(DB::raw("DATE(created_at) as day"), DB::raw('COUNT(*) as count'))
                ->where('created_at', '>=', $start)
                ->groupBy('day')
                ->orderBy('day')
            : null;

        $executionCountsRaw = $executionCountsQuery ? $executionCountsQuery->get()->keyBy('day') : collect();

        // Build labels and counts arrays for chart (ensures 30 points)
        $executionLabels = [];
        $executionCounts = [];
        for ($i = 0; $i < $days; $i++) {
            $d = $start->copy()->addDays($i);
            $label = $d->format('Y-m-d');
            $executionLabels[] = $d->format('M j'); // friendly label e.g. "Nov 1"
            $executionCounts[] = $executionCountsRaw->has($label) ? (int) $executionCountsRaw->get($label)->count : 0;
        }

        // --- weekly activity heatmap (counts per weekday) ---
        // returns Mon..Sun counts
        $weekDays = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
        $activityCountsQuery = $hasActivitiesTable
            ? DB::table('activities')
                ->select(DB::raw("DAYOFWEEK(created_at) as dow"), DB::raw('COUNT(*) as count'))
                ->where('created_at', '>=', Carbon::now()->subWeeks(4))
                ->groupBy('dow')
            : null;

        $activityRaw = $activityCountsQuery ? $activityCountsQuery->get()->keyBy('dow') : collect();

        // Note: MySQL DAYOFWEEK returns 1 = Sunday .. 7 = Saturday
        // Map to Mon..Sun
        $activityCounts = [];
        foreach ([2,3,4,5,6,7,1] as $mysqlDow) {
            $activityCounts[] = $activityRaw->has($mysqlDow) ? (int) $activityRaw->get($mysqlDow)->count : 0;
        }

        // --- trending projects (example: most liked or most forked in last 7 days) ---
        $trending = Schema::hasTable('projects')
            ? Project::withCount(['likes'])
                ->where('created_at', '>=', Carbon::now()->subDays(7))
                ->orderByDesc('likes_count')
                ->limit(6)
                ->get()
            : collect();

        // --- user growth per month ---
        $userGrowth = User::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // --- project growth per month ---
        $projectGrowth = Project::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // --- pass everything to the view ---
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalProjects',
            'totalExecutions',
            'totalPublic',
            'avgExecutionMs',
            'apiCalls',
            'recentActivities',
            'errorLogs',
            'executionLabels',
            'executionCounts',
            'weekDays',
            'activityCounts',
            'trending',
            'userGrowth',
            'projectGrowth'
        ));
    }

    public function projectsIndex(Request $request)
    {
        $query = Project::with('user');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhere('language', 'like', "%{$search}%");
            });
        }

        $projects = $query->latest()->paginate(10);

        // Statistik
        $totalProjects   = Project::count();
        $publicProjects  = Project::where('is_public', true)->count();
        $privateProjects = Project::where('is_public', false)->count();
        $deletedProjects = Project::onlyTrashed()->count();

        return view('admin.projects.index', compact(
            'projects',
            'totalProjects',
            'publicProjects',
            'privateProjects',
            'deletedProjects'
        ));
    }

    public function projectsShow(Project $project)
    {
        return view('admin.projects.show', compact('project'));
    }

    public function projectsDestroy(Project $project)
    {
        $project->delete();
        return redirect()->back()->with('success', 'Project deleted successfully.');
    }

    public function toggleVisibility(Project $project)
    {
        $project->is_public = !$project->is_public;
        $project->save();

        return redirect()->back()->with('success', 'Visibility changed.');
    }

    public function analytics()
    {
        // --- Stats umum ---
        $totalExecutions   = \App\Models\Execution::count();
        $avgExecutionMs    = (float) (\App\Models\Execution::avg('duration_ms') ?? 0);
        $executionsToday   = \App\Models\Execution::whereDate('created_at', now()->toDateString())->count();

        // --- Total login & logout activity ---
        $totalLogins  = \App\Models\Activity::where('type', 'login')->count();
        $totalLogouts = \App\Models\Activity::where('type', 'logout')->count();

        // --- Eksekusi per hari (30 hari terakhir) ---
        $executionsPerDay = \App\Models\Execution::select(
                \DB::raw("DATE(created_at) as day"),
                \DB::raw("COUNT(*) as total")
            )
            ->where('created_at', '>=', now()->subDays(29))
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->mapWithKeys(fn($r) => [$r->day => (int)$r->total]);

        // Konversi data untuk Chart.js
        $executionLabels = $executionsPerDay->keys();
        $executionCounts = $executionsPerDay->values();

        // --- API calls per 7 hari terakhir ---
        $apiCallsData = \App\Models\ApiCall::where('created_at', '>=', now()->subDays(6))
            ->select(\DB::raw("DATE(created_at) as day"), \DB::raw("COUNT(*) as total"))
            ->groupBy('day')->orderBy('day')->get();

        // Total API calls (jumlah semua)
        $apiCalls = $apiCallsData->sum('total');

        // --- Aktivitas & error terbaru ---
        $recentActivities = \App\Models\Activity::with('user')->latest()->take(20)->get();
        $recentErrors     = \App\Models\ErrorLog::latest()->take(20)->get();

        // --- Heatmap eksekusi code seminggu terakhir ---
        $heat = \App\Models\Execution::select(
                \DB::raw('DAYOFWEEK(created_at) as dow'),
                \DB::raw('HOUR(created_at) as hour'),
                \DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('dow', 'hour')
            ->get();

        // --- Data untuk heatmap mingguan (simulasi / ringkasan) ---
        $weekDays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        // Hitung total aktivitas per hari (login + eksekusi, misalnya)
        $activityCounts = \App\Models\Activity::select(
                \DB::raw('DAYOFWEEK(created_at) as dow'),
                \DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('dow')
            ->orderBy('dow')
            ->pluck('total', 'dow')
            ->toArray();

        // Pastikan urutan hari 1–7 (Minggu–Sabtu)
        $activityCounts = array_replace(array_fill(1, 7, 0), $activityCounts);
        $activityCounts = array_values($activityCounts);

        // --- kirim semua data ke view ---
        return view('admin.analytics.index', compact(
            'totalExecutions',
            'avgExecutionMs',
            'executionsToday',
            'executionsPerDay',
            'executionLabels',
            'executionCounts',
            'apiCalls',
            'recentActivities',
            'recentErrors',
            'heat',
            'totalLogins',
            'totalLogouts',
            'weekDays',
            'activityCounts'
        ));
    }
}
