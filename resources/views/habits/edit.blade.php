@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Modifier l\'habitude') }}: {{ $habit->name }}
    </h2>
@endsection

@section('content')
    <form method="POST" action="{{ route('habits.update', $habit) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Nom de l\'habitude') }}</label>
            <input type="text" name="name" id="name" value="{{ old('name', $habit->name) }}" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">{{ __('Description') }}</label>
            <textarea name="description" id="description" rows="3"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description', $habit->description) }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <label for="frequency" class="block text-sm font-medium text-gray-700">{{ __('Fréquence') }}</label>
                <select name="frequency" id="frequency" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="daily" {{ old('frequency', $habit->frequency) == 'daily' ? 'selected' : '' }}>{{ __('Quotidienne') }}</option>
                    <option value="weekly" {{ old('frequency', $habit->frequency) == 'weekly' ? 'selected' : '' }}>{{ __('Hebdomadaire') }}</option>
                    <option value="monthly" {{ old('frequency', $habit->frequency) == 'monthly' ? 'selected' : '' }}>{{ __('Mensuelle') }}</option>
                </select>
                @error('frequency')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="target_value" class="block text-sm font-medium text-gray-700">{{ __('Valeur cible') }}</label>
                <input type="number" name="target_value" id="target_value" value="{{ old('target_value', $habit->target_value) }}" min="1" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('target_value')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="unit" class="block text-sm font-medium text-gray-700">{{ __('Unité') }}</label>
                <input type="text" name="unit" id="unit" value="{{ old('unit', $habit->unit) }}" placeholder="{{ __('ex: fois, minutes, km') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('unit')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="color" class="block text-sm font-medium text-gray-700">{{ __('Couleur') }}</label>
                <select name="color" id="color"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">{{ __('Aucune couleur') }}</option>
                    <option value="blue" {{ old('color', $habit->color) == 'blue' ? 'selected' : '' }}>{{ __('Bleu') }}</option>
                    <option value="green" {{ old('color', $habit->color) == 'green' ? 'selected' : '' }}>{{ __('Vert') }}</option>
                    <option value="red" {{ old('color', $habit->color) == 'red' ? 'selected' : '' }}>{{ __('Rouge') }}</option>
                    <option value="yellow" {{ old('color', $habit->color) == 'yellow' ? 'selected' : '' }}>{{ __('Jaune') }}</option>
                    <option value="purple" {{ old('color', $habit->color) == 'purple' ? 'selected' : '' }}>{{ __('Violet') }}</option>
                    <option value="pink" {{ old('color', $habit->color) == 'pink' ? 'selected' : '' }}>{{ __('Rose') }}</option>
                    <option value="indigo" {{ old('color', $habit->color) == 'indigo' ? 'selected' : '' }}>{{ __('Indigo') }}</option>
                </select>
                @error('color')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="is_active" class="block text-sm font-medium text-gray-700">{{ __('Statut') }}</label>
                <select name="is_active" id="is_active"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="1" {{ old('is_active', $habit->is_active) ? 'selected' : '' }}>{{ __('Active') }}</option>
                    <option value="0" {{ old('is_active', $habit->is_active) ? '' : 'selected' }}>{{ __('Inactive') }}</option>
                </select>
                @error('is_active')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
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
            {{ __('Une fois que vous supprimez une habitude, toutes ses données seront définitivement effacées.') }}
        </p>

        <form method="POST" action="{{ route('habits.destroy', $habit) }}" class="mt-4" onsubmit="return confirm('{{ __('Êtes-vous sûr de vouloir supprimer cette habitude?') }}')">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-900 focus:ring focus:ring-red-300 disabled:opacity-25 transition">
                {{ __('Supprimer cette habitude') }}
            </button>
        </form>
    </div>
@endsection 