<!DOCTYPE html>
<html>

<head>
    <title>Bem-vindo ao Sistema de Pagamentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        .input-icon {
            position: relative;
        }

        .input-icon input {
            padding-left: 40px;
            /* Espaço para o ícone */
        }

        .input-icon .icon {
            position: absolute;
            left: 10px;
            top: 64%;
            transform: translateY(-50%);
            pointer-events: none;
        }

        fieldset {
            background: azure;
        }

        /* Estilo do Spinner */
        #loading {
            display: none;
            /* Escondido por padrão */
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
            /* Coloca o loading acima de outros elementos */
        }

        /* Estilo para o Hero com efeito Parallax */
        .hero {
            height: 350px;
            background-image: url({{ asset('images/hero-banner.png') }});
            background-attachment: fixed;
            background-position: bottom;
            background-repeat: no-repeat;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            position: relative;
        }

        .hero::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(68, 134, 59, 0.5);

        }

        .hero h1 {
            position: relative;
            z-index: 1;
            font-weight: bold;
        }
    </style>
</head>

<div class="hero">
    <h1>Bem-vindo ao Sistema de Pagamentos</h1>
</div>

<!-- Loading spinner -->
<div id="loading" class="text-center mb-4">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Carregando...</span>
    </div>
</div>
