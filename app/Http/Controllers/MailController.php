<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\ContactHomeMailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MailController extends Controller
{
    /**
     * Write code on Method
     * @return response()
     */
    public function index(Request $request)
    {
        $data = $request->all();

        try {
            Mail::to('diretor@paideiaeducacional.com')
                ->cc('dellanosites@gmail.com')
                ->send(new ContactHomeMailable($data));
            return response()->json(['message' => 'E-mail enviado com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Falha ao tentar enviar e-mail. Tente novamente mais tarde.'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
