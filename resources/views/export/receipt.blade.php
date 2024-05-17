
<div style="text-align: center">
    <img src="{{public_path('/logo.png')}}" alt="LOGO - PAIDEIA" width="220px">
    <h3>RECIBO DE PAGAMENTO</h3>

</div>

<p>Recebi(emos) de: <strong>{{ strtoupper($financial->registration->student->name) }}</strong> a importância de R$ {{ $financial->valueFormated }} ({{ $financial->inFull }}) referente a(o) {{ strtoupper($financial->serviceType->name) }} de {{ $financial->due_date->translatedFormat('d \d\e F \d\e Y') }}.</p>

<p>{{ $financial->course }}</p>

<p>Para maior clareza firmo (amos) o presente recibo para que produza os seus efeitos, dando plena, rasa e irrevogável quitação, pelo valor recebido.</p>

<p><strong>Obs: {{ $financial->observations }}</strong></p>

<p>{{ ucfirst($financial->currentDate->translatedFormat('l, d \d\e F \d\e Y')) }}.</p>



<div style="text-align: center">
    <p>____________________________________________</p>
    Paideia Educacional<br/>
    CNPJ 32.599.936/0001-77

</div>