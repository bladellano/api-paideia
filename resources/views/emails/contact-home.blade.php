<x-mail::message>
# Olá, sou o(a) {{ $data['nome'] }}!

<p><b>E-mail:</b> {{ $data['e_mail'] }}</p>
<p><b>Dúvida:</b> {{ $data['doubt'] }}</p>
<p><b>WhatsApp:</b> {{ $data['whatsapp'] }}</p>

<x-mail::button :url="'https://validar-certificado.paideiaeducacional.com/'">
Acesse o Portal Validar Certificado
</x-mail::button>

Obrigado,<br>
{{ $data['nome'] }} 👍
</x-mail::message>
