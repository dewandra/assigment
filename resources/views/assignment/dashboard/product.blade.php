@extends('assignment.dashboard.index')

@section('content')
    <!-- Main content section -->
    <div class="mt-4">
        <!-- Title for Products Section -->
        <p class="fs-5 fw-bolder">Produk</p>

        <!-- Search and Filter Section -->
        <div class="row mb-3">
            <!-- Search and Category Filter -->
            <div class="col-6 d-flex">
                <input class="form-control me-2" type="search" id="customSearch" placeholder="Search" aria-label="Search"
                    style="width: 15rem">
                <select class="form-select" name="category_id" id="categoryFilter" style="width: 8rem">
                    <option value="">Kategori</option> <!-- Default Option for Category -->
                    @foreach ($cat as $c)
                        <option value="{{ $c->id }}" class="text-capitalize"
                            data-category-name="{{ $c->name }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Action buttons: Add Category, Export, Add Product -->
            <div class="col-6 d-flex justify-content-end">
                <!-- Add Category Button (opens modal) -->
                <a href="#" class="btn btn-warning me-2 text-white" data-bs-toggle="modal"
                    data-bs-target="#exampleModal">
                    <i class="fas fa-plus me-2"></i>Kategori
                </a>

                <!-- Export to Excel Button -->
                <a href="{{ route('product.export', ['search' => request('search'), 'category_name' => request('category_name')]) }}"
                    class="btn btn-success me-2">
                    <i class="fas fa-file-excel me-2"></i> Export Excel
                </a>


                <!-- Add Product Button -->
                <a href="{{ route('addproduct') }}" class="btn btn-danger">
                    <i class="fas fa-plus me-2"></i>Produk
                </a>
            </div>
        </div>

        <!-- Products Table Section -->
        <div class="container">
            <div class="row">
                <table id="myTable" class="display">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Img</th>
                            <th scope="col">Nama Produk</th>
                            <th scope="col">Kategori Produk</th>
                            <th scope="col">Harga Beli</th>
                            <th scope="col">Harga Jual</th>
                            <th scope="col">Stok</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($product as $p)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td class="text-center">
                                    <!-- Display Product Image -->
                                    @if ($p->image)
                                        <img src="{{ asset('storage/' . $p->image) }}" width="35" height="30"
                                            alt="{{ $p->name }}">
                                    @else
                                        <img src="{{ asset('Assets/placeholder.png') }}" width="35" height="30"
                                            alt="No Image">
                                    @endif
                                </td>
                                <td>{{ $p->name }}</td>
                                <td>{{ $p->category->name }}</td>
                                <td>{{ 'Rp ' . number_format($p->hb, 0, ',', '.') }}</td>
                                <td>{{ 'Rp ' . number_format($p->hj, 0, ',', '.') }}</td>
                                <td>{{ $p->stok }}</td>
                                <td>
                                    <!-- Edit and Delete Actions -->
                                    <a href="{{ route('produk.edit', ['id' => $p->id]) }}" class="text-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="javascript:void(0);" class="text-danger"
                                        onclick="deleteProduct({{ $p->id }})">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal for Adding Category -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Kategori</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Category Form -->
                    <form id="categoryForm" enctype="multipart/form-data">
                        @csrf
                        <div class="col-8 mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="name"
                                placeholder="Nama Kategori">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="saveCategory" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts for Table and AJAX Functions -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        // Initialize DataTable

        $(document).ready(function() {
            var table = $('#myTable').DataTable({
                "lengthMenu": [10],
                "paging": true,
                "searching": true,
                "info": true,
                "lengthChange": false,
                "ordering": true,
                "columnDefs": [{
                    "orderable": false,
                    "targets": [1, 7] // Disable sorting on columns Img and Aksi
                }]
            });

            // Custom Search Function
            $('#customSearch').on('keyup', function() {
                table.search(this.value).draw(); // Custom search using input value
            });

            // Filter by Category Name (not ID)
            $('#categoryFilter').on('change', function() {
                var categoryId = this.value; // Ambil ID kategori yang dipilih
                var categoryName = $(this).find(':selected').data(
                'category-name'); // Ambil nama kategori yang dipilih

                // Kirim nama kategori ke URL untuk filter ekspor
                var currentSearch = $('#customSearch').val();
                var url = "{{ route('product.export') }}?search=" + currentSearch + "&category_name=" +
                    categoryName;

                // Update URL ekspor dengan kategori yang dipilih
                $('.btn-success').attr('href', url);

                // Reset filter jika tidak ada kategori yang dipilih
                var table = $('#myTable').DataTable();
                if (categoryName) {
                    table.column(3).search(categoryName, true, false)
                .draw(); // Filter berdasarkan nama kategori
                } else {
                    table.column(3).search('').draw(); // Reset filter
                }
            });

        });
    </script>

    <script>
        // Save Category via AJAX
        $('#saveCategory').click(function() {
            var formData = new FormData($('#categoryForm')[0]);
            $.ajax({
                url: "{{ route('category.store') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Kategori berhasil ditambahkan.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href =
                                "{{ route('product') }}"; // Redirect to product page
                        }
                    });
                },
                error: function(response) {
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Kategori gagal ditambahkan.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        // Delete Product with confirmation
        function deleteProduct(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This product will be deleted permanently!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('product.delete') }}", // Adjust route to match your controller method
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: id
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                Swal.fire(
                                    'Deleted!',
                                    'The product has been deleted.',
                                    'success'
                                ).then(() => {
                                    window.location
                                        .reload(); // Reload the page to reflect changes
                                });
                            } else {
                                Swal.fire(
                                    'Failed!',
                                    'The product could not be deleted.',
                                    'error'
                                );
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire(
                                'Error!',
                                'An error occurred while trying to delete the product.',
                                'error'
                            );
                        }
                    });
                }
            });
        }
    </script>
@endsection
