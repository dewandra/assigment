@extends('assignment.index')
@section('content')
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" style="width: 23rem">
        @csrf

        <!-- Header  -->
        <div class="mb-3 d-flex justify-content-center align-items-center">
            <i class="fa-solid fa-bag-shopping me-2 text-danger" style="font-size: 25px"></i>
            <p class="fs-4 fw-semibold mb-0">SIMS Web App</p>
        </div>

        <!-- Judul form -->
        <p class="fs-5 fw-bold mb-3 text-center">Buat akun untuk memulai</p>

        <!-- Input Name -->
        <div class="form-outline mb-4">
            <label for="name" class="form-label">Name</label>
            <input type="text" id="name" name="name" class="form-control form-control-md"
                placeholder="Enter your name" required />
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Input Email -->
        <div class="form-outline mb-4">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control form-control-md"
                placeholder="Enter your email" required />
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Input Password -->
        <div class="form-outline mb-4">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control form-control-md"
                placeholder="Enter your password" required />
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Input Confirm Password -->
        <div class="form-outline mb-4">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                class="form-control form-control-md" placeholder="Confirm your password" required />
            @error('password_confirmation')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="mb-4">
            <button type="submit" class="btn btn-danger btn-sm btn-block w-100">Register</button>
        </div>

        <!-- Link to Login -->
        <p>Already registered? <a href="{{ route('login') }}" class="link-info">Login here</a></p>
    </form>
@endsection
