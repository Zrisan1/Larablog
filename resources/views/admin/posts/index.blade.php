@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    @can('admin.posts.create')
        
    <a class="btn btn-secondary float-right" href="{{route('admin.posts.create')}}">Nuevo Post</a>
    @endcan

    <h1>Listado de post</h1>
@stop

@section('content')

        @if(session('info'))
            <div class="alert alert-success">
                <strong>{{session('info')}}</strong>
            </div>
        @endif

        @livewire('admin.posts-index')
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
