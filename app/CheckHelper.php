<?php


namespace App;


use Illuminate\Support\Facades\DB;
class CheckHelper
{
    /**
     * @param $id
     * @param $data
     * @return bool
     */
    public static function checkUserInTeamUsers($id, $data)
    {
        $team = TeamUser::select('id',
                                'user_id',
                                'team_id as test_team_id',
                                DB::raw("(SELECT user_id
                                                FROM team_users
                                                WHERE role = 1 and team_id = test_team_id)
                                                as creator")
        )
            ->where('id', '=', $id)
            ->first();

        if (!is_null($team) && $team->creator === $data['creator']) {
            if ($team->creator !== $team->user_id){
                return $team;
            }
            return false;
        }
        return false;
    }

    /**
     * @param $id
     * @param $data
     * @return bool
     */
    public static function checkTeam($id, $data)
    {
        $team = TeamUser::where('team_id','=',$id)
                            ->with('team')
                            ->first();

        if (!is_null($team) && $team->role == 1 && $team->user_id == $data['creator']){
            return $team;
        }
        return false;
    }

}