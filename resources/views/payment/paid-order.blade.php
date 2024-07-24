<!DOCTYPE html>
<html>
<head>
    <title>Pagamento Já Realizado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5 mb-5">
    <div class="card">
        <div class="card-header text-center">
            <h2>Pagamento Já Realizado</h2>
        </div>
        <div class="card-body text-center">
            <p class="text-primary">O pagamento para o serviço {{ $financial->serviceType->name }} no valor de R$ {{ number_format($financial->value, 2, ',', '.') }} já foi efetuado em {{ $financial->pay_day->translatedFormat('d \d\e F \d\e Y') }}.</p>
            <p>Se você acredita que isso é um engano, por favor entre em contato com nosso suporte.</p>
            <small class="text-secondary">(91) 3722-9891 / (91) 9 8176-9979 / (91) 9 8208-4651 / contato@paideiaeducacional.com</small>
        </div>
    </div>
</div>
</body>
</html>
