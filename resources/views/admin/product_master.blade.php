@extends('layouts.app')

@section('content')

<!-- 1. POPUP SUCCESS MESSAGE -->
@if(session('success'))
    <script>
        alert("{{ session('success') }}");
    </script>
@endif


<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 text-dark">Product Master Report</h5>
        <small class="text-secondary"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a> / Product Master Report</small>
    </div>
    <a href="{{ route('admin.product.add') }}" class="btn text-white rounded-pill px-4" style="background-color: #5c6bc0; border: none;">
        <i class="fas fa-plus-circle"></i> Add Products
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body">
        
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label" style="font-weight: 600; font-size: 13px;">Filter by Category:</label>
                <!-- ADDED id="categoryFilter" -->
                <select id="categoryFilter" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="Software">Software</option>
                    <option value="Hardware">Hardware</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <!-- ADDED id="productTable" -->
            <table id="productTable" class="table table-bordered table-hover align-middle " style="font-size: 13px;">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        <th><input type="checkbox" id="select_all" class="form-check-input"></th>
                        <th>#</th>
                        <th>PRODUCT NAME</th>
                        <th>CATEGORY</th>
                        <th>HARDWARE DETAILS</th>
                        <th>SOFTWARE DETAILS</th>
                        <th>DESCRIPTION</th>
                        <th>AMOUNT</th>
                        <th>GST (%)</th>
                        <th>CREATED DATE</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                   @forelse($products as $index => $product)
                    <tr id="tr_{{ $product->id }}">
                        <td><input type="checkbox" class="form-check-input select-item" value="{{ $product->id }}"></td>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $product->productname }}</td>
                        <td>{{ $product->category }}</td>
                        <td>{{ $product->hardware_details ?? '-' }}</td>
                        <td>{{ $product->software_details ?? '-' }}</td>
                        <td>{{ $product->description ?? '-' }}</td>
                        <td>{{ number_format($product->amt, 2) }}</td>
                        <td>{{ $product->gst }}</td>
                        <td>{{ $product->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-sm btn-outline-primary py-0 px-2">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <form action="{{ route('admin.product.delete', $product->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center text-danger py-4">No products found in the database.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="text-end mt-3">
             <button type="button" class="btn btn-danger btn-sm rounded" id="delete_selected_btn">Delete Selected</button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Make sure DataTables is included -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // 1. Initialize DataTable (if not already done globally)
    var table = $('#productTable').DataTable();

    // 2. CATEGORY FILTER LOGIC
    // Column 3 is the "CATEGORY" column (0-indexed: Checkbox=0, #=1, Name=2, Category=3)
    $('#categoryFilter').on('change', function() {
        var category = $(this).val();
        table.column(3).search(category).draw();
    });

    // 3. Select All Checkbox logic
    $('#select_all').on('click', function() {
        $('.select-item').prop('checked', this.checked);
    });

    // 4. Bulk Delete via AJAX
    $('#delete_selected_btn').on('click', function() {
        let ids = [];
        $('.select-item:checked').each(function() {
            ids.push($(this).val());
        });

        if (ids.length <= 0) {
            alert("Please select at least one row.");
        } else {
            if (confirm("Are you sure you want to delete selected products?")) {
                $.ajax({
                    url: "{{ route('admin.product.deleteSelected') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        ids: ids
                    },
                    success: function (response) {
                        alert(response.success);
                        location.reload(); 
                    },
                    error: function (err) {
                        alert("Something went wrong.");
                    }
                });
            }
        }
    });
});
</script>
@endsection