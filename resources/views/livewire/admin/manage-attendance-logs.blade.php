<div>
    <table class="table">
        <thead>
            <tr>
                <td>Employee Id</td>
                <td>Time In</td>
                <td>Time Out</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendanceLogs as $attendance)
                <tr wire:key="{{ $attendance->uid }}">
                    <td>{{ $attendance->id }}</td>
                    <td>{{ $attendance->type === 0 ? $attendance->timestamp : '-' }}</td>
                    <td>{{ $attendance->type === 1 ? $attendance->timestamp : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
      
    </table>

</div>
