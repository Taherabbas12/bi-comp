@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="h3">🖥️ لوحة تحكم المشرف</h1>
                    <p class="text-muted mb-0">مرحبًا، {{ auth()->user()->name }}</p>
                </div>
                <p class="text-muted mb-4">نظرة عامة على الحالة العامة للنظام</p>

                <div class="row g-4 mb-4">
                    <!-- عدد الأجهزة -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card text-white bg-dark border-light text-center h-100">
                            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                <i class="bi bi-laptop" style="font-size: 3rem; color: var(--accent);"></i>
                                <h5 class="card-title mt-3">عدد الأجهزة</h5>
                                <p class="card-text display-6 mb-0">{{ \App\Models\Laptop::count() }}</p>
                            </div>
                        </div>
                    </div>
                    <!-- عدد الطلبات -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card text-white bg-dark border-light text-center h-100">
                            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                <i class="bi bi-cart-check" style="font-size: 3rem; color: var(--success);"></i>
                                <h5 class="card-title mt-3">عدد الطلبات</h5>
                                <p class="card-text display-6 mb-0">{{ \App\Models\Order::count() }}</p>
                            </div>
                        </div>
                    </div>
                    <!-- عدد المستخدمين -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card text-white bg-dark border-light text-center h-100">
                            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                <i class="bi bi-people" style="font-size: 3rem; color: var(--warning);"></i>
                                <h5 class="card-title mt-3">عدد المستخدمين</h5>
                                <p class="card-text display-6 mb-0">{{ \App\Models\User::count() }}</p>
                            </div>
                        </div>
                    </div>
                    <!-- عدد الأدوار -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card text-white bg-dark border-light text-center h-100">
                            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                <i class="bi bi-person-badge" style="font-size: 3rem; color: var(--danger);"></i>
                                <h5 class="card-title mt-3">عدد الأدوار</h5>
                                <p class="card-text display-6 mb-0">{{ \App\Models\Role::count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- أحدث الطلبات -->
                    <div class="col-12 col-lg-6">
                        <div class="card text-white bg-dark border-light">
                            <div class="card-header">
                                <h5 class="mb-0">أحدث الطلبات</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    @forelse(\App\Models\Order::latest()->take(5)->get() as $order)
                                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent text-white border-light">
                                            <div>
                                                <strong>{{ $order->customer_name }}</strong><br>
                                                <small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                                            </div>
                                            <span class="badge bg-primary rounded-pill">{{ $order->status->display_name ?? 'غير معروف' }}</span>
                                        </li>
                                    @empty
                                        <li class="list-group-item text-center text-muted bg-transparent border-0">لا توجد طلبات حديثة</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- أحدث الأجهزة -->
                    <div class="col-12 col-lg-6">
                        <div class="card text-white bg-dark border-light">
                            <div class="card-header">
                                <h5 class="mb-0">أحدث الأجهزة</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    @forelse(\App\Models\Laptop::latest()->take(5)->get() as $laptop)
                                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent text-white border-light">
                                            <div>
                                                <strong>{{ $laptop->name }}</strong><br>
                                                <small class="text-muted">{{ $laptop->brand ?? 'غير معروف' }}</small>
                                            </div>
                                            <span class="badge bg-secondary rounded-pill">{{ $laptop->quantity }}</span>
                                        </li>
                                    @empty
                                        <li class="list-group-item text-center text-muted bg-transparent border-0">لا توجد أجهزة حديثة</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection