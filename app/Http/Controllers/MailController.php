<?php

namespace App\Http\Controllers;

use App\Mail\PaiDeiaMail;
use App\Http\Controllers\Controller;
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
            Mail::to('diretor@paideiaeducacional.com')->send(new PaiDeiaMail($data));
            return response()->json(['message' => 'E-mail enviado com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Falha ao tentar entrar em contato. Tente novamente mais tarde.'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
