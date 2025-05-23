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
        // Step 1: Group activities by user and sum points
        $pointData = Activity::select('user_id')
            ->selectRaw('SUM(points) as total')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->get();

        // Step 2: Loop through results and update ranks
        $rank = 1;
        $prevPoints = null;
        $actualRank = 1;

        foreach ($pointData as $data) {
            if ($prevPoints !== null && $prevPoints != $data->total) {
                $actualRank = $rank;
            }

            UserRank::updateOrCreate(
                ['user_id' => $data->user_id],
                ['total_points' => $data->total, 'rank' => $actualRank]
            );

            $prevPoints = $data->total;
            $rank++;
        }

        $this->info('Ranking recalculated successfully.');
    }
}
