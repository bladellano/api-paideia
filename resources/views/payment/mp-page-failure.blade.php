@include('payment.header')

<body>
    <div class="container mt-5 mb-5 py-5">
        <div class="alert alert-danger text-center" role="alert">
            <h2 class="alert-heading">Falha no pagamento!</h2>
        </div>
        
        <p class="text-center">Infelizmente, ocorreu um problema ao processar o seu pagamento. Por favor, tente novamente ou entre em contato com o suporte para obter assistência. Pedimos desculpas pelo transtorno.</p>
    </div>
</body>

@include('payment.footer')
