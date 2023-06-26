<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HistoryController extends Controller
{
    public function storeHistoryPDF(Request $request, Student $student)
    {
        $file = $request->file('pdf');
        $filePath = Storage::putFileAs('history', $file, "{$student->cpf}_historico.pdf");

        if(Storage::exists($filePath)) {

            Document::create([
                'path' => $filePath,
                'code' => $request->input('code'),
                'type' => 'HISTORIC',
                'student_id' => $student->id
            ]);

            return response()->json(['data'=> $filePath, 'message' => 'Registro criado com sucesso!'], 201);
        } else {
            return response()->json(['error'=>true, 'message'=> 'Falha ao criar pdf '], 500);
        }
    }

    public function verifyBlobHistoryPDF(string $filename)
    {
        $path = storage_path('/app/history/' . $filename);
        if (file_exists($path)):
            return response()->file($path, ['Content-Type' => 'application/pdf']);
        else:
            return response()->json(['error'=>true, 'message'=> 'Falha ao criar pdf'], 500);
        endif;
    }

    public function removeFileHistoryPDF(string $filename)
    {
        $fileToDelete = "history/". $filename;

        if (Storage::exists($fileToDelete)):
            Storage::delete($fileToDelete);

            $record = Document::where('path', $fileToDelete);
            $record->delete();

            return response()->json(['data'=> $fileToDelete, 'message' => 'Arquivo removido com sucesso!'], 200);
        else:
            return response()->json(['error'=>true, 'message'=> 'Lamentamos, mas ocorreu um problema ao remover o arquivo. Parece que o arquivo já foi excluído anteriormente.'], 500);
        endif;
    }

    public function hasHistoric(string $code)
    {
        $document = Document::where('code', $code);
        $document->with('student');

        $record = $document->get();

        if(count($record)) {
            return response()->json($record);
        } else {
            return response()->json(['error'=> true,'message'=> 'Não foi encontrado nenhum documento válido com este código.'], 404);
        }
    }

}
