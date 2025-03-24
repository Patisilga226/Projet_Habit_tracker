@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Ajouter une nouvelle habitude') }}
    </h2>
@endsection

@section('content')
    <form method="POST" action="{{ route('habits.store') }}">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Nom de l\'habitude') }}</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">{{ __('Description') }}</label>
            <textarea name="description" id="description" rows="3"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description') }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <label for="frequency" class="block text-sm font-medium text-gray-700">{{ __('Fréquence') }}</label>
                <select name="frequency" id="frequency" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="daily" {{ old('frequency') == 'daily' ? 'selected' : '' }}>{{ __('Quotidienne') }}</option>
                    <option value="weekly" {{ old('frequency') == 'weekly' ? 'selected' : '' }}>{{ __('Hebdomadaire') }}</option>
                    <option value="monthly" {{ old('frequency') == 'monthly' ? 'selected' : '' }}>{{ __('Mensuelle') }}</option>
                </select>
                @error('frequency')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="target_value" class="block text-sm font-medium text-gray-700">{{ __('Valeur cible') }}</label>
                <input type="number" name="target_value" id="target_value" value="{{ old('target_value', 1) }}" min="1" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('target_value')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="unit" class="block text-sm font-medium text-gray-700">{{ __('Unité') }}</label>
                <input type="text" name="unit" id="unit" value="{{ old('unit') }}" placeholder="{{ __('ex: fois, minutes, km') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('unit')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <label for="color" class="block text-sm font-medium text-gray-700">{{ __('Couleur') }}</label>
            <select name="color" id="color"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="">{{ __('Aucune couleur') }}</option>
                <option value="blue" {{ old('color') == 'blue' ? 'selected' : '' }}>{{ __('Bleu') }}</option>
                <option value="green" {{ old('color') == 'green' ? 'selected' : '' }}>{{ __('Vert') }}</option>
                <option value="red" {{ old('color') == 'red' ? 'selected' : '' }}>{{ __('Rouge') }}</option>
                <option value="yellow" {{ old('color') == 'yellow' ? 'selected' : '' }}>{{ __('Jaune') }}</option>
                <option value="purple" {{ old('color') == 'purple' ? 'selected' : '' }}>{{ __('Violet') }}</option>
                <option value="pink" {{ old('color') == 'pink' ? 'selected' : '' }}>{{ __('Rose') }}</option>
                <option value="indigo" {{ old('color') == 'indigo' ? 'selected' : '' }}>{{ __('Indigo') }}</option>
            </select>
            @error('color')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('habits.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                {{ __('Annuler') }}
            </a>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                {{ __('Créer l\'habitude') }}
            </button>
        </div>
    </form>
@endsection 