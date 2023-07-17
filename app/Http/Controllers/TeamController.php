<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\StudentTeam;
use Illuminate\Http\Request;
use App\Services\TeamService;
use Illuminate\Http\Response;
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
        return $this->service->getAll($request, ['grid','polo']);
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
        $this->service->delete($team->id);
        return response()->json(['message' => 'Registro removido com sucesso.']);
    }

    public function registerStudent(StudentTeamRequest $request)
    {
        try {
            $register = StudentTeam::where('student_id', $request->input('student_id'))->first();

            if($register):
                $register->registered = 0;
                $register->save();
                $register->delete();
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
