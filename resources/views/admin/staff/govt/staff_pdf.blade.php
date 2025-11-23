<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Government Staff Details - {{ $staff->surname }}, {{ $staff->first_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #333;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #DC2626;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            color: #DC2626;
            font-size: 22px;
        }
        .header h2 {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 15px;
            font-weight: normal;
        }
        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .section-title {
            background-color: #DC2626;
            color: white;
            padding: 7px 10px;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 12px;
            border-radius: 3px;
        }
        .info-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            font-weight: bold;
            padding: 7px;
            width: 40%;
            border: 1px solid #ddd;
            background-color: #f9fafb;
        }
        .info-value {
            display: table-cell;
            padding: 7px;
            border: 1px solid #ddd;
        }
        .footer {
            margin-top: 35px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .photo-section {
            text-align: center;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f9fafb;
            border-radius: 3px;
        }
        .photo-section img {
            max-width: 130px;
            height: 160px;
            object-fit: cover;
            border: 2px solid #ddd;
            border-radius: 3px;
        }
        .photo-label {
            font-weight: bold;
            font-size: 10px;
            margin-top: 8px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>GOMBE SECONDARY SCHOOL</h1>
        <h2>GOVERNMENT STAFF MEMBER DETAILS</h2>
    </div>

    <!-- Passport Photo -->
    @if($staff->photo_path)
    <div class="photo-section">
        <img src="{{ Storage::url($staff->photo_path) }}" alt="Passport Photo">
        <div class="photo-label">PASSPORT PHOTO</div>
    </div>
    @endif

    <!-- Personal Information -->
    <div class="section">
        <div class="section-title">PERSONAL INFORMATION</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Surname (Last Name):</div>
                <div class="info-value">{{ $staff->surname ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">First Name:</div>
                <div class="info-value">{{ $staff->first_name ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Sex:</div>
                <div class="info-value">{{ $staff->sex ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Date of Birth:</div>
                <div class="info-value">{{ $staff->date_of_birth ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Religion:</div>
                <div class="info-value">{{ $staff->religion ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Official Information -->
    <div class="section">
        <div class="section-title">OFFICIAL INFORMATION</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">UTS File No:</div>
                <div class="info-value">{{ $staff->uts_file_no ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Employee Registration Number(s) for Teachers:</div>
                <div class="info-value">{{ $staff->registration_no ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">IPPS Number:</div>
                <div class="info-value">{{ $staff->ipps_no ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Appointment Information -->
    <div class="section">
        <div class="section-title">APPOINTMENT INFORMATION</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Date of 1st / Probationary Appointment:</div>
                <div class="info-value">{{ $staff->date_of_1st_appt ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Designation at Probationary Appointment:</div>
                <div class="info-value">{{ $staff->designation_of_1st_appt ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">ESC Minute of 1st Appointment:</div>
                <div class="info-value">{{ $staff->minute_no_1st_appt ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">ESC Minute of Confirmation:</div>
                <div class="info-value">{{ $staff->minute_no_confirmation ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Current Position:</div>
                <div class="info-value">{{ $staff->designation_of_current_appt ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">ESC Minute of Appointment to Current Position:</div>
                <div class="info-value">{{ $staff->minute_no_current_appt ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Date of Posting to Current Station:</div>
                <div class="info-value">{{ $staff->date_of_current_posting ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Teaching Information -->
    <div class="section">
        <div class="section-title">TEACHING INFORMATION</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Teaching Subjects:</div>
                <div class="info-value">{{ $staff->teaching_subjects ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Staff Designation -->
    <div class="section">
        <div class="section-title">DESIGNATION</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Staff Designation/Role:</div>
                <div class="info-value">{{ $staff->staff_designation ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Medical Status:</div>
                <div class="info-value">{{ $staff->medical_status ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Physical Health:</div>
                <div class="info-value">{{ $staff->physical_health ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Generated on {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }} | Gombe Secondary School Hub</p>
    </div>
</body>
</html>