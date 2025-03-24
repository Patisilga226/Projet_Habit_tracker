@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $habit->name }}
        </h2>
        <div class="flex space-x-2">
            <a href="{{ route('habits.edit', $habit) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                {{ __('Modifier') }}
            </a>
            <a href="{{ route('stats.show', $habit) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                {{ __('Statistiques') }}
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6 {{ $habit->color ? 'bg-'.$habit->color.'-50' : 'bg-white' }} border-b border-gray-200">
            <div class="mb-4">
                <span class="inline-flex items-center px-3 py-1 text-xs rounded-full {{ $habit->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ $habit->is_active ? __('Active') : __('Inactive') }}
                </span>
                <span class="inline-flex items-center px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-800 ml-2">
                    @if($habit->frequency == 'daily')
                        {{ __('Quotidienne') }}
                    @elseif($habit->frequency == 'weekly')
                        {{ __('Hebdomadaire') }}
                    @else
                        {{ __('Mensuelle') }}
                    @endif
                </span>
            </div>

            @if($habit->description)
                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">{{ __('Description') }}</h3>
                    <p class="text-gray-800">{{ $habit->description }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">{{ __('Objectif') }}</h3>
                    <p class="text-gray-800">{{ $habit->target_value }} {{ $habit->unit ?: __('fois') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">{{ __('Date de création') }}</h3>
                    <p class="text-gray-800">{{ $habit->created_at->format('d/m/Y') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">{{ __('Dernière mise à jour') }}</h3>
                    <p class="text-gray-800">{{ $habit->updated_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-6 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-800">{{ __('Suivi de l\'habitude') }}</h3>
        <a href="{{ route('habit_logs.create', $habit) }}" class="inline-flex items-center px-3 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-900 focus:ring focus:ring-green-300 disabled:opacity-25 transition">
            {{ __('Ajouter un suivi') }}
        </a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            @if($logs->isEmpty())
                <div class="text-center py-4">
                    <p class="text-gray-500">{{ __('Aucun suivi enregistré pour cette habitude.') }}</p>
                    <p class="mt-2">
                        <a href="{{ route('habit_logs.create', $habit) }}" class="text-indigo-600 hover:text-indigo-800">
                            {{ __('Commencer à enregistrer votre progression') }} →
                        </a>
                    </p>
                </div>
            @else
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
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Actions') }}
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
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('habit_logs.edit', [$habit, $log]) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">{{ __('Modifier') }}</a>
                                        <form action="{{ route('habit_logs.destroy', [$habit, $log]) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Êtes-vous sûr de vouloir supprimer ce suivi?') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">{{ __('Supprimer') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection 