@extends('assignment.index')
@section('content')
<form style="width: 23rem;" method="POST" action="{{ route('login') }}">
    @csrf 
    
    <!-- Header -->
    <div class="mb-3 d-flex justify-content-center align-items-center">
        <i class="fa-solid fa-bag-shopping me-2 text-danger" style="font-size: 25px"></i>
        <p class="fs-3 fw-semibold mb-0">SIMS Web App</p>
    </div>

    <!-- Judul form -->
    <p class="fs-3 fw-bold mb-4 text-center">Masuk atau buat akun <br> untuk memulai</p>

    <!-- Input Email -->
    <div class="form-outline mb-4">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" name="email" class="form-control form-control-md" placeholder="Enter your email" required />
        @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>

    <!-- Input Password -->
    <div class="form-outline mb-4">
        <label for="password" class="form-label">Password</label>
        <input type="password" id="password" name="password" class="form-control form-control-md" placeholder="Enter your password" required />
        @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>

    <!-- Tombol Login -->
    <div class="mb-4">
        <button type="submit" class="btn btn-danger btn-lg btn-block w-100">Login</button>
    </div>

    <!-- Lupa password -->
    <p class="small mb-1 pb-lg-2 text-center">
        <a class="text-muted" href="#!">Forgot password?</a>
    </p>

    <!-- Link ke halaman Register -->
    <p class="text-center">Don't have an account? <a href="{{ route('register') }}" class="link-info">Register here</a></p>

</form>
@endsection
