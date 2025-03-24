@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Tableau de bord') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Vue d'ensemble -->
        <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Mes Habitudes</h2>
                    <p class="text-gray-600 mb-4">Vous avez <span class="font-bold text-indigo-600">{{ Auth::user()->habits()->count() }}</span> habitudes en tout.</p>
                    <a href="{{ route('habits.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Voir toutes mes habitudes
                    </a>
                </div>
            </div>
            
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Habitudes Actives</h2>
                    <p class="text-gray-600 mb-4">Vous avez <span class="font-bold text-green-600">{{ Auth::user()->habits()->where('is_active', true)->count() }}</span> habitudes actives.</p>
                    <a href="{{ route('habits.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Ajouter une nouvelle habitude
                    </a>
                </div>
            </div>
            
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Statistiques</h2>
                    <p class="text-gray-600 mb-4">Analysez vos progrès et visualisez votre évolution.</p>
                    <a href="{{ route('stats.index') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 active:bg-purple-700 focus:outline-none focus:border-purple-700 focus:ring ring-purple-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Voir mes statistiques
                    </a>
                </div>
            </div>
        </div>

        <!-- Suivi spécialisé -->
        <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold text-gray-800">Suivi du Sommeil</h2>
                        <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-600 mb-4">Suivez votre sommeil quotidien et améliorez vos habitudes de repos.</p>
                    <div class="flex space-x-2">
                        <a href="{{ route('sleep.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Voir mon sommeil
                        </a>
                        <a href="{{ route('sleep.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-100 border border-transparent rounded-md font-semibold text-xs text-indigo-700 uppercase tracking-widest hover:bg-indigo-200 active:bg-indigo-200 focus:outline-none focus:border-indigo-700 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Ajouter
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold text-gray-800">Suivi de l'Hydratation</h2>
                        <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </div>
                    <p class="text-gray-600 mb-4">Suivez votre consommation d'eau quotidienne et restez hydraté.</p>
                    <div class="flex space-x-2">
                        <a href="{{ route('hydration.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Voir mon hydratation
                        </a>
                        <a href="{{ route('hydration.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-100 border border-transparent rounded-md font-semibold text-xs text-blue-700 uppercase tracking-widest hover:bg-blue-200 active:bg-blue-200 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Ajouter
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Habitudes pour aujourd'hui -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Habitudes pour aujourd\'hui') }}</h3>
                
                @php
                    $todayHabits = Auth::user()->habits()
                        ->where('is_active', true)
                        ->whereNotIn('type', [App\Models\Habit::TYPE_SLEEP, App\Models\Habit::TYPE_HYDRATION])
                        ->get();
                    
                    $today = now()->format('Y-m-d');
                @endphp
                
                @if($todayHabits->isEmpty())
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    {{ __('Vous n\'avez pas encore d\'habitudes à suivre aujourd\'hui.') }}
                                    <a href="{{ route('habits.create') }}" class="font-medium underline text-yellow-700 hover:text-yellow-600">
                                        {{ __('Commencer à suivre une nouvelle habitude') }}
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="overflow-hidden bg-white shadow sm:rounded-lg">
                        <ul class="divide-y divide-gray-200">
                            @foreach($todayHabits as $habit)
                                @php
                                    $todayLog = $habit->logs()->where('date', $today)->first();
                                    $completed = $todayLog && $todayLog->value >= $habit->target_value;
                                    $progress = $habit->target_value > 0 ? ($todayLog ? min(100, ($todayLog->value / $habit->target_value) * 100) : 0) : 0;
                                @endphp
                                
                                <li class="p-4 flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full {{ $habit->color ? 'bg-'.$habit->color.'-100' : 'bg-gray-100' }} flex items-center justify-center">
                                            @if($completed)
                                                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            @else
                                                <span class="text-sm font-medium {{ $habit->color ? 'text-'.$habit->color.'-800' : 'text-gray-800' }}">
                                                    {{ strtoupper(substr($habit->name, 0, 1)) }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $habit->name }}</div>
                                            <div class="text-xs text-gray-500">
                                                @if($todayLog)
                                                    {{ $todayLog->value }} {{ $habit->unit ?? 'fois' }} / {{ $habit->target_value }} {{ $habit->unit ?? 'fois' }}
                                                @else
                                                    0 {{ $habit->unit ?? 'fois' }} / {{ $habit->target_value }} {{ $habit->unit ?? 'fois' }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <div class="w-48 bg-gray-200 rounded-full h-2.5 mr-4">
                                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $progress }}%"></div>
                                        </div>
                                        
                                        <form action="{{ route('habit_logs.quick_log', $habit) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center p-2 border border-transparent rounded-full shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
