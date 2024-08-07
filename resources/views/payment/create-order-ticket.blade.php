@include('payment.header')

<body>
    <div class="container mt-5 mb-5">
      
        <!-- Page heading -->
        <h2 class="text-center mb-4">Efetuar Pagamento com Boleto</h2>

        <!-- Payment form -->
        <form id="paymentForm" action="{{ route('api.payment.orders.store') }}" method="POST">

            @csrf

            <!-- Product or Service Information -->
            <h4>Informação do Produto ou Serviço</h4>
            <input type="hidden" name="code" value="{{ $financial->id }}">
            <input type="hidden" value="{{ $financial->service_type_id }}" name="items[0][code]">
            <input type="hidden" value="{{ $financial->value * 100 }}" name="items[0][amount]">
            <input type="hidden" value="1" name="items[0][quantity]">
            <input type="hidden" name="payments[0][payment_method]" value="boleto">
            <input type="hidden" name="payments[0][boleto][bank]" value="197">
            <input type="hidden" name="payments[0][boleto][instructions]" value="Pagar até o vencimento">
            <input type="hidden" name="payments[0][boleto][due_at]" value="2024-08-10T00:00:00Z">
            <input type="hidden" name="payments[0][boleto][document_number]" value="{{ $financial->id }}">
            <input type="hidden" name="payments[0][boleto][type]" value="DM">

            <input type="hidden" value="CPF" name="customer[document_type]">
            <input type="hidden" value="Individual" name="customer[type]">

            <!-- Service Type and Amount -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="service_type">Tipo do Serviço</label>
                    <input type="text" id="service_type" class="form-control"
                        value="{{ $financial->serviceType->name }}" name="items[0][description]" readonly>
                </div>
                <div class="col-md-6">
                    <label for="amount">Valor</label>
                    <input type="text" id="amount" class="form-control"
                        value="R$ {{ number_format($financial->value, 2, ',', '.') }}" name="items[0][_amount]"
                        disabled>
                </div>
            </div>

            @if (!empty($boletoPDF))
                <div class="row">
                    <div class="col-md-6">
                        Boleto em aberto <a href="{{ $boletoPDF }}" target="_blank" rel="noopener noreferrer" class="btn btn-success">Clique aqui.</a>
                    </div>
                </div>
            @endif

            <!-- Customer Information -->
            <div @class([
                'row',
                'd-none' => !empty($boletoPDF)
            ])>
                <div class="col-md-12">
                    <h4>Informações do Cliente</h4>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="customer_name">Nome</label>
                    <input type="text" id="customer_name" class="form-control" readonly
                        value="{{ $financial->registration->student->name }}" name="customer[name]" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="customer_email">E-mail</label>
                    <input type="email" id="customer_email" class="form-control" readonly
                        value="{{ $financial->registration->student->email }}" name="customer[email]" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="customer_document">CPF</label>
                    <input type="text" id="customer_document" class="form-control" readonly
                        value="{{ $financial->registration->student->cpf }}" name="customer[document]" required>
                </div>

                <div class="col-md-6 mb-3">
                    @php
                        $area_code = preg_replace('/[^0-9]/', '', substr($financial->registration->student->phone, 1, 3));
                        $phone = str_replace('-', '', substr($financial->registration->student->phone, 4));
                    @endphp
                    <label for="customer_phone">Telefone</label>
                    <input type="hidden" value="55" name="customer[phones][mobile_phone][country_code]" required>
                    <input type="hidden" value="{{ $area_code }}" name="customer[phones][mobile_phone][area_code]" required>
                    <input type="hidden" value="{{ $phone }}" name="customer[phones][mobile_phone][number]" required>
                    <input type="text" id="customer_phone" class="form-control" readonly value="{{ $financial->registration->student->phone }}" name="customer[phones][mobile_phone][_number]" disabled>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="country">País</label>
                    <select class="form-control" name="customer[address][country]" required>
                        <option value="BR">Brasil</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="state">Estado</label>
                    <select class="form-control" name="customer[address][state]" required>
                        <option value="">--Selecione--</option>
                        @foreach($states as $code => $state)
                            <option value="{{ $code }}">{{ $state }}</option>
                        @endforeach
                </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="city">Cidade</label>
                    <input type="text" class="form-control" placeholder="Ex.: Ananindeua" name="customer[address][city]" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="address">Endereço</label>
                    <input type="text" class="form-control" value="{{$financial->registration->student->naturalness}}" name="customer[address][line_1]" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="zip_code">CEP</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        placeholder="67035500" 
                        name="customer[address][zip_code]" 
                        maxlength="8"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        required >
                </div>

            </div>

            <!-- Submit button -->
            @empty($boletoPDF)
                <button type="submit" class="btn btn-primary mt-3">Efetuar Pagamento</button>
            @endempty

        </form>
    </div>
</body>

@include('payment.footer')
