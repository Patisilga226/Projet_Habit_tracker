@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Statistiques de sommeil') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6 flex justify-end">
            <a href="{{ route('sleep.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring ring-gray-200 disabled:opacity-25 transition ease-in-out duration-150">
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
                    <div class="bg-indigo-50 rounded-lg p-6">
                        <h4 class="text-sm font-medium text-indigo-800 mb-2">Durée moyenne de sommeil</h4>
                        <div class="flex items-end">
                            <span class="text-3xl font-bold text-indigo-900">{{ number_format($averageSleep, 1) }}</span>
                            <span class="ml-1 text-indigo-800">heures</span>
                        </div>
                        <div class="mt-2 text-sm text-indigo-600">
                            @if($averageSleep >= $dailyGoal)
                                <span class="text-green-600">✓ Au-dessus de l'objectif</span>
                            @else
                                <span class="text-yellow-600">{{ round(($averageSleep / $dailyGoal) * 100) }}% de l'objectif ({{ $dailyGoal }}h)</span>
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
                        <h4 class="text-sm font-medium text-purple-800 mb-2">Qualité dominante</h4>
                        <div class="flex items-end">
                            <span class="text-3xl font-bold text-purple-900">{{ ucfirst($dominantQuality) }}</span>
                        </div>
                        <div class="mt-2 text-sm text-purple-600">
                            @php
                                $qualityLabels = [
                                    'poor' => 'Mauvaise',
                                    'fair' => 'Moyenne',
                                    'good' => 'Bonne',
                                    'excellent' => 'Excellente'
                                ];
                            @endphp
                            {{ $dominantQualityPercentage }}% de vos nuits
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tendance sur 30 jours -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Tendance de sommeil (30 derniers jours)</h3>
                @if(empty($dailyData))
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    Aucune donnée disponible. Ajoutez au moins une entrée de sommeil pour voir les tendances.
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <canvas id="sleepTrendChart" class="w-full h-64"></canvas>
                @endif
            </div>
        </div>

        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('sleepTrendChart').getContext('2d');
                
                const chartData = {
                    labels: {!! json_encode(array_keys($dailyData)) !!},
                    datasets: [{
                        label: 'Heures de sommeil',
                        data: {!! json_encode(array_column($dailyData, 'hours')) !!},
                        borderColor: '#4F46E5',
                        tension: 0.4,
                        fill: false
                    }]
                };
        
                new Chart(ctx, {
                    type: 'line',
                    data: chartData,
                    options: {
                        responsive: true,
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: (context) => {
                                        const data = {!! json_encode(array_values($dailyData)) !!}[context.dataIndex];
                                        return [
                                            `Heures: ${data.hours}h`,
                                            `Qualité: ${data.quality}`,
                                            `Sommeil profond: ${data.deep_sleep}h`
                                        ];
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                title: {
                                    display: true,
                                    text: 'Heures'
                                }
                            }
                        }
                    }
                });
            });
        </script>
        @endpush
                
                <div class="h-64 relative">
                    <!-- Ligne de l'objectif -->
                    <div class="absolute w-full border-t border-dashed border-green-500" style="top: {{ 100 - min(100, ($dailyGoal / 12) * 100) }}%">
                        <div class="absolute right-0 -mt-3 -mr-2 bg-green-100 px-2 py-1 rounded text-xs text-green-800">
                            Objectif: {{ $dailyGoal }}h
                        </div>
                    </div>
                    
                    <div class="flex h-full items-end">
                        @foreach($dailyData as $date => $data)
                            @php
                                $height = min(100, ($data['hours'] / 12) * 100);
                                $color = 'bg-indigo-500';
                                
                                if ($data['quality'] === 'excellent') {
                                    $color = 'bg-green-500';
                                } elseif ($data['quality'] === 'good') {
                                    $color = 'bg-blue-500';
                                } elseif ($data['quality'] === 'fair') {
                                    $color = 'bg-yellow-400';
                                } elseif ($data['quality'] === 'poor') {
                                    $color = 'bg-red-400';
                                }
                            @endphp
                            <div class="flex-1 mx-0.5 h-full flex flex-col justify-end items-center relative group">
                                <div class="opacity-0 group-hover:opacity-100 absolute bottom-full mb-1 z-10 bg-gray-900 text-white text-xs rounded py-1 px-2 transform -translate-x-1/2 left-1/2 pointer-events-none transition-opacity duration-200">
                                    <div class="text-center">
                                        {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}<br>
                                        {{ $data['hours'] }}h de sommeil<br>
                                        Qualité: {{ ucfirst($data['quality']) }}
                                    </div>
                                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-t-4 border-r-4 border-l-4 border-transparent border-t-gray-900"></div>
                                </div>
                                <div class="w-full {{ $color }}" style="height: {{ $height }}%"></div>
                                <div class="w-full text-center mt-1">
                                    <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($date)->format('d/m') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="mt-4 w-full border-t border-gray-200 pt-4">
                    <div class="text-sm text-gray-600">
                        <div class="flex items-center justify-center space-x-4">
                            <div class="flex items-center">
                                <span class="w-3 h-3 inline-block bg-green-500 mr-1"></span>
                                <span>Excellente</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-3 h-3 inline-block bg-blue-500 mr-1"></span>
                                <span>Bonne</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-3 h-3 inline-block bg-yellow-400 mr-1"></span>
                                <span>Moyenne</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-3 h-3 inline-block bg-red-400 mr-1"></span>
                                <span>Mauvaise</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Répartition par qualité -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Répartition par qualité de sommeil</h3>
                
                <div class="relative pt-1 w-full mb-6">
                    <div class="flex h-8 overflow-hidden text-xs rounded-full">
                        @php
                            $colors = [
                                'excellent' => 'bg-green-500',
                                'good' => 'bg-blue-500',
                                'fair' => 'bg-yellow-400',
                                'poor' => 'bg-red-400'
                            ];
                        @endphp
                        
                        @foreach($qualityDistribution as $quality => $data)
                            <div 
                                class="{{ $colors[$quality] ?? 'bg-gray-500' }} flex items-center justify-center text-white" 
                                style="width: {{ $data['percentage'] }}%">
                                @if($data['percentage'] > 5)
                                    {{ $data['percentage'] }}%
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-8">
                    @foreach($qualityDistribution as $quality => $data)
                        @php
                            $labels = [
                                'excellent' => 'Excellente',
                                'good' => 'Bonne',
                                'fair' => 'Moyenne',
                                'poor' => 'Mauvaise'
                            ];
                            
                            $bgColors = [
                                'excellent' => 'bg-green-100',
                                'good' => 'bg-blue-100',
                                'fair' => 'bg-yellow-100',
                                'poor' => 'bg-red-100'
                            ];
                            
                            $textColors = [
                                'excellent' => 'text-green-800',
                                'good' => 'text-blue-800',
                                'fair' => 'text-yellow-800',
                                'poor' => 'text-red-800'
                            ];
                        @endphp
                        
                        <div class="{{ $bgColors[$quality] ?? 'bg-gray-100' }} rounded-lg p-4">
                            <h4 class="text-sm font-medium {{ $textColors[$quality] ?? 'text-gray-800' }} mb-2">{{ $labels[$quality] ?? ucfirst($quality) }}</h4>
                            <div class="flex items-end mb-1">
                                <span class="text-xl font-bold {{ $textColors[$quality] ?? 'text-gray-800' }}">{{ $data['count'] }}</span>
                                <span class="ml-1 text-sm {{ $textColors[$quality] ?? 'text-gray-800' }}">nuits</span>
                            </div>
                            <div class="text-sm {{ $textColors[$quality] ?? 'text-gray-800' }}">
                                {{ $data['percentage'] }}% du total
                            </div>
                            <div class="text-sm {{ $textColors[$quality] ?? 'text-gray-800' }}">
                                Moyenne: {{ number_format($data['avgHours'], 1) }}h
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection