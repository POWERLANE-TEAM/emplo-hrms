<div>
    <button wire:click="save" class="btn btn-primary">Sync attendance</button>
    <table class="table">
        <thead>
            <tr>
                <td>Employee Id</td>
                <td>Employee Name</td>
                <td>Check-In</td>
                <td>Check-Out</td>
                <td>Overtime-In</td>
                <td>Overtime-Out</td>
                <td>Date</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($attlogs as $attlog)
                <tr wire:key="{{ $attlog->employee->employee_id }}">
                    <td>{{ $attlog->employee->employee_id }}</td>
                    <td>{{ $attlog->employee->full_name }}</td>
                    <td>{{ $attlog->checkIn ?? '-' }}</td>
                    <td>{{ $attlog->checkOut ?? '-' }}</td>
                    <td>{{ $attlog->overtimeIn ?? '-' }}</td>
                    <td>{{ $attlog->overtimeOut ?? '-' }}</td>
                    <td>{{ $attlog->date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
