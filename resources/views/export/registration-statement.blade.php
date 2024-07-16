<table>
    <tr>
        <td border="1" style="border:1px solid #000; padding:10px">

            <div style="text-align: center">
                <img src="{{ public_path('/logo.png') }}" alt="LOGO - PAIDEIA" width="180px">
                <p>RESOLUÇÃO CEE/PA N° 90 DE 27 DE MARÇO DE 2023<br />INEP - 15176266</p>
            </div>

            <h3 style="text-align:center; padding-top:100px;"><u>DECLARAÇÃO DE MATRÍCULA</u></h3>

            <p style="text-align:justify">
                Declaramos para os devidos fins que o(a) aluno(a) <b>{{ mb_strtoupper($student->name) }}</b>, CPF: <b>{{ $student->cpf }}</b>, natural de <b>{{ $student->naturalness }}</b>, nascido(a) no dia <b>{{ mb_strtoupper($student->birth_date->translatedFormat('d \d\e F \d\e Y')) }}</b>, encontra-se regularmente matriculado(a) no <b>{{ mb_strtoupper($student->course) }}</b>.
            </p>

            <p style="text-align:justify">
                Outrossim informamos que o(a) referido(a) aluno(a) é de boa conduta escolar e ficamos a sua inteira disposição para mais informações
            </p>

            <p style="text-align:right">Ananindeua/PA, {{ \Carbon\Carbon::now()->translatedFormat('d \d\e F \d\e Y') }}.
            </p>

            <p style="padding:100px 0px; text-align:center">
                <img style="margin-bottom: -20px" src="{{ public_path('/rosangela-santos-assinatura.png') }}" alt="LOGO - PAIDEIA" width="180px"><br/>
                ________________________________________________<br />
                Rosangela Santos<br />Diretora Pedagógica
            </p>

            <h4 style="text-align:center">Obs.: Esta DECLARAÇÃO possui validade de 30 (trinta) dias, contados a partir da data de sua emissão.</h4>

            <p style="font-size:12px; text-align:center">
                Tel/Cel/What: 91 3722-9891 / 9 8176-9979 / 91 9 8208-4651<br />
                www.paideiaeducacional.com
            </p>

        </td>
    </tr>
</table>
