
<div style="text-align: center">
    <img src="{{public_path('/logo.png')}}" alt="LOGO - PAIDEIA" width="220px">
    <h2>RECIBO DE PAGAMENTO</h2>

</div>

<p>Recebi(emos) de: <strong>{{ strtoupper($financial->registration->student->name) }}</strong> a importância de R$ {{ $financial->value }} (cento e cinquenta reais) referente a mensalidades de maio de 2023.</p>

<p>{{ $financial->course }}</p>

<p>Para maior clareza firmo (amos) o presente recibo para que produza os seus efeitos, dando plena, rasa e irrevogável quitação, pelo valor recebido.</p>

<p><strong>Obs: {{ $financial->observations }}</strong></p>

<p>{{ ucfirst($financial->currentDate->translatedFormat('l, d \d\e F \d\e Y')) }}.</p>
