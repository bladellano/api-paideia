<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HistoryController extends Controller
{
    public function storeHistoryPDF(Request $request, string $cpf)
    {
        $file = $request->file('pdf');
        $filePath = Storage::putFileAs('history', $file, "{$cpf}_historico.pdf");

        if(Storage::exists($filePath)) {
            return response()->json(['data'=> $filePath, 'message' => 'Record successfully created!'], 201);
        } else {
            return response()->json(['error'=>true, 'message'=> 'Failed to create pdf '], 500);
        }
    }

    public function verifyBlobHistoryPDF(string $filename)
    {
        $path = storage_path('/app/history/' . $filename);
        if (file_exists($path)):
            return response()->file($path, ['Content-Type' => 'application/pdf']);
        else:
            return response()->json(['error'=>true, 'message'=> 'Failed to create pdf'], 500);
        endif;
    }

    public function removeFileHistoryPDF(string $filename)
    {
        $fileToDelete = "/history/". $filename;

        if (Storage::exists($fileToDelete)):
            Storage::delete($fileToDelete);
            return response()->json(['data'=> $fileToDelete, 'message' => 'File removed successfully!'], 200);
        else:
            return response()->json(['error'=>true, 'message'=> 'Failed to remove file, it has probably already been removed.'], 500);
        endif;
    }

}
