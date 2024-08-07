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
</body>
</html>