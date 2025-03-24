<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SleepController extends Controller
{
    /**
     * Display the sleep tracking index.
     */
    public function index()
    {
        $sleepHabit = Auth::user()->habits()
            ->where('type', Habit::TYPE_SLEEP)
            ->first();
            
        if (!$sleepHabit) {
            // Créer une habitude de sommeil par défaut si elle n'existe pas
            $sleepHabit = Auth::user()->habits()->create([
                'name' => 'Sommeil',
                'description' => 'Suivi de mon sommeil quotidien',
                'type' => Habit::TYPE_SLEEP,
                'frequency' => 'daily',
                'target_value' => 8, // 8 heures recommandées
                'unit' => 'heures',
                'color' => 'indigo',
                'is_active' => true,
            ]);
        }
        
        // Récupérer les logs des 7 derniers jours
        $today = now()->format('Y-m-d');
        $lastWeek = now()->subDays(7)->format('Y-m-d');
        
        $logs = $sleepHabit->logs()
            ->where('date', '>=', $lastWeek)
            ->where('date', '<=', $today)
            ->orderBy('date', 'desc')
            ->get();
            
        // Calculer les statistiques
        $averageSleep = $logs->average('value') ?: 0;
        $deepSleepAvg = $logs->avg(function($log) {
            return json_decode($log->notes, true)['deep_sleep'] ?? 0;
        }) ?: 0;
        
        return view('sleep.index', compact('sleepHabit', 'logs', 'averageSleep', 'deepSleepAvg'));
    }
    
    /**
     * Show the form for logging sleep.
     */
    public function create()
    {
        $sleepHabit = Auth::user()->habits()
            ->where('type', Habit::TYPE_SLEEP)
            ->firstOrFail();
            
        return view('sleep.create', compact('sleepHabit'));
    }
    
    /**
     * Store a sleep log.
     */
    public function store(Request $request)
    {
        $sleepHabit = Auth::user()->habits()
            ->where('type', Habit::TYPE_SLEEP)
            ->firstOrFail();
        
        $validated = $request->validate([
            'date' => 'required|date',
            'sleep_duration' => 'required|numeric|min:0|max:24',
            'quality' => 'required|in:poor,fair,good,excellent',
            'deep_sleep' => 'nullable|numeric|min:0|max:24',
            'notes' => 'nullable|string',
        ]);
        
        // Stocke les détails dans le champ notes au format JSON
        $sleepDetails = [
            'quality' => $validated['quality'],
            'deep_sleep' => $validated['deep_sleep'] ?? 0,
            'notes' => $validated['notes'] ?? '',
        ];
        
        try {
            $sleepHabit->logs()->updateOrCreate(
                ['date' => $validated['date']->toDateString()],
                [
                    'value' => $validated['sleep_duration'],
                    'date' => $validated['date']->toDateString(),
                    'notes' => json_encode($sleepDetails),
                ]
            );
            
            return redirect()->route('sleep.index')->with('success', 'Sommeil enregistré avec succès !');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }
    
    /**
     * Display sleep statistics.
     */
    public function stats()
{
    $sleepHabit = Auth::user()->habits()
        ->where('type', Habit::TYPE_SLEEP)
        ->firstOrFail();

    $logs = $sleepHabit->logs()
        ->orderBy('date', 'desc')
        ->get();

    // Calculate average sleep
    $averageSleep = $logs->average('value') ?: 0;

    // Calculate average deep sleep
    $deepSleepAvg = $logs->avg(function ($log) {
        $notes = json_decode($log->notes, true) ?: [];
        return $notes['deep_sleep'] ?? 0;
    }) ?: 0;

    // Get sleep quality data
    $qualityCounts = [
        'excellent' => 0,
        'good' => 0,
        'fair' => 0,
        'poor' => 0
    ];

    $qualityHours = [
        'excellent' => [],
        'good' => [],
        'fair' => [],
        'poor' => []
    ];

    // Generate the last 30 days
    $dates = collect();
    for ($i = 0; $i < 30; $i++) {
        $date = now()->subDays($i)->toDateString();
        $dates[$date] = ['hours' => 0, 'quality' => 'good', 'deep_sleep' => 0];
    }

    // Merge with existing data
    $dailyData = [];
    foreach ($logs as $log) {
        $date = $log->date->toDateString();
        $notes = json_decode($log->notes, true) ?: [];
        $dailyData[$date] = [
            'hours' => $log->value,
            'quality' => $notes['quality'] ?? 'good',
            'deep_sleep' => $notes['deep_sleep'] ?? 0
        ];
    }
    $dailyData = array_reverse(array_merge($dates->toArray(), $dailyData));
    $totalLogs = $logs->count();

    // Calculate quality statistics
    foreach ($dailyData as $entry) {
        $quality = $entry['quality'];
        if (isset($qualityCounts[$quality])) {
            $qualityCounts[$quality]++;
            $qualityHours[$quality][] = $entry['hours'];
        }
    }

    // Find dominant quality
    $dominantQuality = 'good';
    $dominantQualityCount = 0;

    foreach ($qualityCounts as $quality => $count) {
        if ($count > $dominantQualityCount) {
            $dominantQuality = $quality;
            $dominantQualityCount = $count;
        }
    }

    $dominantQualityPercentage = $totalLogs > 0 ? round(($dominantQualityCount / $totalLogs) * 100) : 0;

    // Create quality distribution
    $qualityDistribution = [];
    foreach ($qualityCounts as $quality => $count) {
        $percentage = $totalLogs > 0 ? round(($count / $totalLogs) * 100) : 0;
        $avgHours = count($qualityHours[$quality]) > 0 ? array_sum($qualityHours[$quality]) / count($qualityHours[$quality]) : 0;

        $qualityDistribution[$quality] = [
            'count' => $count,
            'percentage' => $percentage,
            'avgHours' => $avgHours
        ];
    }

    // Daily goal
    $dailyGoal = $sleepHabit->target_value;

    return view('sleep.stats', compact(
        'sleepHabit',
        'logs',
        'averageSleep',
        'deepSleepAvg',
        'dominantQuality',
        'dominantQualityPercentage',
        'qualityDistribution',
        'dailyData',
        'dailyGoal'
    ));
}

}