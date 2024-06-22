<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PDF</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }
        #customers {
            border-collapse: collapse;
            width: 100%;
            font-size: 11px;
        }

        #customers td,
        #customers th {
            border: 1px solid #ddd;
            padding: 4px;
        }

        #customers tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #customers tr:hover {
            background-color: #ddd;
        }

        #customers th {
            padding-top: 4px;
            padding-bottom: 2px;
            text-align: left;
            background-color: #999;
            color: white;
            text-transform: uppercase;
        }

        .page-break {
            page-break-after: always;
        }

        .status-quitado {
            color: green;
        }
        .status-em-aberto {
            color: red;
        }

    </style>
</head>

<body>

    <table style="width: 80%">
        <tr>
            <td> <img src="{{ public_path('/logo.png') }}" alt="LOGO - PAIDEIA" width="140px"> </td>
            <td style="text-align: center">
                <h3>Extrato Financeiro</h3>
                <b>ALUNO:</b> {{ $student->name }}<br />
                <b>CPF:</b> {{ $student->cpf }}<br />
            </td>
        </tr>
    </table>

    @foreach ($pages as $page)

        <p><b>TURMA:</b> {{ $page['team_name'] }}</p>

        <table id="customers">
            <tr>
                <th>Descrição</th>
                <th>Valor</th>
                <th>Dt. Venc</th>
                <th>Valor até o venc.</th>
                <th>Situação</th>
                <th>Dt. Pago</th>
                <th>Forma</th>
                <th>Vl. Pago</th>
            </tr>
            @foreach ($page['financials'] as $f)
                <tr>
                    <td>({{ $f['quota'] }}/{{ $f['total_by_service'] }}) {{ Str::upper($f['service_type']['name']) }} - {{ $page['team_name'] }}</td>
                    <td>R$ {{ number_format($f['value'], 2, ',', '.') }}</td>
                    <td>{{ $f['due_date'] }}</td>
                    <td>R$ {{ number_format($f['value'], 2, ',', '.') }}</td>
                    <td class="{{ $f['paid'] ? 'status-quitado' : 'status-em-aberto' }}">
                        {{ $f['paid'] ? 'QUITADO' : 'EM ABERTO' }}
                    </td>
                    <td>{{ $f['pay_day'] }}</td>
                    <td>{{ $f['paid'] ? $f['payment_type']['name'] : '' }}</td>
                    <td>R$ {{ number_format($f['value'], 2, ',', '.') }}</td>
                </tr>
            @endforeach

        </table>

        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach <!-- Fim page -->

</body>

</html>
