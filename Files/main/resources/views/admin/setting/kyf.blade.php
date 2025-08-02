@extends('admin.layouts.master')

@section('master')
    <form action="" method="post">
        @csrf

        @include('admin.partials.formData')
    </form>

    <x-formGenerator/>
@endsection
