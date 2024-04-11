<?php

namespace App\Http\Controllers;

use App\Models\PaymentType;
use App\Repositories\PaymentTypeRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PaymentTypeController extends Controller
{

    private $repository;

    public function __construct(PaymentTypeRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
    * Display a listing of the resource.
    * @param Request $request
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        return $this->repository->getAll($request, 'user');
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     * @param PaymentType $paymentTyp
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentType $paymentType)
    {
        return $paymentType;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentType $paymentType)
    {
        try {
            $paymentType->delete();
            return response()->json(['message' => 'Registro removido com sucesso.']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
