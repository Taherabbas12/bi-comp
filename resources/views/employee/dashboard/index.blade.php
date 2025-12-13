@extends('layouts.employee-layout')

@section('title', 'الرئيسية')

@section('content')

    <h2 class="mb-4">مرحباً {{ auth()->user()->name }} 👋</h2>

    <div class="row g-3">

        {{-- إجمالي المهام --}}
        <div class="col-md-3">
            <div class="card shadow-sm text-center p-3">
                <h5>إجمالي المهام</h5>
                <h2 class="text-primary">{{ $total }}</h2>
            </div>
        </div>

        {{-- المكتملة --}}
        <div class="col-md-3">
            <div class="card shadow-sm text-center p-3">
                <h5>المهام المكتملة</h5>
                <h2 class="text-success">{{ $completed }}</h2>
            </div>
        </div>

        {{-- الجاري --}}
        <div class="col-md-3">
            <div class="card shadow-sm text-center p-3">
                <h5>قيد التنفيذ</h5>
                <h2 class="text-warning">{{ $inProgress }}</h2>
            </div>
        </div>

        {{-- المتأخرة --}}
        <div class="col-md-3">
            <div class="card shadow-sm text-center p-3">
                <h5>المهام المتأخرة</h5>
                <h2 class="text-danger">{{ $delayed }}</h2>
            </div>
        </div>
    </div>

    <hr class="my-4">

    <h4>آخر التحديثات</h4>

    <ul class="list-group">
        @forelse($lastUpdates as $task)
            <li class="list-group-item d-flex justify-content-between">
                <span>{{ $task->title }}</span>
                <small>{{ $task->updated_at->diffForHumans() }}</small>
            </li>
        @empty
            <li class="list-group-item">لا توجد تحديثات.</li>
        @endforelse
    </ul>

@endsection
