@extends('layouts.master')

@section('body')
    <div class="flex h-screen">
        @include('layouts.nav')
        <div class="flex-1 ml-[190px] flex flex-col">
            <x-header />
            <div class="container">
                @yield('content')
            </div>
        </div>
    </div>
@endsection
