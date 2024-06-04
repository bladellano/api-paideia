<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Document;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class DocumentController extends Controller
{
    public function verifyBlobDocumentPDF(string $folder, string $filename)
    {
        $path = storage_path("/app/{$folder}/" . $filename);
        if (file_exists($path)):
            return response()->file($path, ['Content-Type' => 'application/pdf']);
        else:
            return response()->json(['error'=> true, 'message'=> 'Falha ao retornar um blob pdf'], 404);
        endif;
    }
    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeDocument(Request $request, Student $student)
    {
        $file = $request->file('pdf');

        $partials = [];

        if(!empty($request->input('registration')))
            $partials[] = $request->input('registration');

        if(true)
            $partials[] = $student->cpf;

        if(!empty($request->input('team')))
            $partials[] = strtoupper(Str::slug($request->input('team')));

        if(!empty($request->input('document_name')))
            $partials[] = strtoupper(Str::slug($request->input('document_name'))) . ".pdf";

        $joinNames = implode('_', $partials);
        $filePath = Storage::putFileAs($request->input('folder'), $file, $joinNames);

        if(Storage::exists($filePath)) {

            Document::create([
                'path' => $filePath,
                'code' => $request->input('code'),
                'type' => $request->input('type'),
                'student_id' => $student->id
            ]);

            return response()->json(['data' => $filePath, 'message' => 'Registro criado com sucesso!'], 201);
        } else {
            return response()->json(['error' => true, 'message' => 'Falha ao criar pdf'], 500);
        }
    }

    public function destroy(string $folder, string $filename)
    {
        $fileToDelete = "{$folder}/". $filename;

        if (Storage::exists($fileToDelete)):
            Storage::delete($fileToDelete);

            $record = Document::where('path', $fileToDelete);
            $record->delete();

            return response()->json(['data' => $fileToDelete, 'message' => 'Arquivo removido com sucesso!'], 200);
        else:
            return response()->json(['error' => true, 'message' => 'Lamentamos, mas ocorreu um problema ao remover o arquivo. Parece que o arquivo já foi excluído anteriormente.'], 405);
        endif;
    }

    public function hasDocument(string $code)
    {
        $document = Document::where('code', $code);
        $document->with('student');

        $record = $document->get();

        if(count($record)) {
            return response()->json($record);
        } else {
            return response()->json(['error' => true, 'message' => 'Não foi encontrado nenhum documento válido com este código.'], 404);
        }
    }

    public function update(Request $request, Document $document)
    {
        try {
            $document->student_id = $request->student_id;
            $document->save();

            return response()->json(['data' => $document, 'message' => 'Registro atualizado com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message'=> $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

}
