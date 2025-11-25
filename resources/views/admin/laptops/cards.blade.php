@forelse($laptops as $laptop)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 shadow-sm border-0 rounded-4 bg-dark text-white laptop-card">
            <div class="card-body">
                <h5 class="card-title text-white fw-bold">{{ $laptop->name }}</h5>
                <ul class="list-unstyled small">
                    <li><strong>العلامة:</strong> {{ $laptop->brand ?? 'غير محددة' }}</li>
                    <li><strong>الموديل:</strong> {{ $laptop->model ?? 'غير محدد' }}</li>
                    <li><strong>المعالج:</strong> {{ $laptop->processor ?? 'غير محدد' }}</li>
                    <li><strong>الرام:</strong> {{ $laptop->ram ?? 'غير محددة' }}</li>
                    <li><strong>السعة:</strong> {{ $laptop->storage ?? 'غير محددة' }}</li>
                    <li><strong>الشاشة:</strong> {{ $laptop->screen ?? 'غير محددة' }}</li>
                    <li><strong>كرت الشاشة:</strong> {{ $laptop->gpu ?? 'غير محدد' }}</li>
                    <li><strong>السعر:</strong> <span class="text-success">{{ $laptop->price_display }}</span></li>
                    <li><strong>الكمية:</strong>
                        @if ($laptop->quantity > 0)
                            <span class="badge bg-success">{{ $laptop->quantity }}</span>
                        @else
                            <span class="badge bg-danger">منتهية</span>
                        @endif
                    </li>
                </ul>
            </div>
            <div class="card-footer bg-transparent border-0 text-end">
                <a href="{{ route('admin.laptops.edit', $laptop) }}" class="btn btn-sm btn-outline-light">✏️ تعديل</a>
                <form action="{{ route('admin.laptops.destroy', $laptop) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('هل أنت متأكد من الحذف؟')">🗑️
                        حذف</button>
                </form>
            </div>
        </div>
    </div>
@empty
    <div class="col-12">
        <div class="alert alert-warning text-center">لا توجد نتائج مطابقة للبحث.</div>
    </div>
@endforelse
