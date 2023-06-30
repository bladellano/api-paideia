<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentTeamRequest;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Http\Requests\TeamRequest;
use App\Models\StudentTeam;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Team::query();
        $query->with(['grid','polo']);

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

    public function registerStudent(StudentTeamRequest $request)
    {
        try {
            $register = StudentTeam::where('student_id', $request->input('student_id'))->first();

            if($register):
                $register->registered = 0;
                $register->save();
                $register->delete();
            endif;

            if(!$request->input('team_id'))
                return response()->json(['message' => 'Matrícula removida com sucesso!'], 200);

            $record = StudentTeam::create($request->all());
            return response()->json(['data'=> $record, 'message' => 'Matrícula efetuada com sucesso!'], 201);


        } catch (\Exception $e) {
            return response()->json(['error'=>true,'message'=> $e->getMessage()], 422);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param TeamRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(TeamRequest $request)
    {
        try {
            $record = Team::create($request->all());
            return response()->json(['data'=> $record, 'message' => 'Registro criado com sucesso!'], 201);

        } catch (\Exception $e) {
            return response()->json(['error'=>true,'message'=>$e->getMessage()], 422);
        }
    }

    /**
     * Display the specified resource.
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
    {
        $team->grid;
        $team->polo;
        return response()->json([$team]);
    }

    /**
     * Update the specified resource in storage.
     * @param TeamRequest $request
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(TeamRequest $request, Team $team)
    {
        try {
            $team->update($request->all());
            return response()->json(['data'=>$team, 'message' => 'Cadastro atualizado com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['error'=>true,'message'=> $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        $team->delete();
        return response()->json(['message' => 'Registro removido com sucesso.']);
    }
}
