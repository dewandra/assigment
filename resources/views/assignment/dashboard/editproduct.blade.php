@extends('assignment.dashboard.index')

@section('content')
<div class="mt-4">
    <!-- Breadcrumbs -->
    <p class="fs-5 fw-bolder">
        <span class="fw-lighter" style="color: #babcbd;">Daftar Produk</span> > Edit Produk
    </p>

    <!-- Form Edit Produk -->
    <form id="editProductForm" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{ $product->id }}">

        <!-- Input Fields -->
        <div class="row">
            <!-- Kategori -->
            <div class="col-4 mb-3">
                <label for="floatingSelect" class="form-label">Kategori</label>
                <select class="form-select" name="category_id" id="floatingSelect">
                    @foreach ($cat as $c)
                        <option value="{{ $c->id }}" class="text-capitalize" 
                            {{ $product->category_id == $c->id ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Nama Barang -->
            <div class="col-8 mb-3">
                <label for="name" class="form-label">Nama Barang</label>
                <input type="text" class="form-control" name="name" id="name" value="{{ $product->name }}" placeholder="Nama barang">
            </div>

            <!-- Harga Beli -->
            <div class="col-4 mb-3">
                <label for="hb" class="form-label">Harga Beli</label>
                <input type="number" class="form-control" name="hb" id="hb" value="{{ $product->hb }}" placeholder="Harga beli">
            </div>

            <!-- Harga Jual -->
            <div class="col-4 mb-3">
                <label for="hj" class="form-label">Harga Jual</label>
                <input type="number" class="form-control" name="hj" id="hj" value="{{ $product->hj }}" placeholder="Harga jual" readonly>
            </div>

            <!-- Stok Barang -->
            <div class="col-4 mb-3">
                <label for="stok" class="form-label">Stok Barang</label>
                <input type="number" class="form-control" name="stok" id="stok" value="{{ $product->stok }}" placeholder="Stok barang">
            </div>

            <!-- Upload Image -->
            <div class="col mb-3">
                <label for="imageUpload" class="form-label">Upload Image</label>
                <input type="file" class="form-control" name="image" id="imageUpload" accept="image/*">
            </div>

            <!-- Image Preview -->
            <div class="col mb-3">
                <img id="imagePreview" src="{{ asset('storage/' . $product->image) }}" alt="Image Preview" style="width: 200px; height: auto;">
            </div>

            <!-- Form Actions -->
            <div class="d-flex justify-content-end">
                <a href="{{ route('product') }}" class="btn btn-outline-danger me-3">Batalkan</a>
                <button type="button" id="updateProduct" class="btn btn-danger">Update</button>
            </div>
        </div>
    </form>
</div>

<!-- Script untuk Handling Input dan Update -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    // Menghitung harga jual berdasarkan harga beli
    $('#hb').on('input', function() {
        var hargaBeli = parseFloat($(this).val());
        if (!isNaN(hargaBeli)) {
            var markup = 0.3; // 30% markup
            var hargaJual = hargaBeli + (hargaBeli * markup);
            $('#hj').val(hargaJual.toFixed(2)); // Format harga jual menjadi dua desimal
        } else {
            $('#hj').val(''); // Jika input harga beli tidak valid
        }
    });

    // Menampilkan preview gambar saat diupload
    document.getElementById("imageUpload").addEventListener("change", function(event) {
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            const imgElement = document.getElementById("imagePreview");
            imgElement.src = e.target.result;
            imgElement.style.display = "block"; // Menampilkan gambar setelah diupload
        }

        if (file) {
            reader.readAsDataURL(file);
        }
    });

    // Menangani proses update produk
    $('#updateProduct').click(function() {
        var formData = new FormData($('#editProductForm')[0]);

        $.ajax({
            url: "{{ route('product.update', ['id' => $product->id]) }}",
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
                    text: 'Produk berhasil diperbarui.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('product') }}";
                    }
                });
            },
            error: function(response) {
                Swal.fire({
                    title: 'Gagal!',
                    text: 'Produk gagal diperbarui. \n' + JSON.stringify(response.responseJSON.errors),
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
</script>
@endsection
