<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }
        /* Estilo para a tabela */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* Estilo para a tabela com bordas */
        table.table-bordered {
            border: 1px solid #ddd;
        }

        /* Estilo para as células da tabela com bordas */
        table.table-bordered th,
        table.table-bordered td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        /* Estilo para a tabela listrada */
        table.table-striped tbody tr:nth-of-type(odd) {
            background-color: #e4f1fb;
        }

        /* Estilo para o cabeçalho da tabela */
        table th {
            background-color: #305496;
            color: #fff;
            text-align: left;
            padding: 8px;
        }
    </style>

</head>

<body>

    <div class="container mt-5">
        <table>
            <tr>
                <td><img src="{{ public_path('/logo.png') }}" alt="LOGO - PAIDEIA" width="180px"></td>
                <td>
                    <h2>RELATÓRIO DE INADIMPLENTES</h2>
                </td>
                <td><b>Data:</b> {{ \Carbon\Carbon::parse($request->start_date)->format('d/m/Y') }} -
                    {{ \Carbon\Carbon::parse($request->end_date)->format('d/m/Y') }}<br /><b>Total de Títulos:</b>
                    {{ $defaulters->count() }}
                </td>
            </tr>
        </table>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Descrição</th>
                    <th>Valor R$</th>
                    <th>Vencimento</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalValue = 0;
                @endphp

                @foreach ($defaulters as $defaulter)

                @php
                    $quota = str_pad($defaulter->quota ?? '00', 2, '0', STR_PAD_LEFT);
                    $totalValue += $defaulter->value;
                @endphp

                    <tr>
                        <td>Nome: {{ $defaulter->name }}<br>Telefone: {{ $defaulter->phone }}<br />E-mail: {{ $defaulter->email }}</td>
                        <td>{{ $quota }} - {{ $defaulter->service_type_name }}<br />
                            Turma: {{ $defaulter->team_name }}<br/>
                            {{ mb_strtoupper(\Carbon\Carbon::parse($defaulter->due_date)->locale('pt_BR')->translatedFormat('F/Y')) }}<br />
                            {{ $defaulter->observations }}
                        </td>
                        <td>{{ number_format($defaulter->value, 2, ',', '.') }}</td>
                        <td>{{ \Carbon\Carbon::parse($defaulter->due_date)->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2"><b>Total</b></td>
                    <td colspan="2"><b>{{ number_format($totalValue, 2, ',', '.') }}</b></td>
                </tr>
            </tfoot>
        </table>
    </div>

</body>

</html>