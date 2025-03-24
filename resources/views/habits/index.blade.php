@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mes Habitudes') }}
        </h2>
        <a href="{{ route('habits.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            {{ __('Ajouter une habitude') }}
        </a>
    </div>
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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($habits as $habit)
                <div class="border rounded-lg overflow-hidden bg-white shadow hover:shadow-md transition">
                    <div class="p-4 border-b {{ $habit->color ? 'bg-'.$habit->color.'-100' : 'bg-gray-50' }}">
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg font-semibold text-gray-800">{{ $habit->name }}</h3>
                            <span class="px-2 py-1 text-xs rounded-full {{ $habit->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $habit->is_active ? __('Active') : __('Inactive') }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ $habit->description ?: __('Aucune description') }}
                        </p>
                    </div>

                    <div class="p-4">
                        <div class="flex flex-wrap gap-2 text-sm text-gray-600 mb-3">
                            <span class="inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                @if($habit->frequency == 'daily')
                                    {{ __('Quotidienne') }}
                                @elseif($habit->frequency == 'weekly')
                                    {{ __('Hebdomadaire') }}
                                @else
                                    {{ __('Mensuelle') }}
                                @endif
                            </span>
                            <span class="inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                {{ __('Objectif') }}: {{ $habit->target_value }} {{ $habit->unit ?: __('fois') }}
                            </span>
                        </div>

                        <div class="mt-4 flex justify-between">
                            <div>
                                <a href="{{ route('habits.show', $habit) }}" class="inline-flex items-center px-3 py-1 bg-indigo-100 border border-transparent rounded-md font-medium text-xs text-indigo-800 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                                    {{ __('Détails') }}
                                </a>
                                <a href="{{ route('habits.edit', $habit) }}" class="inline-flex items-center px-3 py-1 bg-gray-100 border border-transparent rounded-md font-medium text-xs text-gray-800 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                                    {{ __('Modifier') }}
                                </a>
                            </div>
                            <form action="{{ route('habit_logs.quick_log', $habit) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-3 py-1 bg-green-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
                                    {{ __('Compléter') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection 