<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\StudentService;
use App\Http\Requests\StudentRequest;
use App\Notifications\StudentCreated;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StudentUpdateRequest;
use Illuminate\Support\Facades\Notification;

class StudentController extends Controller
{
    private $service;

    public function __construct(StudentService $service)
    {
        $this->service = $service;
    }

    public function getImage(Student $student)
    {
        $path = storage_path("app/{$student->image}");
    
        if (!empty($student->image) && file_exists($path)) {
            $mimeType = mime_content_type($path);
    
            if (str_starts_with($mimeType, 'image/')) {
                return response()->file($path, ['Content-Type' => $mimeType]);
            } else {
                return response()->json(['error' => true, 'message' => 'O arquivo não é uma imagem válida'], 400);
            }
        } else {
            return response()->json(['error' => true, 'message' => 'Imagem não encontrada'], 404);
        }
    }

    public function index(Request $request)
    {
        return $this->service->getAll($request, ['registrations']);
    }

    public function store(StudentRequest $request)
    {
        try {

            $data = $request->all();
            
            if ($request->hasFile('image')) 
                $data['image'] = $request->file('image')->store('students', 'local');

            $student = $this->service->create($data);

            #Notification::route('mail', 'dellanosites@gmail.com')->notify(new StudentCreated($student));

            return response()->json(['data' => $student, 'message' => 'Registro criado com sucesso!'], Response::HTTP_CREATED);
        } catch (\PDOException $e) {

            return response()->json(['error' => true, 'message'=> $e->getPrevious()->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function show(Student $student)
    {
        return $this->service->find($student->id);
    }

    public function update(StudentUpdateRequest $request, Student $student)
    {
        try {

            $data = $request->all();

            if ($request->hasFile('image')) {

                if (!is_null($student->image))
                    @Storage::delete($student->image);

                $data['image'] = $request->file('image')->store('students', 'local');
            }

            $data = $this->service->update($student->id, $data);
        
            return response()->json(['data' => $data, 'message' => 'Cadastro atualizado com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message'=> $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy(Student $student)
    {
        try {
            $this->service->delete($student->id);
            return response()->json(['message' => 'Registro removido com sucesso.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'A remoção desse registro não é viável devido ao fato de que ele já está associado a outra tabela'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
