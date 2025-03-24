<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Habit;
use App\Models\HabitLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HabitLogController extends Controller
{
    /**
     * Affiche la liste des logs pour une habitude spécifique
     */
    public function index(Habit $habit)
    {
        $this->authorize('view', $habit);
        
        $logs = $habit->logs()->orderBy('date', 'desc')->paginate(15);
        
        return view('habit_logs.index', compact('habit', 'logs'));
    }

    /**
     * Show the form for creating a new habit log.
     */
    public function create(Habit $habit)
    {
        $this->authorize('view', $habit);
        
        return view('habit_logs.create', compact('habit'));
    }

    /**
     * Store a newly created habit log in storage.
     */
    public function store(Request $request, Habit $habit)
    {
        $this->authorize('update', $habit);
        
        $validated = $request->validate([
            'date' => 'required|date',
            // 'date' => 'required|date_format:Y-m-d',
            'value' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['date'] = Carbon::parse($validated['date'])->format('Y-m-d');

        try {
            // Utilise updateOrCreate pour gérer automatiquement le cas où un log existe déjà
            $habit->logs()->updateOrCreate(
                ['date' => $validated['date']], // Critères de recherche
                $validated                      // Données à mettre à jour ou à créer
            );

            return redirect()->route('habits.show', $habit)
                ->with('success', 'Suivi enregistré avec succès !');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified habit log.
     */
    public function edit(Habit $habit, HabitLog $log)
    {
        $this->authorize('update', $habit);
        $this->authorize('update', $log);
        
        return view('habit_logs.edit', compact('habit', 'log'));
    }

    /**
     * Update the specified habit log in storage.
     */
    public function update(Request $request, Habit $habit, HabitLog $log)
    {
        $this->authorize('update', $habit);
        $this->authorize('update', $log);
        
        $validated = $request->validate([
            'date' => 'required|date',
            'value' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $log->update($validated);

        return redirect()->route('habits.show', $habit)->with('success', 'Suivi mis à jour avec succès !');
    }

    /**
     * Remove the specified habit log from storage.
     */
    public function destroy(Habit $habit, HabitLog $log)
    {
        $this->authorize('update', $habit);
        $this->authorize('delete', $log);
        
        $log->delete();

        return redirect()->route('habits.show', $habit)->with('success', 'Suivi supprimé avec succès !');
    }

    /**
     * Quick log for a habit.
     */
    public function quickLog(Habit $habit)
    {
        $this->authorize('update', $habit);
        
        $today = now()->format('Y-m-d');
        
        try {
            // Vérifie d'abord si un log existe déjà pour cette habitude à cette date
            $log = $habit->logs()->where('date', $today)->first();
            
            if ($log) {
                // Si le log existe, incrémente simplement sa valeur et affiche un message d'avertissement
                $log->increment('value');
                return back()->with('warning', 'Cette habitude a déjà été enregistrée aujourd\'hui. La valeur a été incrémentée.');
            } else {
                // Si le log n'existe pas, crée un nouveau avec une valeur initiale de 1
                $habit->logs()->create([
                    'date' => $today,
                    'value' => 1
                ]);
            }

            return back()->with('success', 'Habitude complétée pour aujourd\'hui !');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }
}
