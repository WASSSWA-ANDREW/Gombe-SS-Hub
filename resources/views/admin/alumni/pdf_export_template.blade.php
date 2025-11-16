<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Alumni List</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 10px; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; }
        .graduation-header { background-color: #e8f5e8 !important; }
    </style>
</head>
<body>
    <h1>Gombe SS Hub Pro - Alumni List</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Gender</th>
                <th>LIN</th>
                <th>NIN</th>
                <th>DOB</th>
                <th>Mobile</th>
                <th>Email</th>
                <th class="graduation-header">Graduation Class</th>
                <th class="graduation-header">Graduation Year</th>
                <th>Previous School</th>
                <th>PLE Index</th>
                <th>UCE Index</th>
                <th>Combination</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($alumni) && $alumni->count() > 0)
                @foreach($alumni as $alumnus)
                    <tr>
                        <td>{{ $alumnus->id }}</td>
                        <td>{{ $alumnus->student_name ?? 'N/A' }}</td>
                        <td>{{ $alumnus->gender ?? 'N/A' }}</td>
                        <td>{{ $alumnus->learners_lin ?? 'N/A' }}</td>
                        <td>{{ $alumnus->learners_nin ?? 'N/A' }}</td>
                        <td>{{ $alumnus->date_of_birth ? \Carbon\Carbon::parse($alumnus->date_of_birth)->format('Y-m-d') : 'N/A' }}</td>
                        <td>{{ $alumnus->mobile_number ?? 'N/A' }}</td>
                        <td>{{ $alumnus->email ?? 'N/A' }}</td>
                        <td class="graduation-header">{{ $alumnus->graduation_class ?? 'N/A' }}</td>
                        <td class="graduation-header">{{ $alumnus->graduation_year ?? 'N/A' }}</td>
                        <td>{{ $alumnus->previous_school ?? 'N/A' }}</td>
                        <td>{{ $alumnus->ple_index_number ?? 'N/A' }}</td>
                        <td>{{ $alumnus->uce_index_number ?? 'N/A' }}</td>
                        <td>{{ $alumnus->combination ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="14" style="text-align: center;">No alumni records found.</td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>