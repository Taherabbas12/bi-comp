@extends('admin.layouts.app')



@section('content')
    <div class="container py-4">
        <h2 class="mb-4 text-center fw-bold text-white">💻 إدارة اللابتوبات</h2>

        <!-- أدوات البحث والفلترة -->
        <div class="card mb-4 shadow-sm border-0 rounded-4 bg-dark text-white">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" id="searchInput" class="form-control"
                            placeholder="🔍 ابحث عن اسم، موديل، معالج...">
                    </div>
                    <div class="col-md-2">
                        <input type="number" id="minPrice" class="form-control" placeholder="💰 السعر الأدنى">
                    </div>
                    <div class="col-md-2">
                        <input type="number" id="maxPrice" class="form-control" placeholder="💰 السعر الأعلى">
                    </div>
                    <div class="col-md-2 d-flex align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="hideFinished">
                            <label class="form-check-label" for="hideFinished">❌ إخفاء المنتهية</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ✅ هنا تضيف الكود المطلوب -->
        <div class="row" id="laptopCards">
            @include('admin.laptops.cards', ['laptops' => $laptops])
        </div>

        <!-- روابط الصفحات -->
        <div class="mt-4 d-flex justify-content-center">
            {{ $laptops->links() }}
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        function fetchLaptops() {
            const search = document.getElementById('searchInput').value;
            const min = document.getElementById('minPrice').value;
            const max = document.getElementById('maxPrice').value;
            const hideFinished = document.getElementById('hideFinished').checked;

            fetch(`/admin/laptops/filter?search=${encodeURIComponent(search)}&min=${min}&max=${max}&hideFinished=${hideFinished}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    cache: 'no-cache'
                })
                .then(res => res.text())
                .then(html => {
                    document.getElementById('laptopCards').innerHTML = html;
                })
                .catch(err => console.error('خطأ في جلب البيانات:', err));
        }

        ['searchInput', 'minPrice', 'maxPrice', 'hideFinished'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', fetchLaptops);
                el.addEventListener('change', fetchLaptops);
            }
        });
    </script>
@endsection
