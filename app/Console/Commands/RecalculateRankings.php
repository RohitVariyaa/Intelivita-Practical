<?php

namespace App\Console\Commands;

use App\Models\Activity;
use App\Models\UserRank;
use Illuminate\Console\Command;

class RecalculateRankings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recalculate:rankings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculates user rankings based on activity points';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $activities = Activity::all();

        $userPoints = [];

        foreach ($activities as $activity) {
            if (!isset($userPoints[$activity->user_id])) {
                $userPoints[$activity->user_id] = 0;
            }
            $userPoints[$activity->user_id] += $activity->points;
        }

        arsort($userPoints);

        $rank = 1;
        $prevPoints = null;
        $actualRank = 1;

        foreach ($userPoints as $userId => $total) {
            if ($prevPoints !== null && $prevPoints != $total) {
                $actualRank = $rank;
            }

            UserRank::updateOrCreate(
                ['user_id' => $userId],
                ['total_points' => $total, 'rank' => $actualRank]
            );

            $prevPoints = $total;
            $rank++;
        }

        $this->info('Ranking recalculated successfully.');
    }
}
