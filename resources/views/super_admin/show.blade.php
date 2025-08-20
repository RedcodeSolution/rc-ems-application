@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Super Admin Details</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Super Admin ID: {{ $superAdmin->super_admin_id }}</h5>
            <p class="card-text">Super Admin Name: {{ $superAdmin->super_admin_name }}</p>
        </div>
    </div>
    <a href="{{ route('super_admins.index') }}" class="btn btn-secondary mt-3">Back to Super Admins</a>
</div>
@endsection
