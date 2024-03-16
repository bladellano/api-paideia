<?php

namespace App\Http\Controllers;

use App\Models\TextDocument;
use App\Repositories\TextDocumentRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TextDocumentController extends Controller
{

    private $repository;

    public function __construct(TextDocumentRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->repository->getAll($request, 'teaching');
    }

    public function show(TextDocument $textDocument)
    {
        return $textDocument;
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
                'name' => 'required|string',
                'content' => 'required|string',
                'teaching_id' => 'required|int',
            ]);

            $document = TextDocument::create($validatedData);

            return response()->json(['data' => $document, 'message' => 'Registro criado com sucesso!'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TextDocument  $textDocument
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TextDocument $textDocument)
    {
        try {

            $validatedData = $request->validate([
                'name' => 'required|string',
                'content' => 'required|string',
                'teaching_id' => 'required|int',
            ]);

            $textDocument->update($validatedData);

            return response()->json(['data' => $validatedData, 'message' => 'Registro atualizado com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TextDocument  $textDocument
     * @return \Illuminate\Http\Response
     */
    public function destroy(TextDocument $textDocument)
    {
        try {
            $textDocument->delete();
            return response()->json(['message' => 'Registro removido com sucesso.']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
