<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Intelivita Pvt. Ltd. - Practical Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5 bg-dark p-4 rounded text-white">
        <form method="GET" action="{{ route('leaderboard') }}" class="row g-3 align-items-center mb-3">
            <div class="col-auto">
                <input type="number" name="user_id" class="form-control" placeholder="User ID"
                    value="{{ $searchId ?? '' }}">
            </div>
            <div class="col-auto">
                <select name="filter" class="form-select">
                    <option value="day" {{ $filter === 'day' ? 'selected' : '' }}>Day</option>
                    <option value="month" {{ $filter === 'month' ? 'selected' : '' }}>Month</option>
                    <option value="year" {{ $filter === 'year' ? 'selected' : '' }}>Year</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-secondary" type="submit">Filter</button>
            </div>
        </form>

        <form action="{{ route('recalculate') }}" method="GET" class="mb-3">
            <button class="btn btn-secondary">Recalculate</button>
        </form>

        <table class="table table-dark table-striped table-hover rounded">
            <thead>
                <tr>
                    <th><a href="{{ route('leaderboard', array_merge(request()->all(), ['sort' => 'id', 'direction' => $sort === 'id' && $direction === 'asc' ? 'desc' : 'asc'])) }}"
                            class="text-white">ID</a></th>
                    <th><a href="{{ route('leaderboard', array_merge(request()->all(), ['sort' => 'name', 'direction' => $sort === 'name' && $direction === 'asc' ? 'desc' : 'asc'])) }}"
                            class="text-white">Name</a></th>
                    <th><a href="{{ route('leaderboard', array_merge(request()->all(), ['sort' => 'total_points', 'direction' => $sort === 'total_points' && $direction === 'asc' ? 'desc' : 'asc'])) }}"
                            class="text-white">Points</a></th>
                    <th><a href="{{ route('leaderboard', array_merge(request()->all(), ['sort' => 'rank', 'direction' => $sort === 'rank' && $direction === 'asc' ? 'desc' : 'asc'])) }}"
                            class="text-white">Rank</a></th>

                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->total_points }}</td>
                        <td>#{{ $user->rank }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>

</html>

<style>
    body {
        background: #121212;
        color: #e0e0e0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 900px;
        margin: 40px auto;
        padding: 20px;
        background: #1e1e1e;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.7);
    }

    form {
        margin-bottom: 20px;
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        align-items: center;
    }

    input[type="number"],
    select {
        padding: 10px 12px;
        border-radius: 6px;
        border: none;
        background: #ffffff;
        color: #000000;
        font-size: 16px;
        min-width: 120px;
    }

    input[type="number"]:focus,
    select:focus {
        outline: none;
        background: #ffffff;
    }

    .button {
        padding: 12px 25px;
        background: #007bff;
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .button:hover {
        background: #0056b3;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 15px 12px;
        border-bottom: 1px solid #333;
        text-align: left;
    }

    th {
        background: #222;
    }

    tbody tr:hover {
        background: #333;
    }

    @media (max-width: 600px) {
        form {
            flex-direction: column;
            align-items: stretch;
        }

        .button {
            width: 100%;
            margin-top: 10px;
        }
    }
</style>
