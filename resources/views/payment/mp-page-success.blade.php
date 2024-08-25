@include('payment.header')

<body>
    <div class="container mt-5 mb-5">
        <div class="alert alert-success text-center" role="alert">
            <h2 class="alert-heading">Pagamento realizado com sucesso!</h2>
        </div>

        <p class="text-center"> {{ $financial->id }} | {{ $financial->value }} | {{ $financial->pay_day }}</p>
    </div>
</body>

@include('payment.footer')
