<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Weekly Timetable</title>

    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            font-size: 12px;
            color: #111827;
            margin: 20px;
        }

        h1,
        h2 {
            text-align: center;
            margin: 0;
        }

        h1 {
            font-size: 20px;
            color: #1f2937;
            margin-bottom: 5px;
        }

        h2 {
            font-size: 16px;
            color: #4b5563;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 30px;
        }

        th,
        td {
            border: 1px solid #e5e7eb;
            padding: 8px;
            vertical-align: top;
            text-align: center;
            word-wrap: break-word;
        }

        th {
            background-color: #f3f4f6;
            font-weight: 600;
            color: #374151;
        }


        td div {
            margin-bottom: 6px;
            background-color: #ecfdf5;
            border: 1px solid #bbf7d0;
            border-radius: 4px;
            padding: 6px;
            font-size: 11px;
            color: #065f46;
        }

        td div:last-child {
            margin-bottom: 0;
        }

        .course-details {
            margin-top: 30px;
        }

        .course-details h3 {
            font-size: 14px;
            color: #065f46;
            margin-bottom: 10px;
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 4px;
        }

        .course-details div {
            font-size: 12px;
            margin-bottom: 5px;
            color: #374151;
        }

        .course-details strong {
            color: #065f46;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
        }



        .course-details {
            margin-top: 30px;
        }

        .course-details h3 {
            font-size: 14px;
            margin-bottom: 10px;
            border-bottom: 1px solid #000;
            padding-bottom: 4px;
        }

        .course-details div {
            font-size: 12px;
            margin-bottom: 5px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <h1>{{ $title }}</h1>
    <h2>{{ $subtitle }}</h2>

    <table>
        <thead>
            <tr>
                <th>Day / Time</th>
                @foreach ($timeSlots as $slot)
                    <th>
                        {{ \Carbon\Carbon::parse($slot['start'])->format('H:i') }}<br> - <br>
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
                        @if ($slot['type'] === 'break')
                            <td>
                                Break
                            </td>
                        @else
                            @php
                                $cellEntries = collect($entries)->filter(
                                    fn($entry) => $entry['day'] === $day && $entry['start_time'] === $slot['start'],
                                );
                            @endphp
                            <td>
                                @if ($cellEntries->isNotEmpty())
                                    @foreach ($cellEntries as $entry)
                                        <span style="margin-bottom: 16px">{{ $entry['course_code'] }}
                                        </span>
                                        <br>
                                        <span> {{ $entry['venue'] }}</span>
                                    @endforeach
                                @else
                                    &nbsp;
                                @endif
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="course-details">
        <h3>Course Details</h3>
        @foreach (collect($entries)->unique('course_code') as $entry)
            <div>
                <strong>{{ $entry['course_code'] }}</strong>: {{ $entry['course_name'] }} — {{ $entry['lecturer'] }}
            </div>
        @endforeach
    </div>
</body>

</html>
