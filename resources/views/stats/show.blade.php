@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Statistiques pour') }}: {{ $habit->name }}
        </h2>
        <a href="{{ route('habits.show', $habit) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
            {{ __('Retour à l\'habitude') }}
        </a>
    </div>
@endsection

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6 {{ $habit->color ? 'bg-'.$habit->color.'-50' : 'bg-white' }} border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">{{ __('Entrées totales') }}</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalLogs }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">{{ __('Objectifs atteints') }}</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $completedLogs }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">{{ __('Taux de réussite') }}</h3>
                    <div class="flex items-center">
                        <p class="text-2xl font-bold text-gray-800">{{ $completionRate }}%</p>
                        <div class="ml-2 w-24 bg-gray-200 rounded-full h-2.5">
                            @php
                                $color = $completionRate >= 80 ? 'bg-green-600' : ($completionRate >= 50 ? 'bg-yellow-600' : 'bg-red-600');
                            @endphp
                            <div class="{{ $color }} h-2.5 rounded-full" style="width: {{ $completionRate }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($charts['error']))
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        {{ __('Les graphiques n\'ont pas pu être générés.') }}
                        @if(app()->environment('local'))
                            <br>{{ $charts['error'] }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-4 border-b border-gray-200">
                <h4 class="text-md font-medium text-gray-800">{{ __('Progression au fil du temps') }}</h4>
            </div>
            <div class="p-4">
                @if(isset($charts['progress']))
                    <img src="{{ $charts['progress'] }}" alt="{{ __('Graphique de progression') }}" class="w-full h-auto">
                @else
                    <div class="flex items-center justify-center h-64 bg-gray-100 rounded">
                        <p class="text-gray-500">{{ __('Pas assez de données pour générer un graphique') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-4 border-b border-gray-200">
                <h4 class="text-md font-medium text-gray-800">{{ __('Analyse mensuelle') }}</h4>
            </div>
            <div class="p-4">
                @if(isset($charts['monthly']))
                    <img src="{{ $charts['monthly'] }}" alt="{{ __('Graphique mensuel') }}" class="w-full h-auto">
                @else
                    <div class="flex items-center justify-center h-64 bg-gray-100 rounded">
                        <p class="text-gray-500">{{ __('Pas assez de données pour générer un graphique') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-4 border-b border-gray-200">
            <h4 class="text-md font-medium text-gray-800">{{ __('Heatmap de performance') }}</h4>
        </div>
        <div class="p-4">
            @if(isset($charts['heatmap']))
                <img src="{{ $charts['heatmap'] }}" alt="{{ __('Heatmap') }}" class="w-full h-auto">
            @else
                <div class="flex items-center justify-center h-64 bg-gray-100 rounded">
                    <p class="text-gray-500">{{ __('Pas assez de données pour générer un graphique') }}</p>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-8 mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Historique des suivis') }}</h3>
        
        @if($logs->isEmpty())
            <div class="text-center py-4 bg-white rounded-lg shadow">
                <p class="text-gray-500">{{ __('Aucun suivi enregistré pour cette habitude.') }}</p>
                <p class="mt-2">
                    <a href="{{ route('habit_logs.create', $habit) }}" class="text-indigo-600 hover:text-indigo-800">
                        {{ __('Commencer à enregistrer votre progression') }} →
                    </a>
                </p>
            </div>
        @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Date') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Valeur') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Progression') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Notes') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($logs as $log)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $log->date->format('d/m/Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $log->value }} {{ $habit->unit ?: __('fois') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            @php
                                                $percentage = min(100, ($log->value / $habit->target_value) * 100);
                                                $color = $percentage >= 100 ? 'bg-green-600' : 'bg-blue-600';
                                            @endphp
                                            <div class="{{ $color }} h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ round($percentage) }}% {{ $percentage >= 100 ? '✓' : '' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-500">{{ $log->notes ?: '-' }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection 