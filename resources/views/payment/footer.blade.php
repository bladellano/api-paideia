
<footer class="bg-dark text-white py-4">
  <div class="container">
    <div class="row">
      <div class="col-md-4">
        <h5 class="text-center">Contato</h5>
        <p class="text-center">
          <i class="fas fa-phone-alt"></i> +55 (91) 3722-9891<br>
          <i class="fas fa-phone-alt"></i> +55 (91) 9 8176-9979<br>
          <i class="fas fa-phone-alt"></i> +55 (31) (91) 9 8208-4651
          <i class="fas fa-phone-alt"></i> contato@paideiaeducacional.com
        </p>
      </div>
      <div class="col-md-4">
        <h5 class="text-center">Endereço</h5>
        <p class="text-center">
          TV WE 17, Cidade Nova 2, N 111 - Coqueiro, Ananindeua - PA, 67130-450, Brasil
        </p>
      </div>
      <div class="col-md-4">
        <h5 class="text-center">Redes Sociais</h5>
        <p class="text-center">
          <a href="https://www.facebook.com/paideiaeducacionalpa" class="text-white"><i class="fab fa-facebook"></i> Facebook</a><br>
          <a href="https://www.instagram.com/paideiaeducacional/" class="text-white"><i class="fab fa-instagram"></i> Instagram</a>
        </p>
      </div>
    </div>
    <div class="text-center mt-3">
      <small>&copy; 2024 - Paideia Educacional. Todos os direitos reservados.</small>
    </div>
  </div>
</footer>

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
                        text: 'Ocorreu um erro ao efetuar o pagamento. Verifique se falta algum dado a ser preenchido e tente novamente.',
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