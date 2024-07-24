<!DOCTYPE html>
<html>
<head>
    <title>Efetuar Pagamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .input-icon {
            position: relative;
        }
        .input-icon input {
            padding-left: 40px; /* Espaço para o ícone */
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
            display: none; /* Escondido por padrão */
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999; /* Coloca o loading acima de outros elementos */
        }
    </style>
</head>
<body>
<div class="container mt-5 mb-5">

    <div id="loading">
      <div class="spinner-border" role="status">
          <span class="visually-hidden">Carregando...</span>
      </div>
    </div>
    <h2 class="text-center">Efetuar Pagamento com Cartão de Crédito</h2>

    <form id="paymentForm" action="{{ route('payment.orders.store') }}" method="POST">
        @csrf

        <h4>Informação do Produto ou Serviço</h4>

        <input type="hidden" value="{{ $financial->service_type_id }}" name="items[0][code]">
        <input type="hidden" value="{{ ($financial->value * 100) }}" name="items[0][amount]">
        <input type="hidden" value="1" name="items[0][quantity]">

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="">Tipo do Serviço</label>
                <input type="text" class="form-control" value="{{ $financial->serviceType->name }}" name="items[0][description]" readonly>
            </div>
            <div class="col-md-6">
                <label for="">Valor</label>
                <input type="text" class="form-control" value="R$ {{ number_format($financial->value, 2, ',', '.') }}" name="items[0][_amount]" disabled>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <h4>Informações do Cliente</h4>
                <input type="hidden" name="code" value="{{ $financial->id }}">
                <div class="form-group">
                    <label for="customer_name">Nome</label>
                    <input type="text" class="form-control" readonly value="{{ $financial->registration->student->name }}" name="customer[name]" required>
                </div>
                <div class="form-group">
                    <label for="customer_email">Email</label>
                    <input type="email" class="form-control" readonly value="{{ $financial->registration->student->email }}" name="customer[email]" required>
                </div>
                <div class="form-group">
                    <label for="customer_document">CPF</label>
                    <input type="text" class="form-control" readonly value="{{ $financial->registration->student->cpf }}" name="customer[document]" required>
                    <input type="hidden" value="CPF" name="customer[document_type]">
                    <input type="hidden" value="individual" name="customer[type]">
                </div>
                <div class="form-group">
                    @php
                        $area_code = preg_replace('/[^0-9]/', '', substr($financial->registration->student->phone, 1, 3)); 
                        $phone = str_replace('-', '', substr($financial->registration->student->phone, 4)); 
                    @endphp

                    <label for="customer_phone">Telefone</label>
                    <input type="hidden" value="55" name="customer[phones][mobile_phone][country_code]" required>
                    <input type="hidden" value="{{ $area_code }}" name="customer[phones][mobile_phone][area_code]" required>
                    <input type="hidden" value="{{ $phone }}" name="customer[phones][mobile_phone][number]" required>
                    
                    <input type="text" class="form-control" readonly value="{{ $financial->registration->student->phone }}" name="customer[phones][mobile_phone][_number]" disabled>
                </div>
            </div>

            <div class="col-md-6">
                <h4>Endereço de Cobrança</h4>
                
                <div class="form-group">
                    <label for="billing_address_line_1">Endereço</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        name="payments[0][credit_card][card][billing_address][line_1]" value="{{ $financial->registration->student->naturalness }}" 
                        required
                    >
                </div>
                <div class="form-group">
                    <label for="billing_zip_code">CEP</label>
                    <input type="text" 
                        class="form-control" 
                        placeholder="00000000" 
                        name="payments[0][credit_card][card][billing_address][zip_code]" 
                        required 
                        maxlength="8" 
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                    >
                </div>
              
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="billing_country">País</label>
                        <select class="form-control" name="payments[0][credit_card][card][billing_address][country]" required>
                            <option value="BR">Brasil</option>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="billing_state">Estado</label>
                        <select class="form-control" name="payments[0][credit_card][card][billing_address][state]" required>
                                <option value="">--Selecione--</option>
                                @foreach($states as $code => $state)
                                    <option value="{{ $code }}">{{ $state }}</option>
                                @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="billing_city">Cidade</label>
                    <input type="text" class="form-control" name="payments[0][credit_card][card][billing_address][city]" required>
                </div>
            </div>
        </div>

        <fieldset class="border p-3 mt-4">

            <input type="hidden" name="payments[0][payment_method]" value="credit_card">

            <div class="form-group input-icon mb-3">
                <label for="card_number">Número do Cartão</label>
                <span class="icon"><img src="{{ asset('images/credit-card.png') }}" alt="Card Icon" width="20"></span>
                <input type="text" class="form-control" name="payments[0][credit_card][card][number]" required placeholder="0000000000000000" oninput="this.value = this.value.replace(/\s+/g, '')">
            </div>
            <div class="form-group input-icon mb-3">
                <label for="card_holder_name">Nome no Cartão</label>
                <span class="icon"><img src="{{ asset('images/do-utilizador.png') }}" alt="User Icon" width="20"></span>
                <input type="text" class="form-control" name="payments[0][credit_card][card][holder_name]" required value="{{ mb_strtoupper($financial->registration->student->name) }}">
            </div>
            <div class="form-group input-icon mb-3">
                <label for="card_exp_month">Mês de Expiração</label>
                <span class="icon"><img src="{{ asset('images/calendar.png') }}" alt="Calendar Icon" width="20"></span>
                <input type="text" class="form-control" name="payments[0][credit_card][card][exp_month]" required placeholder="00">
            </div>
            <div class="form-group input-icon mb-3">
                <label for="card_exp_year">Ano de Expiração</label>
                <span class="icon"><img src="{{ asset('images/calendar.png') }}" alt="Calendar Icon" width="20"></span>
                <input type="text" class="form-control" name="payments[0][credit_card][card][exp_year]" required placeholder="00">
            </div>
            <div class="form-group input-icon mb-3">
                <label for="card_cvv">CVV</label>
                <span class="icon"><img src="{{ asset('images/cvv.png') }}" alt="CVV Icon" width="20"></span>
                <input type="text" class="form-control" name="payments[0][credit_card][card][cvv]" required placeholder="000">
            </div>

            <input type="hidden" name="payments[0][credit_card][recurrence]" value="false">
            <input type="hidden" name="payments[0][credit_card][installments]" value="1">
            <input type="hidden" name="payments[0][credit_card][statement_descriptor]" value="{{ $financial->observations }}">
        </fieldset>

        <button type="submit" class="btn btn-primary mt-3">Efetuar Pagamento</button>
    </form>
</div>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
    $(document).ready(function () {

        $('#paymentForm').on('submit', function (event) {

            event.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                beforeSend: () => {
                  $('#loading').show();
                  $('button').prop('disabled', true);
                },
                success: function (response) {

                console.log('response', response);

                  if(response.status == 'paid') {
                      Swal.fire({
                          title: 'Sucesso!',
                          text: 'Pagamento efetuado com sucesso!',
                          icon: 'success',
                          confirmButtonText: 'OK'
                      }).then(() => {
                        document.querySelector('form').reset();
                      });
                  } else {
                    Swal.fire({
                          title: 'Sucesso!',
                          text: 'Transação efetuada com sucesso! Por favor, aguarde até o pagamento ser processado.',
                          icon: 'warning',
                          confirmButtonText: 'OK'
                      }).then(() => {
                        document.querySelector('form').reset();
                      });
                  }
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        title: 'Erro!',
                        text: 'Ocorreu um erro ao efetuar o pagamento. Verifique os dados do cartão. Tente novamente.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    $('button').prop('disabled', false);
                    console.error(xhr.responseText);
                },
                complete: () => {
                  $('#loading').hide();
                }
            });
        });
    });
</script>

</html>
