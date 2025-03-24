<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Habit;
use App\Models\HabitLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HabitApiController extends Controller
{
    /**
     * Récupérer toutes les habitudes
     */
    public function index(Request $request)
    {
        $habits = Habit::with('logs')->get();
        
        return response()->json($habits);
    }

    /**
     * Récupérer une habitude spécifique
     */
    public function show(Habit $habit)
    {
        return response()->json($habit);
    }

    /**
     * Récupérer les logs d'une habitude
     */
    public function logs(Habit $habit)
    {
        $logs = $habit->logs()->orderBy('date', 'desc')->get();
        
        return response()->json($logs);
    }

    /**
     * Récupérer les statistiques d'une habitude au format JSON
     */
    public function stats(Habit $habit)
    {
        $logs = $habit->logs()->orderBy('date', 'asc')->get();
        
        // Préparer les données au format attendu par le script Python
        $data = [
            'name' => $habit->name,
            'type' => $habit->type,
            'dates' => [],
            'values' => [],
            'target_value' => $habit->target_value,
            'unit' => $habit->unit
        ];
        
        foreach ($logs as $log) {
            $data['dates'][] = $log->date->format('Y-m-d');
            $data['values'][] = $log->value;
        }
        
        return response()->json($data);
    }
}