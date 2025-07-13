<table>
    <thead>
        <tr>
            <th>Day</th>
            <th>Time</th>
            <th>Course</th>
            <th>Lecturer</th>
            <th>Venue</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($entries as $entry)
            <tr>
                <td>{{ $entry->day }}</td>
                <td>{{ $entry->start_time }} - {{ $entry->end_time }}</td>
                <td>{{ $entry->course->code }} - {{ $entry->course->name }}</td>
                <td>{{ $entry->lecturer->user->name }}</td>
                <td>{{ $entry->venue->name }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
