@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Statistiques d\'hydratation') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="{{ asset('js/hydration-charts.js') }}"></script>
        <div class="mb-6 flex justify-end">
            <a href="{{ route('hydration.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring ring-gray-200 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour
            </a>
        </div>

        <!-- Vue d'ensemble -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Vue d'ensemble</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-blue-50 rounded-lg p-6">
                        <h4 class="text-sm font-medium text-blue-800 mb-2">Consommation moyenne</h4>
                        <div class="flex items-end">
                            <span class="text-3xl font-bold text-blue-900">{{ $averageIntake }}</span>
                            <span class="ml-1 text-blue-800">ml par jour</span>
                        </div>
                        <div class="mt-2 text-sm text-blue-600">
                            @if($averageIntake >= $dailyGoal)
                                <span class="text-green-600">✓ Au-dessus de l'objectif</span>
                            @else
                                <span class="text-yellow-600">{{ round(($averageIntake / $dailyGoal) * 100) }}% de l'objectif quotidien</span>
                            @endif
                        </div>
                    </div>

                    <div class="bg-indigo-50 rounded-lg p-6">
                        <h4 class="text-sm font-medium text-indigo-800 mb-2">Jours d'objectif atteint</h4>
                        <div class="flex items-end">
                            <span class="text-3xl font-bold text-indigo-900">{{ $goalReachedDays }}</span>
                            <span class="ml-1 text-indigo-800">/ {{ $totalTrackedDays }} jours</span>
                        </div>
                        <div class="mt-2 text-sm text-indigo-600">
                            @php
                                $successRate = $totalTrackedDays > 0 ? round(($goalReachedDays / $totalTrackedDays) * 100) : 0;
                            @endphp
                            Taux de réussite: {{ $successRate }}%
                        </div>
                    </div>

                    <div class="bg-purple-50 rounded-lg p-6">
                        <h4 class="text-sm font-medium text-purple-800 mb-2">Type de boisson préféré</h4>
                        <div class="flex items-end">
                            <span class="text-3xl font-bold text-purple-900">{{ ucfirst($favoriteType) }}</span>
                        </div>
                        <div class="mt-2 text-sm text-purple-600">
                            @php
                                $typeLabels = [
                                    'water' => 'Eau',
                                    'tea' => 'Thé',
                                    'coffee' => 'Café',
                                    'juice' => 'Jus',
                                    'other' => 'Autre'
                                ];
                            @endphp
                            {{ $favoriteTypePercentage }}% de votre consommation
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphique mensuel -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Tendance mensuelle</h3>
                
                <div class="h-64 w-full">
                    <canvas id="monthlyTrendChart" data-monthly-data='{{ json_encode($monthlyData) }}' data-daily-goal='{{ $dailyGoal }}'></canvas>
                </div>
                
                <div class="mt-4 w-full border-t border-gray-200 pt-4">
                    <div class="text-sm text-gray-600">
                        <div class="flex items-center justify-center">
                            <span class="w-3 h-3 inline-block bg-blue-500 mr-1"></span>
                            <span class="mr-4">En dessous de l'objectif</span>
                            <span class="w-3 h-3 inline-block bg-green-500 mr-1"></span>
                            <span>Objectif atteint ou dépassé</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Répartition par type de boisson -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Répartition par type de boisson</h3>
                
                <div class="h-64 w-full mb-4">
                    <canvas id="typeDistributionChart" data-type-distribution='{{ json_encode($typeDistribution) }}'></canvas>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mt-6">
                    @foreach($typeDistribution as $type => $data)
                        @php
                            $labels = [
                                'water' => 'Eau',
                                'tea' => 'Thé',
                                'coffee' => 'Café',
                                'juice' => 'Jus',
                                'other' => 'Autre'
                            ];
                            
                            $bgColors = [
                                'water' => 'bg-blue-100',
                                'tea' => 'bg-green-100',
                                'coffee' => 'bg-yellow-100',
                                'juice' => 'bg-orange-100',
                                'other' => 'bg-gray-100'
                            ];
                            
                            $textColors = [
                                'water' => 'text-blue-800',
                                'tea' => 'text-green-800',
                                'coffee' => 'text-yellow-800',
                                'juice' => 'text-orange-800',
                                'other' => 'text-gray-800'
                            ];
                        @endphp
                        
                        <div class="{{ $bgColors[$type] ?? 'bg-gray-100' }} rounded-lg p-4">
                            <h4 class="text-sm font-medium {{ $textColors[$type] ?? 'text-gray-800' }} mb-2">{{ $labels[$type] ?? ucfirst($type) }}</h4>
                            <div class="flex items-end mb-1">
                                <span class="text-xl font-bold {{ $textColors[$type] ?? 'text-gray-800' }}">{{ $data['total'] }}</span>
                                <span class="ml-1 text-sm {{ $textColors[$type] ?? 'text-gray-800' }}">ml</span>
                            </div>
                            <div class="text-sm {{ $textColors[$type] ?? 'text-gray-800' }}">
                                {{ $data['percentage'] }}% du total
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection