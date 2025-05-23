<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\User;

class LeaderboardService
{
    public function getUsersWithPoints(string $filter, ?string $searchId = null, string $sort = 'total_points', string $direction = 'desc')
    {
        $query = Activity::query();

        // Apply time filter
        match ($filter) {
            'day'   => $query->whereBetween('performed_at', [now()->startOfDay(), now()->endOfDay()]),
            'month' => $query->whereMonth('performed_at', now()->month),
            'year'  => $query->whereYear('performed_at', now()->year),
            default => null,
        };

        // Get filtered activities
        $activities = $query->get();

        $userPoints = [];

        foreach ($activities as $activity) {
            if (!isset($userPoints[$activity->user_id])) {
                $userPoints[$activity->user_id] = 0;
            }
            $userPoints[$activity->user_id] += $activity->points;
        }

        // Load users
        $users = User::with('rank')->get()->map(function ($user) use ($userPoints) {
            $user->total_points = $userPoints[$user->id] ?? 0;
            $user->rank = $user->rank->rank ?? '-';
            return $user;
        });

        // Sort users
        $users = $users->sortBy(function ($user) use ($sort) {
            return $sort === 'rank' ? (int) $user->rank : $user->{$sort};
        }, SORT_REGULAR, $direction === 'desc')->values();

        if ($searchId) {
            $users = $users->sortByDesc(fn($u) => $u->id == $searchId)->values();
        }

        return $users;
    }
}
