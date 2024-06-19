<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Team;
use App\Models\StudentTeam;
use App\Services\TeamService;
use App\Http\Requests\TeamRequest;
use App\Http\Requests\StudentTeamRequest;

class TeamController extends Controller
{
    private $service;

    public function __construct(TeamService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return $this->service->getAll($request, ['grid','polo','registrations']);
    }

    public function store(TeamRequest $request)
    {
        try {
            $data = $this->service->create($request->all());
            return response()->json(['data'=> $data, 'message' => 'Registro criado com sucesso!'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['error'=> true, 'message'=> $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function show(Team $team)
    {
        return $this->service->find($team->id);
    }

    public function update(TeamRequest $request, Team $team)
    {
        try {
            $data = $this->service->update($team->id, $request->all());
            return response()->json(['data' => $data, 'message' => 'Cadastro atualizado com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message'=> $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy(Team $team)
    {
        try {
            $this->service->delete($team->id);
            return response()->json(['message' => 'Registro removido com sucesso.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'A remoção desse registro não é viável devido ao fato de que ele já está associado a outra tabela'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function studentsByTeam(Team $team)
    {
        $team->registrations;
        return response()->json($team);
    }

    public function disciplinesByTeam(Team $team)
    {
        $disciplines = $team->getDisciplines($team->id);
        return response()->json($disciplines);
    }

    public function gradesByTeam(Team $team)
    {
        $team->grades;
        return response()->json($team->grades);
    }

    public function registerStudent(StudentTeamRequest $request)
    {
        try {
            $register = StudentTeam::where('student_id', $request->input('student_id'))->first();

            if($register):
                $register->registered = 0;
                $register->save();
                $register->forceDelete();
            endif;

            if(!$request->input('team_id')) {
                return response()->json(['message' => 'Matrícula removida com sucesso!'], 200);
            }

            $record = StudentTeam::create($request->all());
            return response()->json(['data'=> $record, 'message' => 'Matrícula efetuada com sucesso!'], 201);


        } catch (\Exception $e) {
            return response()->json(['error'=>true,'message'=> $e->getMessage()], 422);
        }
    }

}
