<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Http\Response;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserRequestUpdate;

class UserController extends Controller
{

    private $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return $this->service->getAll($request);
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {

        try {
            $data = $this->service->create($request->all());
            return response()->json(['data' => $data, 'message' => 'Registro criado com sucesso!'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->service->find($user->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequestUpdate  $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequestUpdate $request, User $user)
    {
        try {
            $data = $this->service->update($user->id, $request->all());
            return response()->json(['data' => $data, 'message' => 'Cadastro atualizado com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        try {
            $this->service->delete($user->id);
            return response()->json(['message' => 'Registro removido com sucesso.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'A remoção desse registro não é viável devido ao fato de que ele já está associado a outra tabela'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
