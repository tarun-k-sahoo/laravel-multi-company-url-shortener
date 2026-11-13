@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <h3 class="mb-4">Dashboard</h3>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5>Welcome, {{ auth()->user()->name }}</h5>

            <p class="mb-1">
                Role:
                <strong>{{ auth()->user()->role->name ?? 'No Role' }}</strong>
            </p>

            <p class="mb-0">
                Company:
                <strong>{{ auth()->user()->company->name ?? 'Platform Administration' }}</strong>
            </p>
        </div>
    </div>

@endsection
