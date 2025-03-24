@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Suivi du sommeil') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Statistiques de sommeil -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex flex-wrap justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Aperçu de mon sommeil</h3>
                    <div class="flex space-x-2">
                        <a href="{{ route('sleep.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Ajouter un suivi
                        </a>
                        <a href="{{ route('sleep.stats') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Voir les statistiques
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <div class="bg-indigo-50 rounded-lg p-6">
                        <h4 class="text-sm font-medium text-indigo-800 mb-2">Durée moyenne de sommeil</h4>
                        <div class="flex items-end">
                            <span class="text-3xl font-bold text-indigo-900">{{ number_format($averageSleep, 1) }}</span>
                            <span class="ml-1 text-indigo-800">heures</span>
                        </div>
                        <div class="mt-2 text-sm text-indigo-600">
                            @if($averageSleep >= $sleepHabit->target_value)
                                <span class="text-green-600">✓ Objectif atteint</span>
                            @else
                                <span class="text-yellow-600">Objectif : {{ $sleepHabit->target_value }} heures</span>
                            @endif
                        </div>
                    </div>

                    <div class="bg-blue-50 rounded-lg p-6">
                        <h4 class="text-sm font-medium text-blue-800 mb-2">Sommeil profond moyen</h4>
                        <div class="flex items-end">
                            <span class="text-3xl font-bold text-blue-900">{{ number_format($deepSleepAvg, 1) }}</span>
                            <span class="ml-1 text-blue-800">heures</span>
                        </div>
                        <div class="mt-2 text-sm text-blue-600">
                            @php
                                $deepSleepPercent = $averageSleep > 0 ? round(($deepSleepAvg / $averageSleep) * 100) : 0;
                            @endphp
                            {{ $deepSleepPercent }}% de votre sommeil total
                        </div>
                    </div>

                    <div class="bg-purple-50 rounded-lg p-6">
                        <h4 class="text-sm font-medium text-purple-800 mb-2">Qualité du sommeil</h4>
                        <div class="relative pt-1">
                            <div class="overflow-hidden h-6 text-xs flex rounded-full bg-purple-200">
                                @php
                                    $qualityMap = [
                                        'poor' => 25,
                                        'fair' => 50,
                                        'good' => 75,
                                        'excellent' => 100
                                    ];
                                    
                                    $qualityCounts = [
                                        'poor' => 0,
                                        'fair' => 0,
                                        'good' => 0,
                                        'excellent' => 0
                                    ];
                                    
                                    foreach ($logs as $log) {
                                        $notes = json_decode($log->notes, true);
                                        if (isset($notes['quality'])) {
                                            $qualityCounts[$notes['quality']]++;
                                        }
                                    }
                                    
                                    $bestQuality = 'good';
                                    $maxCount = 0;
                                    
                                    foreach ($qualityCounts as $quality => $count) {
                                        if ($count > $maxCount) {
                                            $maxCount = $count;
                                            $bestQuality = $quality;
                                        }
                                    }
                                    
                                    $qualityPercent = $logs->count() > 0 ? $qualityMap[$bestQuality] : 0;
                                @endphp
                                <div style="width: {{ $qualityPercent }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-purple-600">
                                    {{ ucfirst($bestQuality) }}
                                </div>
                            </div>
                        </div>
                        <div class="mt-2 text-sm text-purple-600">
                            Qualité dominante: {{ ucfirst($bestQuality) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historique récent -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Historique de sommeil récent</h3>
                
                @if($logs->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durée</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sommeil profond</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qualité</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($logs as $log)
                                    @php
                                        $notes = json_decode($log->notes, true) ?? [];
                                        $quality = $notes['quality'] ?? 'non spécifié';
                                        $deepSleep = $notes['deep_sleep'] ?? 0;
                                        $textNotes = $notes['notes'] ?? '';
                                        
                                        $qualityColors = [
                                            'poor' => 'bg-red-100 text-red-800',
                                            'fair' => 'bg-yellow-100 text-yellow-800',
                                            'good' => 'bg-green-100 text-green-800',
                                            'excellent' => 'bg-indigo-100 text-indigo-800',
                                        ];
                                        
                                        $qualityClass = $qualityColors[$quality] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ \Carbon\Carbon::parse($log->date)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $log->value }} heures
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $deepSleep }} heures
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $qualityClass }}">
                                                {{ ucfirst($quality) }}
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
                                    Vous n'avez pas encore enregistré de sommeil. Commencez à suivre votre sommeil en utilisant le bouton "Ajouter un suivi".
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