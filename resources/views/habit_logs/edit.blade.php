@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Modifier le suivi du') }} {{ $log->date->format('d/m/Y') }} {{ __('pour') }}: {{ $habit->name }}
    </h2>
@endsection

@section('content')
    <form method="POST" action="{{ route('habit_logs.update', [$habit, $log]) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="date" class="block text-sm font-medium text-gray-700">{{ __('Date') }}</label>
            <input type="date" name="date" id="date" value="{{ old('date', $log->date->format('Y-m-d')) }}" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            @error('date')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="value" class="block text-sm font-medium text-gray-700">
                {{ __('Valeur') }} 
                @if($habit->unit)
                    ({{ $habit->unit }})
                @endif
            </label>
            <input type="number" name="value" id="value" value="{{ old('value', $log->value) }}" required min="0" step="any"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            <div class="mt-1 text-sm text-gray-500">
                {{ __('Objectif') }}: {{ $habit->target_value }} {{ $habit->unit ?: __('fois') }}
            </div>
            @error('value')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="notes" class="block text-sm font-medium text-gray-700">{{ __('Notes (optionnel)') }}</label>
            <textarea name="notes" id="notes" rows="3"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('notes', $log->notes) }}</textarea>
            @error('notes')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('habits.show', $habit) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                {{ __('Annuler') }}
            </a>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                {{ __('Mettre à jour') }}
            </button>
        </div>
    </form>

    <div class="mt-6 pt-6 border-t border-gray-200">
        <h3 class="text-lg font-medium text-red-600">{{ __('Zone dangereuse') }}</h3>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Une fois que vous supprimez un suivi, il ne peut pas être récupéré.') }}
        </p>

        <form method="POST" action="{{ route('habit_logs.destroy', [$habit, $log]) }}" class="mt-4" onsubmit="return confirm('{{ __('Êtes-vous sûr de vouloir supprimer ce suivi?') }}')">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-900 focus:ring focus:ring-red-300 disabled:opacity-25 transition">
                {{ __('Supprimer ce suivi') }}
            </button>
        </form>
    </div>
@endsection 