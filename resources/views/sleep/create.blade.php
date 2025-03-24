@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Ajouter un suivi de sommeil') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <form action="{{ route('sleep.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date du sommeil</label>
                        <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" required
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>

                    <div class="mb-6">
                        <label for="sleep_duration" class="block text-sm font-medium text-gray-700 mb-1">Durée de sommeil (heures)</label>
                        <input type="number" name="sleep_duration" id="sleep_duration" required step="0.1" min="0" max="24"
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        <p class="mt-1 text-xs text-gray-500">Entrez la durée totale de sommeil en heures (ex: 7.5 pour 7h30)</p>
                    </div>

                    <div class="mb-6">
                        <label for="deep_sleep" class="block text-sm font-medium text-gray-700 mb-1">Sommeil profond (heures)</label>
                        <input type="number" name="deep_sleep" id="deep_sleep" step="0.1" min="0" max="24"
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        <p class="mt-1 text-xs text-gray-500">Estimation du temps passé en sommeil profond (optionnel)</p>
                    </div>

                    <div class="mb-6">
                        <label for="quality" class="block text-sm font-medium text-gray-700 mb-1">Qualité du sommeil</label>
                        <select name="quality" id="quality" required
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            <option value="poor">Mauvaise</option>
                            <option value="fair">Moyenne</option>
                            <option value="good" selected>Bonne</option>
                            <option value="excellent">Excellente</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes (optionnel)</label>
                        <textarea name="notes" id="notes" rows="3" 
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                            placeholder="Ajoutez des notes sur votre sommeil, comme des rêves, des réveils nocturnes, etc."></textarea>
                    </div>
                
                    <div class="flex justify-between">
                        <a href="{{ route('sleep.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring ring-gray-200 disabled:opacity-25 transition ease-in-out duration-150">
                            Annuler
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 