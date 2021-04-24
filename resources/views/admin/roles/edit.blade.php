@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Editar Role</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            {!! Form::model($role, ['route' => ['admin.roles.update', $role], 'method' => 'PUT']) !!}

                @include('admin.roles.partials.form')
                    
                {!! Form::submit('Editar Role',['class' => 'btn btn-warning']) !!}
            {!! Form::close() !!}
        </div>
    </div>
@stop
