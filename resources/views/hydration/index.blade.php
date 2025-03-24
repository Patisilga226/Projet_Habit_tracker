@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Suivi d\'hydratation') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Statistiques d'hydratation -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex flex-wrap justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Aperçu de mon hydratation</h3>
                    <div class="flex space-x-2">
                        <a href="{{ route('hydration.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Ajouter
                        </a>
                        <a href="{{ route('hydration.stats') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Statistiques
                        </a>
                    </div>
                </div>
                
                <!-- Aujourd'hui -->
                <div class="bg-blue-50 rounded-lg p-6 mb-6">
                    <h4 class="text-md font-medium text-blue-800 mb-3">Aujourd'hui</h4>
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="mb-4 md:mb-0">
                            <div class="flex items-center">
                                <span class="text-4xl font-bold text-blue-900">{{ $todayAmount }}</span>
                                <span class="ml-2 text-lg text-blue-700">ml</span>
                                <span class="ml-4 text-sm {{ $todayAmount >= $dailyGoal ? 'text-green-600' : 'text-yellow-600' }}">
                                    @if($todayAmount >= $dailyGoal)
                                        ✓ Objectif atteint
                                    @else
                                        {{ round(($todayAmount / $dailyGoal) * 100) }}% de l'objectif
                                    @endif
                                </span>
                            </div>
                            <div class="text-sm text-blue-600 mt-1">Objectif: {{ $dailyGoal }} ml</div>
                        </div>
                        
                        <div class="flex flex-wrap gap-2">
                            <form action="{{ route('hydration.quick_add') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="amount" value="200">
                                <button type="submit" class="bg-blue-200 hover:bg-blue-300 text-blue-800 py-2 px-4 rounded-md text-sm transition-colors">
                                    + 200ml
                                </button>
                            </form>
                            <form action="{{ route('hydration.quick_add') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="amount" value="350">
                                <button type="submit" class="bg-blue-200 hover:bg-blue-300 text-blue-800 py-2 px-4 rounded-md text-sm transition-colors">
                                    + 350ml
                                </button>
                            </form>
                            <form action="{{ route('hydration.quick_add') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="amount" value="500">
                                <button type="submit" class="bg-blue-200 hover:bg-blue-300 text-blue-800 py-2 px-4 rounded-md text-sm transition-colors">
                                    + 500ml
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Progress bar -->
                    <div class="mt-4">
                        <div class="w-full bg-blue-200 rounded-full h-3">
                            <div class="bg-blue-600 h-3 rounded-full" style="width: {{ min(100, round(($todayAmount / $dailyGoal) * 100)) }}%"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Cette semaine -->
                <div>
                    <h4 class="text-md font-medium text-gray-800 mb-3">Cette semaine</h4>
                    <div class="grid grid-cols-7 gap-2 text-center">
                        @foreach($weeklyData as $day)
                        <div class="p-3 {{ $day['is_today'] ? 'bg-blue-100 rounded-lg' : '' }}">
                            <div class="text-sm font-medium text-gray-500">{{ $day['day_name'] }}</div>
                            <div class="mt-1 text-xs">{{ $day['date'] }}</div>
                            <div class="mt-3">
                                <div class="inline-block w-6 h-{{ min(24, ceil($day['percentage'] * 24)) }} bg-blue-500 rounded-t-sm"></div>
                            </div>
                            <div class="mt-1 text-sm font-medium {{ $day['percentage'] >= 1 ? 'text-green-600' : 'text-gray-700' }}">
                                {{ $day['amount'] }} ml
                            </div>
                            <div class="mt-1 text-xs text-gray-500">
                                {{ round($day['percentage'] * 100) }}%
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Historique récent -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Historique d'hydratation récent</h3>
                
                @if($logs->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heure</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($logs as $log)
                                    @php
                                        $notes = json_decode($log->notes, true) ?? [];
                                        $drinkType = $notes['drink_type'] ?? 'water';
                                        $time = $notes['time'] ?? null;
                                        $textNotes = $notes['notes'] ?? '';
                                        
                                        $typeLabels = [
                                            'water' => 'Eau',
                                            'tea' => 'Thé',
                                            'coffee' => 'Café',
                                            'juice' => 'Jus',
                                            'other' => 'Autre'
                                        ];
                                        
                                        $typeColors = [
                                            'water' => 'bg-blue-100 text-blue-800',
                                            'tea' => 'bg-green-100 text-green-800',
                                            'coffee' => 'bg-yellow-100 text-yellow-800',
                                            'juice' => 'bg-orange-100 text-orange-800',
                                            'other' => 'bg-gray-100 text-gray-800'
                                        ];
                                        
                                        $typeColor = $typeColors[$drinkType] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ \Carbon\Carbon::parse($log->date)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $time ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $log->value }} ml
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $typeColor }}">
                                                {{ $typeLabels[$drinkType] ?? 'Eau' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $textNotes }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    Vous n'avez pas encore enregistré d'hydratation. Commencez à suivre votre consommation d'eau en utilisant le bouton "Ajouter".
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection