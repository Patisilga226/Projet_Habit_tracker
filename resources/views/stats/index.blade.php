@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Statistiques et analyses') }}
    </h2>
@endsection

@section('content')
    @if($habits->isEmpty())
        <div class="text-center py-6">
            <p class="text-gray-500 mb-4">{{ __('Vous n\'avez pas encore créé d\'habitudes.') }}</p>
            <a href="{{ route('habits.create') }}" class="text-indigo-600 hover:text-indigo-800">
                {{ __('Commencer à suivre une nouvelle habitude') }} →
            </a>
        </div>
    @else
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Progression générale') }}</h3>
            
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

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 border-b border-gray-200">
                        <h4 class="text-md font-medium text-gray-800">{{ __('Évolution au fil du temps') }}</h4>
                    </div>
                    <div class="p-4">
                        @if(isset($charts['overall']))
                            <img src="{{ $charts['overall'] }}" alt="{{ __('Graphique de progression') }}" class="w-full h-auto">
                        @else
                            <div class="flex items-center justify-center h-64 bg-gray-100 rounded">
                                <p class="text-gray-500">{{ __('Pas assez de données pour générer un graphique') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 border-b border-gray-200">
                        <h4 class="text-md font-medium text-gray-800">{{ __('Taux de complétion') }}</h4>
                    </div>
                    <div class="p-4">
                        @if(isset($charts['completion_rate']))
                            <img src="{{ $charts['completion_rate'] }}" alt="{{ __('Graphique de taux de complétion') }}" class="w-full h-auto">
                        @else
                            <div class="flex items-center justify-center h-64 bg-gray-100 rounded">
                                <p class="text-gray-500">{{ __('Pas assez de données pour générer un graphique') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 border-b border-gray-200">
                    <h4 class="text-md font-medium text-gray-800">{{ __('Analyse des séquences') }}</h4>
                </div>
                <div class="p-4">
                    @if(isset($charts['streak']))
                        <img src="{{ $charts['streak'] }}" alt="{{ __('Graphique d\'analyse des séquences') }}" class="w-full h-auto">
                    @else
                        <div class="flex items-center justify-center h-64 bg-gray-100 rounded">
                            <p class="text-gray-500">{{ __('Pas assez de données pour générer un graphique') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-8 mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Résumé par habitude') }}</h3>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Habitude') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Fréquence') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Entrées totales') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Objectifs atteints') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Taux de réussite') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($habits as $habit)
                                @php
                                    $stats = $habitStats[$habit->id] ?? null;
                                @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full {{ $habit->color ? 'bg-'.$habit->color.'-100' : 'bg-gray-100' }} flex items-center justify-center">
                                                <span class="text-sm font-medium {{ $habit->color ? 'text-'.$habit->color.'-800' : 'text-gray-800' }}">
                                                    {{ strtoupper(substr($habit->name, 0, 1)) }}
                                                </span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $habit->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $habit->target_value }} {{ $habit->unit ?: __('fois') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            @if($habit->frequency == 'daily')
                                                {{ __('Quotidienne') }}
                                            @elseif($habit->frequency == 'weekly')
                                                {{ __('Hebdomadaire') }}
                                            @else
                                                {{ __('Mensuelle') }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $stats ? $stats['totalLogs'] : 0 }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $stats ? $stats['completedLogs'] : 0 }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($stats && $stats['totalLogs'] > 0)
                                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                @php
                                                    $color = $stats['completionRate'] >= 80 ? 'bg-green-600' : ($stats['completionRate'] >= 50 ? 'bg-yellow-600' : 'bg-red-600');
                                                @endphp
                                                <div class="{{ $color }} h-2.5 rounded-full" style="width: {{ $stats['completionRate'] }}%"></div>
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $stats['completionRate'] }}%
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-500">{{ __('N/A') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('stats.show', $habit) }}" class="text-indigo-600 hover:text-indigo-900">
                                            {{ __('Détails') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection 