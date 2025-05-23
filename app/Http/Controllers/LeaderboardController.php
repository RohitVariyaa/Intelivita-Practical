<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LeaderboardService;
use Illuminate\Support\Facades\Artisan;

class LeaderboardController extends Controller
{
    protected LeaderboardService $leaderboard;

    public function __construct(LeaderboardService $leaderboard)
    {
        $this->leaderboard = $leaderboard;
    }

    public function index(Request $request)
    {
        $users = $this->leaderboard->getUsersWithPoints(
            filter: $request->get('filter', 'day'),
            searchId: $request->get('user_id'),
            sort: $request->get('sort', 'total_points'),
            direction: $request->get('direction', 'desc')
        );

        return view('welcome', [
            'users'     => $users,
            'filter'    => $request->get('filter', 'day'),
            'searchId'  => $request->get('user_id'),
            'sort'      => $request->get('sort', 'total_points'),
            'direction' => $request->get('direction', 'desc'),
        ]);
    }


    public function recalculate()
    {
        Artisan::call('recalculate:rankings');

        return redirect()->route('leaderboard')->with('success', 'Leaderboard recalculated.');
    }
}
