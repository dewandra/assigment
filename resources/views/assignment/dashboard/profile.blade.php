@extends('assignment.dashboard.index')

@section('content')
    <div class="mt-4">
        <!-- User profile image -->
        <img src="{{ asset('Assets/Frame 98700.png') }}" class="rounded-circle mb-4" alt="user">

        <!-- User name -->
        <p class="fs-5 fw-bold text-uppercase">{{ Auth::user()->name }}</p>

        <!-- User details in two columns -->
        <div class="row">
            <!-- Candidate name -->
            <div class="col-6">
                <p class="fs-6 fw-normal">Nama Kandidat</p>
                <p class="fs-6 fw-lighter text-capitalize">{{ Auth::user()->name }}</p>
            </div>

            <!-- Candidate position -->
            <div class="col-6">
                <p class="fs-6 fw-normal">Posisi Kandidat</p>
                <p class="fs-6 fw-lighter">&lt;/&gt; Website Programmer</p>
            </div>
        </div>
    </div>
@endsection
