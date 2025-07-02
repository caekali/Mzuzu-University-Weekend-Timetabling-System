<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Weekly Timetable</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            font-size: 12px;
            color: #111827;
            margin: 20px;
        }

        h2 {
            text-align: center;
            font-size: 20px;
            margin-bottom: 20px;
            color: #1f2937;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #e5e7eb;
            padding: 6px;
            vertical-align: top;
            text-align: left;
        }

        th {
            background-color: #f3f4f6;
            font-weight: 600;
            color: #374151;
        }

        .time-slot {
            width: 100px;
        }

        .time-slot .slot {
            text-align: center;
        }



        .entry-card {
            background-color: #d1fae5;
            border-radius: 4px;
            padding: 4px;
            margin-bottom: 4px;
        }

        .entry-card .course {
            font-weight: bold;
            color: #065f46;
        }

        .entry-card .meta {
            font-size: 10px;
            color: #374151;
        }

        .empty-cell {
            min-height: 50px;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div style="text-align: center; margin-bottom: 10px;">
        <img src="{{ public_path('assets/mzunilogo.webp') }}" alt="Mzuni Logo" style="height: 80px; display: inline-block;" />
    </div>
    <h2>Weekly Timetable</h2>

    <table>
        <thead>
            <tr>
                <th class="time-slot">Day / Time</th>
                @foreach ($timeSlots as $slot)
                    <th class='slot'>
                        {{ \Carbon\Carbon::parse($slot['start'])->format('H:i') }} <br /> - <br />
                        {{ \Carbon\Carbon::parse($slot['end'])->format('H:i') }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($days as $day)
                <tr>
                    <th>{{ $day }}</th>
                    @foreach ($timeSlots as $slot)
                        @php
                            $cellEntries = collect($entries)->filter(function ($entry) use ($day, $slot) {
                                return $entry['day'] === $day && $entry['start_time'] === $slot['start'];
                            });
                        @endphp
                        <td>
                            @if ($cellEntries->isNotEmpty())
                                @foreach ($cellEntries as $entry)
                                    <div class="entry-card">
                                        <div class="course">{{ $entry['course_code'] }}
                                        </div>
                                        {{-- <div class="meta">{{ $entry['lecturer'] }}</div> --}}
                                        <div class="meta">{{ $entry['venue'] }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="empty-cell"></div>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ now()->format('d M Y, H:i') }} by {{ config('app.name') }}
    </div>

</body>

</html>
