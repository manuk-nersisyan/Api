<?php


namespace App\Http\Controllers;

use App\CheckHelper;
use App\TeamUser;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class TeamUsersController extends BaseController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $teamsIds = TeamUser::select('team_id')
            ->where('user_id', '=', $request->get('creator'))
            ->where('role', '=', 1)
            ->pluck('team_id')->toArray();

        $teams = TeamUser::whereIn('team_id', $teamsIds)
            ->with(['user'])
            ->get();

        if (!is_null($teams)) {
            return response()->json($teams, 200);
        }
        return response()->json(0, 200);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id, Request $request)
    {
        $team = TeamUser::where('team_id', $id)->first();
        if (!is_null($team) && $team->role == 1 && $team->user_id == $request->get('creator')) {
            $teamUsers = TeamUser::where('team_id', '=', $id)
                ->get();
            return response()->json($teamUsers, 200);
        }
        return response()->json(0, 404);
    }


    /**
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(int $id, Request $request)
    {
        $this->validate($request, [
            'user_id.*' => 'required|numeric|exists:users,id',
        ]);
        $team = CheckHelper::checkTeam($id, $request->all());
        if ($team) {
            $userIds = $request->get('user_id');
            $teamUserIds = TeamUser::where('team_id', '=', $team->team_id)->pluck('user_id')->toArray();
            if (count($request->get('user_id')) > 1 || is_array($userIds)) {
                foreach ($userIds as $userId) {
                    if (in_array ($userId, $teamUserIds)){
                        return response()->json(0, 409);
                    }
                    TeamUser::create([
                        'team_id' => $team->team_id,
                        'user_id' => $userId,
                        'role' => 0
                    ]);
                }
            } else {
                if (in_array ($userIds, $teamUserIds)){
                    return response()->json(0, 409);
                }
                TeamUser::create([
                    'team_id' => $team->team_id,
                    'user_id' => $userIds,
                    'role' => 0
                ]);
            }
            return response()->json(1, 200);
        }
        return response()->json(0, 404);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id, Request $request)
    {
        $team = CheckHelper::checkUserInTeamUsers($id,$request->all());

        if ($team){
            $team->delete();
            return response()->json(1,200);
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
            'user_id' => 'required|numeric|exists:users,id',
        ]);

        $team = CheckHelper::checkUserInTeamUsers($id, $request->all());
        if ($team){
            $userId = $request->get('user_id');
            $teamUserIds = TeamUser::where('team_id', '=', $team->test_team_id)->pluck('user_id')->toArray();
            if (in_array ($userId, $teamUserIds)){
                return response()->json(0, 404);
            }
            $team->update(['user_id'=> $request->get('user_id')]);
            return response()->json(1,200);
        }
        return response()->json(0, 404);
    }
}