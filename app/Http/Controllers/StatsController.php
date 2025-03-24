<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class StatsController extends Controller
{
    /**
     * Constructeur qui applique le middleware auth
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Affiche les statistiques de toutes les habitudes
     */
    public function index()
    {
        $user = Auth::user();
        $habits = $user->habits()->with('logs')->get();
        
        $habitStats = [];
        foreach ($habits as $habit) {
            $totalLogs = $habit->logs()->count();
            $completedLogs = $habit->logs()->where('value', '>=', $habit->target_value)->count();
            $completionRate = $totalLogs > 0 ? round(($completedLogs / $totalLogs) * 100, 1) : 0;
            
            $habitStats[$habit->id] = [
                'name' => $habit->name,
                'totalLogs' => $totalLogs,
                'completedLogs' => $completedLogs,
                'completionRate' => $completionRate,
            ];
        }
        
        // Générer des graphiques avec Python
        $charts = $this->generateCharts($habits);
        
        return view('stats.index', compact('habits', 'habitStats', 'charts'));
    }

    /**
     * Affiche les statistiques d'une habitude spécifique
     */
    public function show(Habit $habit)
    {
        $this->authorize('view', $habit);
        
        $logs = $habit->logs()->orderBy('date')->get();
        $totalLogs = $logs->count();
        $completedLogs = $logs->where('value', '>=', $habit->target_value)->count();
        $completionRate = $totalLogs > 0 ? round(($completedLogs / $totalLogs) * 100, 1) : 0;
        
        // Générer des graphiques spécifiques à cette habitude
        $charts = $this->generateHabitCharts($habit);
        
        return view('stats.show', compact('habit', 'logs', 'totalLogs', 'completedLogs', 'completionRate', 'charts'));
    }

    /**
     * Génère des graphiques pour toutes les habitudes de l'utilisateur
     */
    private function generateCharts($habits)
    {
        $data = [];
        
        // Préparer les données pour les graphiques
        foreach ($habits as $habit) {
            $logs = $habit->logs()->orderBy('date')->get();
            
            $dates = $logs->pluck('date')->map(function($date) {
                return $date->format('Y-m-d');
            })->toArray();
            
            $values = $logs->pluck('value')->toArray();
            
            $data[$habit->id] = [
                'name' => $habit->name,
                'dates' => $dates,
                'values' => $values,
                'target_value' => $habit->target_value,
            ];
        }
        
        // Sauvegarder les données au format JSON pour Python
        $jsonData = json_encode($data);
        
        // S'assurer que le répertoire existe
        if (!Storage::exists('stats')) {
            Storage::makeDirectory('stats');
        }
        
        // Sauvegarder le fichier avec le contenu JSON
        $result = Storage::put('stats/habits_data.json', $jsonData);
        
        // Vérifier si le fichier a été créé avec succès
        if (!$result) {
            return ['error' => 'Impossible de créer le fichier JSON pour les graphiques'];
        }
        
        // Exécuter le script Python pour générer les graphiques
        try {
            $process = new Process(['python', base_path('python/generate_charts.py')]);
            $process->run();
            
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
            
            return [
                'overall' => asset('storage/stats/overall_progress.png'),
                'completion_rate' => asset('storage/stats/completion_rate.png'),
                'streak' => asset('storage/stats/streak_analysis.png'),
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Génère des graphiques pour une habitude spécifique
     */
    private function generateHabitCharts(Habit $habit)
    {
        $logs = $habit->logs()->orderBy('date')->get();
        
        $data = [
            'name' => $habit->name,
            'dates' => $logs->pluck('date')->map(function($date) {
                return $date->format('Y-m-d');
            })->toArray(),
            'values' => $logs->pluck('value')->toArray(),
            'target_value' => $habit->target_value,
        ];
        
        // Sauvegarder les données au format JSON pour Python
        $jsonData = json_encode($data);
        
        // S'assurer que le répertoire existe
        if (!Storage::exists('stats')) {
            Storage::makeDirectory('stats');
        }
        
        // Sauvegarder le fichier avec le contenu JSON
        $result = Storage::put('stats/habit_data.json', $jsonData);
        
        // Vérifier si le fichier a été créé avec succès
        if (!$result) {
            return ['error' => 'Impossible de créer le fichier JSON pour les graphiques'];
        }
        
        // Exécuter le script Python pour générer les graphiques
        try {
            $process = new Process(['python', base_path('python/generate_habit_charts.py'), $habit->id]);
            $process->run();
            
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
            
            return [
                'progress' => asset('storage/stats/habit_' . $habit->id . '_progress.png'),
                'monthly' => asset('storage/stats/habit_' . $habit->id . '_monthly.png'),
                'heatmap' => asset('storage/stats/habit_' . $habit->id . '_heatmap.png'),
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
