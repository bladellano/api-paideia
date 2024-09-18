@extends('layouts.app')

@section('content')
    <h1>Criar Módulo</h1>
    <form action="{{ route('modules.store') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-md-6">
                <label for="nm_module">Nome do Módulo:</label>
                <input class="form-control" type="text" name="nm_module" placeholder="Nome amigável" required>
            </div>
            <div class="col-md-6">
                <label for="nm_module">Nome do Módulo (Máquina):</label>
                <input class="form-control bg-warning" type="text" name="nm_machine" placeholder="Nome do módulo no .env" required>
            </div>
        </div>
        <hr>
        <h3>Menus</h3>

        <div id="menus">
            <div class="row mb-2 menu-item">
                <div class="col-md-6">
                    <input class="form-control" type="text" placeholder="Nome da Ação" name="menus[0][action]" required>
                </div>
                <div class="col-md-5">
                    <input class="form-control" type="text" placeholder="Slug Caminho (vue)" name="menus[0][path]" required> 
                </div>
                <div class="col-md-1">
                    <button class="btn btn-danger btn-sm remove-menu" type="button">Remover</button>
                </div>
            </div>
        </div>

        <button class="btn btn-primary btn-sm" type="button" id="add-menu">Adicionar Menu</button><br><br>
        <button class="btn btn-primary btn-sm" type="submit">Salvar</button>
        
    </form>

    <script>
        let menuCount = 1;

        document.getElementById('add-menu').addEventListener('click', function() {
            const newMenu = document.createElement('div');
            newMenu.classList.add('row', 'mb-2', 'menu-item');
            newMenu.innerHTML = `
                <div class="col-md-6">
                    <input class="form-control" type="text" placeholder="Nome da Ação" name="menus[${menuCount}][action]" required>
                </div>
                <div class="col-md-5">
                    <input class="form-control" type="text" placeholder="Slug Caminho (vue)" name="menus[${menuCount}][path]" required>
                </div>
                <!-- Botão de Remover -->
                <div class="col-md-1">
                    <button class="btn btn-danger btn-sm remove-menu" type="button">Remover</button>
                </div>
            `;
            document.getElementById('menus').appendChild(newMenu);
            menuCount++;
        });

        document.getElementById('menus').addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-menu')) {
                e.target.closest('.menu-item').remove();
            }
        });
    </script>
@endsection
