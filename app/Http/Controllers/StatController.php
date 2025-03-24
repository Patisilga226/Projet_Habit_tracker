<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatController extends Controller
{
    /**
     * Display the stats index page.
     */
    public function index()
    {
        $habits = Auth::user()->habits()->with('logs')->get();
        
        // Calculate stats for each habit
        $habitStats = [];
        
        foreach ($habits as $habit) {
            $stats = $this->calculateHabitStats($habit);
            $habitStats[$habit->id] = $stats;
        }
        
        // Generate charts data
        $charts = $this->generateOverallCharts($habits);
        
        return view('stats.index', compact('habits', 'habitStats', 'charts'));
    }

    /**
     * Display stats for a specific habit.
     */
    public function show(Habit $habit)
    {
        $this->authorize('view', $habit);
        
        $logs = $habit->logs()->orderByDesc('date')->get();
        
        // Calculate stats
        $stats = $this->calculateHabitStats($habit);
        
        $totalLogs = $stats['totalLogs'];
        $completedLogs = $stats['completedLogs'];
        $completionRate = $stats['completionRate'];
        
        // Generate charts
        $charts = $this->generateHabitCharts($habit, $logs);
        
        return view('stats.show', compact('habit', 'logs', 'totalLogs', 'completedLogs', 'completionRate', 'charts'));
    }

    /**
     * Calculate statistics for a habit.
     */
    private function calculateHabitStats(Habit $habit)
    {
        $logs = $habit->logs;
        
        $totalLogs = $logs->count();
        $completedLogs = $logs->filter(function ($log) use ($habit) {
            return $log->value >= $habit->target_value;
        })->count();
        
        $completionRate = $totalLogs > 0 ? round(($completedLogs / $totalLogs) * 100) : 0;
        
        return [
            'totalLogs' => $totalLogs,
            'completedLogs' => $completedLogs,
            'completionRate' => $completionRate,
        ];
    }

    /**
     * Generate overall charts for all habits.
     */
    private function generateOverallCharts($habits)
    {
        // In a real application, this would generate actual chart data
        // For now, return a placeholder
        
        return [
            'overall' => null,
            'completion_rate' => null,
            'streak' => null,
        ];
    }

    /**
     * Generate charts for a specific habit.
     */
    private function generateHabitCharts(Habit $habit, $logs)
    {
        // In a real application, this would generate actual chart data
        // For now, return a placeholder
        
        return [
            'progress' => null,
            'monthly' => null,
            'heatmap' => null,
        ];
    }
} 