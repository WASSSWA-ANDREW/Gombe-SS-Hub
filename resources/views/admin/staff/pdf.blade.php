<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Directory PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; margin: 20px; font-size: 10px; }
        h1 { text-align: center; margin-bottom: 20px; font-size: 16px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .page-break { page-break-after: always; }
        .footer { text-align: center; font-size: 8px; position: fixed; bottom: 0px; width:100%;}
    </style>
</head>
<body>
    <h1>Gombe Secondary School - Staff Directory</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Surname</th>
                <th>First Name</th>
                <th>Other Name</th>
                <th>Sex</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Date of Birth</th>
                <th>National ID</th>
                <th>Highest Edu.</th>
                <th>Joined</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($staffMembers as $staff)
                <tr>
                    <td>{{ $staff->id }}</td>
                    <td>{{ $staff->surname }}</td>
                    <td>{{ $staff->first_name }}</td>
                    <td>{{ $staff->other_name ?? 'N/A' }}</td>
                    <td>{{ $staff->sex }}</td>
                    <td>{{ $staff->email ?? 'N/A' }}</td>
                    <td>{{ $staff->telephone_contacts ?? 'N/A' }}</td>
                    <td>{{ $staff->date_of_birth ? $staff->date_of_birth->format('d M, Y') : 'N/A' }}</td>
                    <td>{{ $staff->national_id_no ?? 'N/A' }}</td>
                    <td>{{ $staff->highest_level_of_education ?? 'N/A' }}</td>
                    <td>{{ $staff->created_at->format('d M, Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" style="text-align: center;">No staff members found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="footer">
        Generated on {{ date('d M, Y H:i:s') }}
    </div>
</body>
</html>