<?php


namespace App\Http\Controllers;


use App\CheckHelper;
use App\Team;
use App\TeamUser;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class TeamController extends BaseController
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $teams = TeamUser::select('teams.id', 'teams.title')
                            ->leftJoin('teams','teams.id','=','team_users.team_id')
                            ->where('team_users.user_id','=',$request->get('creator'))
                            ->where('team_users.role','=',1)
                            ->get();

        if (!is_null($teams)){
            return response()->json($teams,200);
        }
        return response()->json(0, 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:255',
        ]);

        $team = Team::create(['title'=>$request->get('title')]);
                TeamUser::create([
                            'team_id' => $team->id,
                            'user_id' => $request->get('creator'),
                            'role' => 1
                   ]);

        return response()->json(1,200);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id, Request $request)
    {
        $team = CheckHelper::checkTeam($id, $request->all());

        if ($team){
            return response()->json($team->team,200);
        }
        return response()->json(0,404);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(int $id, Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:255',
        ]);
        $team = CheckHelper::checkTeam($id, $request->all());

        if ($team){
            $team->team->update(['title' => $request->get('title')]);
            return response()->json(1,200);
        }
        return response()->json(0, 404);
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id, Request $request)
    {
        $team = CheckHelper::checkTeam($id, $request->all());

        if ($team) {
            $team->team->delete();
            return response()->json(1, 200);
        }
        return response()->json(0, 404);
    }
}