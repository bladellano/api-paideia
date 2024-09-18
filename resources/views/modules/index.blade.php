@extends('layouts.app')

@section('content')
    <h1>Módulos</h1>
    <a class="btn btn-success btn-sm" href="{{ route('modules.create') }}">Criar Módulo</a>
    <ul class="list-group mt-2">
        @foreach($modules as $module)
            <li class="list-group-item">{{ $module->nm_module }}
                <ul>
                    @foreach($module->moduleMenus as $menu)
                        <li>{{ $menu->action }} [{{ $menu->path }}]</li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
@endsection
