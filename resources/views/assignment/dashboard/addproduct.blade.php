@extends('assignment.dashboard.index')

@section('content')
    <div class="mt-4">
        <!-- Title Section -->
        <p class="fs-5 fw-bolder">
            <span class="fw-lighter" style="color: #babcbd;">Daftar Produk</span> > Tambah Produk
        </p>

        <!-- Product Form -->
        <form id="productForm" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- Category Selection -->
                <div class="col-4 mb-3">
                    <label for="floatingSelect" class="form-label">Kategori</label>
                    <select class="form-select" name="category_id" id="floatingSelect">
                        @foreach ($cat as $c)
                            <option value="{{ $c->id }}" class="text-capitalize">{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Product Name -->
                <div class="col-8 mb-3">
                    <label for="name" class="form-label">Nama Barang</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Nama barang">
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Harga Beli -->
                <div class="col-4 mb-3">
                    <label for="hb" class="form-label">Harga Beli</label>
                    <input type="number" class="form-control" name="hb" id="hb" placeholder="Harga beli">
                    @error('hb')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Harga Jual (Readonly) -->
                <div class="col-4 mb-3">
                    <label for="hj" class="form-label">Harga Jual</label>
                    <input type="number" class="form-control" name="hj" id="hj"
                        placeholder="Harga Jual"readonly>
                    @error('hj')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Stok Barang -->
                <div class="col-4 mb-3">
                    <label for="stok" class="form-label">Stok Barang</label>
                    <input type="number" class="form-control" name="stok" id="stok" placeholder="Stok barang">
                    @error('stok')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Upload Image -->
                <div class="col mb-3">
                    <label for="imageUpload" class="form-label">Upload Image</label>
                    <input type="file" class="form-control" name="image" id="imageUpload" accept="image/*">
                    @error('image')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Image Preview -->
                <div class="col mb-3">
                    <img name="image" id="imagePreview" src="" alt="Image Preview"
                        style="width: 200px; height: auto; display: none;">
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-end">
                    <a href="{{ route('product') }}" class="btn btn-outline-danger me-3">Batalkan</a>
                    <button type="button" id="submitProduk" class="btn btn-danger d-flex">Submit</button>
                </div>
            </div>
        </form>
    </div>

    {{-- JavaScript --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        // Function to calculate Harga Jual automatically
        $('#hb').on('input', function() {
            const hargaBeli = parseFloat($(this).val()); // Get harga beli value

            if (!isNaN(hargaBeli)) {
                const markup = 0.3; // 30% markup
                const hargaJual = hargaBeli + (hargaBeli * markup); // Calculate harga jual
                $('#hj').val(hargaJual.toFixed(2)); // Update harga jual with 2 decimals
            } else {
                $('#hj').val(''); // Clear harga jual if harga beli is invalid
            }
        });

        // Image Preview Function
        document.getElementById("imageUpload").addEventListener("change", function(event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                const imgElement = document.getElementById("imagePreview");
                imgElement.src = e.target.result;
                imgElement.style.display = "block"; // Show the image preview
            }

            if (file) {
                reader.readAsDataURL(file); // Read the file as Data URL
            }
        });

        // Submit Product Form via AJAX
        $('#submitProduk').click(function() {
            const formData = new FormData($('#productForm')[0]);

            $.ajax({
                url: "{{ route('produk.store') }}",
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
                        text: 'Produk berhasil ditambahkan.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href =
                                "{{ route('product') }}"; // Redirect to product list
                        }
                    });
                },
                error: function(response) {
                    console.log(response);

                    // Hapus pesan error yang lama
                    $('#category').next('.text-danger').remove();
                    $('#name').next('.text-danger').remove();
                    $('#hb').next('.text-danger').remove();
                    $('#hj').next('.text-danger').remove();
                    $('#stok').next('.text-danger').remove();
                    $('#imageUpload').next('.text-danger').remove();

                    // Kalau ada error di image, tampilkan pesan errornya
                    if (response.responseJSON.errors.image) {
                        const errorMessage = response.responseJSON.errors.image[0];
                        // Tampilkan pesan error di bawah input gambar
                        $('#imageUpload').after(`<div class="text-danger">${errorMessage}</div>`);
                    }

                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Produk gagal ditambahkan. \n' + JSON.stringify(response
                            .responseJSON.errors),
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    </script>
@endsection
