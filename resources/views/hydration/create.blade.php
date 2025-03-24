@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Ajouter un suivi d\'hydratation') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <form action="{{ route('hydration.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date de l'hydratation</label>
                        <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" required
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>

                    <div class="mb-6">
                        <label for="water_amount" class="block text-sm font-medium text-gray-700 mb-1">Quantité d'eau (ml)</label>
                        <div class="mt-2 flex flex-wrap gap-2">
                            <button type="button" onclick="setWaterAmount(100)" class="bg-blue-100 hover:bg-blue-200 text-blue-800 py-1 px-3 rounded-full transition-colors">
                                + 100ml
                            </button>
                            <button type="button" onclick="setWaterAmount(200)" class="bg-blue-100 hover:bg-blue-200 text-blue-800 py-1 px-3 rounded-full transition-colors">
                                + 200ml
                            </button>
                            <button type="button" onclick="setWaterAmount(250)" class="bg-blue-100 hover:bg-blue-200 text-blue-800 py-1 px-3 rounded-full transition-colors">
                                + 250ml
                            </button>
                            <button type="button" onclick="setWaterAmount(300)" class="bg-blue-100 hover:bg-blue-200 text-blue-800 py-1 px-3 rounded-full transition-colors">
                                + 300ml
                            </button>
                            <button type="button" onclick="setWaterAmount(500)" class="bg-blue-100 hover:bg-blue-200 text-blue-800 py-1 px-3 rounded-full transition-colors">
                                + 500ml
                            </button>
                            <button type="button" onclick="setWaterAmount(750)" class="bg-blue-100 hover:bg-blue-200 text-blue-800 py-1 px-3 rounded-full transition-colors">
                                + 750ml
                            </button>
                            <button type="button" onclick="setWaterAmount(1000)" class="bg-blue-100 hover:bg-blue-200 text-blue-800 py-1 px-3 rounded-full transition-colors">
                                + 1L
                            </button>
                        </div>
                        <div class="mt-3">
                            <input type="number" name="water_amount" id="water_amount" value="250" required min="1" step="1"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            <p class="mt-1 text-xs text-gray-500">Entrez la quantité d'eau consommée en millilitres</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="drink_type" class="block text-sm font-medium text-gray-700 mb-1">Type de boisson</label>
                        <select name="drink_type" id="drink_type" 
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            <option value="water" selected>Eau</option>
                            <option value="tea">Thé</option>
                            <option value="coffee">Café</option>
                            <option value="juice">Jus</option>
                            <option value="other">Autre</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label for="time" class="block text-sm font-medium text-gray-700 mb-1">Heure (optionnel)</label>
                        <input type="time" name="time" id="time" value="{{ date('H:i') }}"
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>

                    <div class="mb-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes (optionnel)</label>
                        <textarea name="notes" id="notes" rows="2" 
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                            placeholder="Ajoutez des notes sur votre hydratation"></textarea>
                    </div>
                
                    <div class="flex justify-between">
                        <a href="{{ route('hydration.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring ring-gray-200 disabled:opacity-25 transition ease-in-out duration-150">
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

<script>
    function setWaterAmount(amount) {
        document.getElementById('water_amount').value = amount;
    }
</script>
@endsection 