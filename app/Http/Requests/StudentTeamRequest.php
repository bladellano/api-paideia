<?php

namespace App\Http\Requests;


class StudentTeamRequest extends APIFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'team_id' => 'unique:student_teams,team_id,NULL,id,deleted_at, NULL,student_id,'. $this->input('student_id'),
            'student_id' => 'required|exists:students,id|unique:student_teams,student_id,NULL,id,deleted_at, NULL,team_id,'. $this->input('team_id'),
        ];
    }

}
