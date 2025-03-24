<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class HabitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $habits = Auth::user()->habits()->orderBy('name')->get();
        return view('habits.index', compact('habits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('habits.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'frequency' => 'required|in:daily,weekly,monthly',
            'target_value' => 'required|numeric|min:1',
            'unit' => 'nullable|string|max:50',
            'color' => 'nullable|string|in:blue,green,red,yellow,purple,pink,indigo',
        ]);

        $habit = Auth::user()->habits()->create($validated + ['is_active' => true]);

        return redirect()->route('habits.show', $habit)->with('success', 'Habitude créée avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Habit $habit)
    {
        $this->authorize('view', $habit);
        
        $logs = $habit->logs()->orderByDesc('date')->paginate(10);
        return view('habits.show', compact('habit', 'logs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Habit $habit)
    {
        $this->authorize('update', $habit);
        
        return view('habits.edit', compact('habit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Habit $habit)
    {
        $this->authorize('update', $habit);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'frequency' => 'required|in:daily,weekly,monthly',
            'target_value' => 'required|numeric|min:1',
            'unit' => 'nullable|string|max:50',
            'color' => 'nullable|string|in:blue,green,red,yellow,purple,pink,indigo',
            'is_active' => 'required|boolean',
        ]);

        $habit->update($validated);

        return redirect()->route('habits.show', $habit)->with('success', 'Habitude mise à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Habit $habit)
    {
        $this->authorize('delete', $habit);
        
        $habit->delete();

        return redirect()->route('habits.index')->with('success', 'Habitude supprimée avec succès !');
    }

    /**
     * Affiche les statistiques des habitudes
     */
    public function stats()
    {
        $habits = Auth::user()->habits()->with('logs')->get();
        
        return view('habits.stats', compact('habits'));
    }
}
