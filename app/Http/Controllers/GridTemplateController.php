<?php

namespace App\Http\Controllers;

use App\Models\GridTemplate;
use Illuminate\Http\Request;

class GridTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = GridTemplate::query();

        $search   = $request->input('search');
        $sortBy   = $request->input('sortBy');
        $sortDesc = $request->input('sortDesc');

        $perPage = $request->input('perPage') ?? 10;

        $page = $request->input('page') ?? 1;

        if ($search) {
            $query->where('name', 'like', "%$search%");
        }

        if ($sortBy) {
            $query->orderBy($sortBy, $sortDesc ? 'desc' : 'asc');
        }

        $itens = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json($itens);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GridTemplate  $gridTemplate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GridTemplate $gridTemplate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GridTemplate  $gridTemplate
     * @return \Illuminate\Http\Response
     */
    public function destroy(GridTemplate $gridTemplate)
    {
        //
    }
}
