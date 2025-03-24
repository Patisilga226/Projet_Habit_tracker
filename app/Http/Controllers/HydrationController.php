<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HydrationController extends Controller
{
    /**
     * Display the hydration tracking index.
     */
    public function index()
    {
        $hydrationHabit = Auth::user()->habits()
            ->where('type', Habit::TYPE_HYDRATION)
            ->first();

        return $this->showHydrationStats($hydrationHabit);
            
        if (!$hydrationHabit) {
            // Créer une habitude d'hydratation par défaut si elle n'existe pas
            $hydrationHabit = Auth::user()->habits()->create([
                'name' => 'Hydratation',
                'description' => 'Suivi de ma consommation d\'eau quotidienne',
                'type' => Habit::TYPE_HYDRATION,
                'frequency' => 'daily',
                'target_value' => 2000, // 2000 ml recommandés
                'unit' => 'ml',
                'color' => 'blue',
                'is_active' => true,
            ]);
        }
        
        // Récupérer le log d'aujourd'hui
        $today = now()->format('Y-m-d');
        $todayLog = $hydrationHabit->logs()->where('date', $today)->first();
        $todayAmount = $todayLog ? $todayLog->value : 0;
        $dailyGoal = $hydrationHabit->target_value;
        
        // Données pour la semaine
        $startOfWeek = now()->startOfWeek();
        $weeklyData = [];
        $dayNames = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
        
        for ($day = 0; $day < 7; $day++) {
            $currentDate = $startOfWeek->copy()->addDays($day);
            $dateStr = $currentDate->format('Y-m-d');
            $log = $hydrationHabit->logs()->where('date', $dateStr)->first();
            
            $weeklyData[] = [
                'day_name' => $dayNames[$day],
                'date' => $currentDate->format('d/m'),
                'amount' => $log ? $log->value : 0,
                'percentage' => $log ? min(1, $log->value / $dailyGoal) : 0,
                'is_today' => $dateStr === $today
            ];
        }
            
        // Récupérer les logs des 30 derniers jours pour l'historique
        $lastMonth = now()->subDays(30)->format('Y-m-d');
        $logs = $hydrationHabit->logs()
            ->where('date', '>=', $lastMonth)
            ->where('date', '<=', $today)
            ->orderBy('date', 'desc')
            ->get();
        
        return view('hydration.index', compact(
            'hydrationHabit', 
            'logs', 
            'todayAmount', 
            'dailyGoal',
            'weeklyData'
        ));
    }
    
    /**
     * Affiche les statistiques d'hydratation pour un habit d'hydratation donné
     */
    private function showHydrationStats($hydrationHabit)
    {
        if (!$hydrationHabit) {
            // Créer une habitude d'hydratation par défaut si elle n'existe pas
            $hydrationHabit = Auth::user()->habits()->create([
                'name' => 'Hydratation',
                'description' => 'Suivi de ma consommation d\'eau quotidienne',
                'type' => Habit::TYPE_HYDRATION,
                'frequency' => 'daily',
                'target_value' => 2000, // 2000 ml recommandés
                'unit' => 'ml',
                'color' => 'blue',
                'is_active' => true,
            ]);
        }
        
        // Récupérer le log d'aujourd'hui
        $today = now()->format('Y-m-d');
        $todayLog = $hydrationHabit->logs()->where('date', $today)->first();
        $todayAmount = $todayLog ? $todayLog->value : 0;
        $dailyGoal = $hydrationHabit->target_value;
        
        // Données pour la semaine
        $startOfWeek = now()->startOfWeek();
        $weeklyData = [];
        $dayNames = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
        
        for ($day = 0; $day < 7; $day++) {
            $currentDate = $startOfWeek->copy()->addDays($day);
            $dateStr = $currentDate->format('Y-m-d');
            $log = $hydrationHabit->logs()->where('date', $dateStr)->first();
            
            $weeklyData[] = [
                'day_name' => $dayNames[$day],
                'date' => $currentDate->format('d/m'),
                'amount' => $log ? $log->value : 0,
                'percentage' => $log ? min(1, $log->value / $dailyGoal) : 0,
                'is_today' => $dateStr === $today
            ];
        }
            
        // Récupérer les logs des 30 derniers jours pour l'historique
        $lastMonth = now()->subDays(30)->format('Y-m-d');
        $logs = $hydrationHabit->logs()
            ->where('date', '>=', $lastMonth)
            ->where('date', '<=', $today)
            ->orderBy('date', 'desc')
            ->get();
        
        return view('hydration.index', compact(
            'hydrationHabit', 
            'logs', 
            'todayAmount', 
            'dailyGoal',
            'weeklyData'
        ));
    }
    
    /**
     * Affiche les statistiques d'hydratation
     */
    public function stats()
    {
        $hydrationHabit = Auth::user()->habits()
            ->where('type', Habit::TYPE_HYDRATION)
            ->firstOrFail();

        // Calcul de la moyenne quotidienne
        $today = now()->format('Y-m-d');
        $lastMonth = now()->subDays(30)->format('Y-m-d');
        
        $logs = $hydrationHabit->logs()
            ->where('date', '>=', $lastMonth)
            ->where('date', '<=', $today)
            ->get();

        $averageIntake = $logs->avg('value') / 1000; // Convertir en litres
        $dailyGoal = $hydrationHabit->target_value / 1000; // Convertir en litres

        // Calcul des jours où l'objectif est atteint
        $totalTrackedDays = $logs->count();
        
        $goalReachedDays = $logs->filter(function ($log) use ($hydrationHabit) {
            return $log->value >= $hydrationHabit->target_value;
        })->count();

        $goalReachedPercentage = $totalTrackedDays > 0 
            ? round(($goalReachedDays / $totalTrackedDays) * 100)
            : 0;

        // Données pour le graphique de tendance
        $dailyData = $logs->sortBy('date')->map(function ($log) {
            return [
                'date' => $log->date,
                'amount' => $log->value / 1000 // Convertir en litres
            ];
        })->values();
        
        // Données pour le graphique de tendance mensuelle
        $monthlyData = $logs->groupBy(function ($log) {
            return date('Y-m', strtotime($log->date));
        })->map(function ($group, $month) {
            return [
                'month' => date('M Y', strtotime($month.'-01')),
                'average' => round($group->avg('value'))
            ];
        })->values()->toArray();

        // Données pour le graphique de type de boisson
        $beverageTypes = $logs->groupBy('beverage_type')
            ->map(function ($group) use ($logs) {
                return [
                    'count' => $group->count(),
                    'percentage' => round(($group->count() / $logs->count()) * 100)
                ];
            });

        $dominantType = $beverageTypes->sortByDesc('count')->keys()->first() ?? 'eau';
        $dominantTypePercentage = $beverageTypes[$dominantType]['percentage'] ?? 0;
        $favoriteType = $dominantType;

        $favoriteTypePercentage = $dominantTypePercentage;
        
        // Formater les données pour le graphique de distribution des types
        $typeDistribution = $beverageTypes->map(function ($data, $type) {
            return [
                'type' => $type,
                'total' => $data['count'],
                'percentage' => $data['percentage']
            ];
        })->values()->toArray();

        return view('hydration.stats', compact(
            'averageIntake',
            'dailyGoal',
            'goalReachedDays',
            'goalReachedPercentage',
            'dailyData',
            'beverageTypes',
            'dominantType',
            'dominantTypePercentage',
            'totalTrackedDays',
            'favoriteType',
            'favoriteTypePercentage',
            'monthlyData',
            'typeDistribution'
        ));
    }

    /**
     * Quick add water consumption.
     */
    public function quickAdd(Request $request)
    {
        $hydrationHabit = Auth::user()->habits()
            ->where('type', Habit::TYPE_HYDRATION)
            ->firstOrFail();
            
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1|max:5000',
        ]);
        
        $today = now()->format('Y-m-d');
        
        try {
            $log = $hydrationHabit->logs()->updateOrCreate(
                ['date' => $today],
                []
            );
            
            // Ajouter le montant au log existant
            $log->increment('value', $validated['amount']);
            
            return back()->with('success', $validated['amount'] . ' ml d\'eau ajouté !');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }
    
    /**
     * Show the form for detailed hydration logging.
     */
    public function create()
    {
        $hydrationHabit = Auth::user()->habits()
            ->where('type', Habit::TYPE_HYDRATION)
            ->firstOrFail();
            
        // Obtenir les presets communs (différentes tailles de contenants)
        $presets = [
            ['name' => 'Petit verre', 'amount' => 200],
            ['name' => 'Grand verre', 'amount' => 300],
            ['name' => 'Bouteille', 'amount' => 500],
            ['name' => 'Grande bouteille', 'amount' => 1000],
        ];
        
        return view('hydration.create', compact('hydrationHabit', 'presets'));
    }
    
    /**
     * Store a detailed hydration log.
     */
    public function store(Request $request)
    {
        $hydrationHabit = Auth::user()->habits()
            ->where('type', Habit::TYPE_HYDRATION)
            ->firstOrFail();
        
        $validated = $request->validate([
            'date' => 'required|date',
            'water_amount' => 'required|numeric|min:0',
            'drink_type' => 'nullable|string',
            'time' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        
        $details = [
            'drink_type' => $validated['drink_type'] ?? 'water',
            'time' => $validated['time'] ?? null,
            'notes' => $validated['notes'] ?? '',
        ];
        
        try {
            $log = $hydrationHabit->logs()->updateOrCreate(
                ['date' => $validated['date']],
                [
                    'value' => $validated['water_amount'],
                    'notes' => json_encode($details),
                ]
            );
            
            return redirect()->route('hydration.index')->with('success', 'Hydratation enregistrée avec succès !');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }
    

}