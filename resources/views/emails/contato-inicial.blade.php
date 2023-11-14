<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }

        h1 {
            color: #333;
        }

        p {
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Olá, sou o(a) {{ $data['nome'] }}!</h1>

        <p><b>E-mail:</b> {{ $data['e_mail'] }}</p>
        <p><b>Duvida:</b> {{ $data['doubt'] }}</p>
        <p><b>WhatApp:</b> {{ $data['whatsapp'] }}</p>

        <p>Atenciosamente,</p>
        <p>{{ $data['nome'] }} 😘</p>
    </div>
</body>

</html>