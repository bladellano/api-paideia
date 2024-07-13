<table>
    <tr>
        <td border="1" style="border:1px solid #000; padding:10px">

            <div style="text-align: center">
                <img src="{{public_path('/logo.png')}}" alt="LOGO - PAIDEIA" width="180px">
                <p>RESOLUÇÃO CEE/PA N° 90 DE 27 DE MARÇO DE 2023<br/>INEP - 15176266</p>
            </div>

            <h3 style="text-align:center; padding-top:100px;"><u>ATESTADO DE CONCLUSÃO</u></h3>

            <p style="text-align:justify">
                Declaramos para os devidos fins que o(a) aluno(a) <b>{{ mb_strtoupper($student->name) }}</b>, CPF: <b>{{ $student->cpf }}</b>, natural de <b>{{ $student->naturalness }}</b>, nascido(a) no dia <b>{{ mb_strtoupper($student->birth_date->translatedFormat('d \d\e F \d\e Y')) }}</b>, concluiu com aproveitamento satisfatório em  <b>{{ mb_strtoupper(\Carbon\Carbon::now()->translatedFormat('F \d\e Y'), 'UTF-8') }}</b> o curso de <b>{{ mb_strtoupper($student->course) }}</b>.
            </p>

            <p>Por se verdade, firmo a presente declaração.</p>

            <p style="text-align:right">Ananindeua/PA, {{ \Carbon\Carbon::now()->translatedFormat('d \d\e F \d\e Y') }}.</p>

            <p style="padding:100px 0px; text-align:center">
                ________________________________________________<br/>
                Rosangela Maria Silva dos Santos<br/>Diretora Pedagógica
            </p>

            <p style="font-size:12px; text-align:center">
                Endereço: TV WE 17, Cidade Nova 2, N 111 - Coqueiro, Ananindeua - PA, 67130-450<br/>
                www.paideiaeducacional.com<br/>
                Tel/Cel/What: 91 3722-9891 / 9 8176-9979 / 91 9 8208-4651
            </p>
        
        </td>
    </tr>
</table>

<div style="page-break-after: always"></div>

<table style="width:98%;">
    <tr>
        <td border="1" style="border:1px solid #000; padding:10px;">
            <h4 style="text-align:center">OBSERVAÇÃO: Este documento possui validade de 30 dias, a partir da data da emissão.</h4>
        </td>
    </tr>
</table>