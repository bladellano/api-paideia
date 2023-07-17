<?php

namespace App\Http\Controllers;

use App\Models\GridTemplate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repositories\GridTemplateRepository;

class GridTemplateController extends Controller
{

    private $repository;

    public function __construct(GridTemplateRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->repository->getAll($request);
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            foreach($request->all() as $rowTemplate):
                GridTemplate::create($rowTemplate);
            endforeach;

            return response()->json(['data'=> $request->all(), 'message' => 'Registro criado com sucesso!'], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json(['error'=>true,'message'=>$e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     * @param  \App\Models\GridTemplate  $gridTemplate
     * @return \Illuminate\Http\Response
     */
    public function show(GridTemplate $gridTemplate)
    {
        $gridTemplate->grid;
        $gridTemplate->course;
        $gridTemplate->stage;
        $gridTemplate->discipline;
        return response()->json([$gridTemplate]);
    }

}
