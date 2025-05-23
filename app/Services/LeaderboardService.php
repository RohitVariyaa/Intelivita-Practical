<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\User;

class LeaderboardService
{
    public function getUsersWithPoints(string $filter, ?string $searchId = null, string $sort = 'total_points', string $direction = 'desc')
    {
        $query = Activity::query();

        // Filter based on time period
        match ($filter) {
            'day'   => $query->whereBetween('performed_at', [now()->startOfDay(), now()->endOfDay()]),
            'month' => $query->whereMonth('performed_at', now()->month),
            'year'  => $query->whereYear('performed_at', now()->year),
            default => null,
        };

        // Sum of points grouped by user
        $activityTotals = $query
            ->select('user_id')
            ->selectRaw('SUM(points) as total_points')
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        // Load users and attach computed points and rank
        $users = User::with('rank')->get()->map(function ($user) use ($activityTotals) {
            $user->total_points = $activityTotals[$user->id]->total_points ?? 0;
            $user->rank = $user->rank->rank ?? '-';
            return $user;
        });

        // Sort
        $users = $users->sortBy(function ($user) use ($sort) {
            return $sort === 'rank' ? (int) $user->rank : $user->{$sort};
        }, SORT_REGULAR, $direction === 'desc')->values();

        // Highlight searched user
        if ($searchId) {
            $users = $users->sortByDesc(fn($u) => $u->id == $searchId)->values();
        }

        return $users;
    }
}
