<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function verifyBlobDocumentPDF(string $folder, string $filename)
    {
        $path = storage_path("/app/{$folder}/" . $filename);
        if (file_exists($path)):
            return response()->file($path, ['Content-Type' => 'application/pdf']);
        else:
            return response()->json(['error'=>true, 'message'=> 'Falha ao retornar um blob pdf'], 404);
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
        $filePath = Storage::putFileAs($request->input('folder'), $file, "{$student->cpf}_{$request->input('document_name')}.pdf");

        if(Storage::exists($filePath)) {

            Document::create([
                'path' => $filePath,
                'code' => $request->input('code'),
                'type' => $request->input('type'),
                'student_id' => $student->id
            ]);

            return response()->json(['data'=> $filePath, 'message' => 'Registro criado com sucesso!'], 201);
        } else {
            return response()->json(['error'=>true, 'message'=> 'Falha ao criar pdf '], 500);
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

}
