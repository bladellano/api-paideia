<?php

namespace App\Http\Controllers;

use App\Models\Financial;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FinancialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            $validatedData = $request->validate([
                'registration_id' => 'required',
                'service_type_id' => 'required',
                'value' => 'required',
                'due_date' => 'required',
                'paid' => 'required|boolean',
                'observations' => 'nullable|string',
                'gateway_response' => 'nullable|string',
                'payment_type' => 'required|int',
            ]);

            $document = Financial::create($validatedData);

            return response()->json(['data' => $document, 'message' => 'Registro realizado com sucesso!'], Response::HTTP_CREATED);
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
    public function show(Financial $financial)
    {
        return response()->json($financial);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function update(Request $request, Financial $financial)
    {

        try {

            $validatedData = $request->validate([
                'registration_id' => 'nullable|int',
                'service_type_id' => 'required',
                'value' => 'required',
                'due_date' => 'required',
                'paid' => 'required|boolean',
                'observations' => 'nullable|string',
                'gateway_response' => 'nullable|string',
                'payment_type' => 'required|int',
            ]);

            $updated = $financial->update($validatedData);

            return response()->json(['data' => $updated, 'message' => 'Registro atualizado com sucesso!'], Response::HTTP_OK);
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
    public function destroy(Financial $financial)
    {
        try {
            $financial->delete();
            return response()->json(['message' => 'Registro removido com sucesso.']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
