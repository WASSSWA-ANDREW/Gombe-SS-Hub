<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>O'Level Students List</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 10px; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; }
    </style>
</head>
<body>
    <h1>O'Level Students List</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Gender</th>
                <th>LIN</th>
                <th>NIN</th>
                <th>DOB</th>
                <th>Religion</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>District of Birth</th>
                <th>Previous School</th>
                <th>PLE Index</th>
                {{-- Add more relevant headers based on your Student model and needs --}}
            </tr>
        </thead>
        <tbody>
            @if(isset($students) && $students->count() > 0)
                @foreach($students as $student)
                    <tr>
                        <td>{{ $student->id }}</td>
                        <td>{{ $student->student_name ?? 'N/A' }}</td>
                        <td>{{ $student->gender ?? 'N/A' }}</td>
                        <td>{{ $student->learners_lin ?? 'N/A' }}</td>
                        <td>{{ $student->learners_nin ?? 'N/A' }}</td>
                        <td>{{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('Y-m-d') : 'N/A' }}</td>
                        <td>{{ $student->religion ?? 'N/A' }}</td>
                        <td>{{ $student->mobile_number ?? 'N/A' }}</td>
                        <td>{{ $student->email ?? 'N/A' }}</td>
                        <td>{{ $student->district_of_birth ?? 'N/A' }}</td>
                        <td>{{ $student->previous_school ?? 'N/A' }}</td>
                        <td>{{ $student->ple_index_number ?? 'N/A' }}</td>
                        {{-- Add more relevant data cells --}}
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="12" style="text-align: center;">No O'Level students found.</td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>