<p><b>Webhook - Detalhes Integração Mercado Pago:</b></p>

<b>ID_PENDENCIA_FINANCEIRA:</b> #{{ $financial_id }}<br/>
<b>ID_PAGTO_MP:</b> {{ $pagamento_id }}<br/>

<hr style="border: 1px dashed #eee;"/>

<b>Andamento do Pagamento:</b> <span style="color:{{ $color }}">{{ $mp['status'] }}</span><br/>
<b>Pago em:</b> {{ $pay_day }}<br/>
<b>Forma de Pagamento:</b> {{ $payment_type }}<br/>

<hr style="border: 1px dashed #eee;"/>

<b>Matrícula:</b> {{ $registration_id }}<br/>
<b>Turma:</b> {{ $team }}<br/>
<b>Aluno:</b> <a href="https://validar-certificado.paideiaeducacional.com/admin/alunos/{{ $student_id }}/editar" target="_blank">{{ $student_name }}</a> <br/>
<b>Parcela:</b> {{ $quota }}<br/>
<b>Vencimento:</b> {{ $due_date }} <br/>
<b>Valor:</b> R$ {{ $value }}<br/>
<b>Observação:</b> {{ $observations }}<br/><br/>
