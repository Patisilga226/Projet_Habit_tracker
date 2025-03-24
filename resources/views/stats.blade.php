@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Statistiques des habitudes</h2>

    <div class="chart-container mb-5">
        <canvas id="progressChart"></canvas>
    </div>

    <div class="chart-container mb-5">
        <canvas id="completionChart"></canvas>
    </div>

    <div class="chart-container">
        <canvas id="streakChart"></canvas>
    </div>
</div>

<style>
.chart-container {
    position: relative;
    height: 400px;
    margin: 20px 0;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/charts.js') }}"></script>
@endsection