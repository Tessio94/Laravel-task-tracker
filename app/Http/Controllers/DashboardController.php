<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        // Summary statistics
        $stats = [
            'tasks_today' => $user->tasks()
                ->whereDate('task_date', $today)
                ->count(),
            'completed_today' => $user->tasks()
                ->whereDate('task_date', $today)
                ->whereNotNull('completed_at')
                ->count(),
            'overdue' => $user->tasks()
                ->whereDate('task_date', '<', $today)
                ->whereNull('completed_at')
                ->count(),
            'total_pending' => $user->tasks()
                ->whereNull('completed_at')
                ->count()
        ];

        // Calculate completion rate for today
        $stats['today_completion_rate'] = $stats['tasks_today'] > 0
            ? round(($stats['completed_today'] / $stats['tasks_today']) * 100)
            : 0;

        // Upcoming tasks (today and tomorrow, incomplete)
        $upcomingTasks = $user->tasks()
            ->with('category')
            ->whereNull('completed_at')
            ->whereBetween('task_date', [$today, $tomorrow])
            ->orderBy('task_date')
            ->orderBy('created_at')
            ->limit(10)
            ->get()
            ->toResourceCollection()
            ->resolve();

        // Overdue tasks
        $overdueTasks = $user->tasks()
            ->with('category')
            ->whereNull('completed_at')
            ->whereDate('task_date', '<', '$today')
            ->orderBy('task_date')
            ->orderBy('created_at')
            ->limit(5)
            ->get()
            ->toResourceCollection()
            ->resolve();

        // Recent completions (last 7 days)
        $recentCompletions = $user->tasks()
            ->whereNotNull('completed_at')
            ->where('completed_at', '>=', $today->copy()->subDays(7))
            ->count();

        return view('dashboard', [
            'stats' => $stats,
            'upcomingTasks' => $upcomingTasks,
            'overdueTasks' => $overdueTasks,
            'recentCompletions' => $recentCompletions,
            'today' => $today
        ]);
    }
}
