<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Details - {{ $staff->surname }}, {{ $staff->first_name }}</title>
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
            border-bottom: 3px solid #059669;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            color: #059669;
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
            background-color: #059669;
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
        .comment-box {
            border: 1px solid #ddd;
            padding: 10px;
            background-color: #f9fafb;
            min-height: 50px;
            border-radius: 3px;
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
        <h2>PRIVATE STAFF MEMBER DETAILS</h2>
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
                <div class="info-label">Surname:</div>
                <div class="info-value">{{ $staff->surname ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">First Name:</div>
                <div class="info-value">{{ $staff->first_name ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Other Name:</div>
                <div class="info-value">{{ $staff->other_name ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Sex:</div>
                <div class="info-value">{{ $staff->sex ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Date of Birth:</div>
                <div class="info-value">{{ $staff->date_of_birth ? \Carbon\Carbon::parse($staff->date_of_birth)->format('d/m/Y') : 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">National ID No:</div>
                <div class="info-value">{{ $staff->national_id_no ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value">{{ $staff->email ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Telephone Contacts:</div>
                <div class="info-value">{{ $staff->telephone_contacts ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Marital Status:</div>
                <div class="info-value">{{ $staff->marital_status ?? 'N/A' }}</div>
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
                <div class="info-label">District File No:</div>
                <div class="info-value">{{ $staff->district_file_no ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Computer No:</div>
                <div class="info-value">{{ $staff->computer_no ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Registration No/Nos:</div>
                <div class="info-value">{{ $staff->registration_no ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">TIN No:</div>
                <div class="info-value">{{ $staff->tin_no ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Salary Information -->
    <div class="section">
        <div class="section-title">SALARY INFORMATION</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Salary Scale:</div>
                <div class="info-value">{{ $staff->salary_scale ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Gross Salary:</div>
                <div class="info-value">{{ $staff->gross_salary ? 'UGX ' . number_format($staff->gross_salary, 2) : 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Net Salary:</div>
                <div class="info-value">{{ $staff->net_salary ? 'UGX ' . number_format($staff->net_salary, 2) : 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Appointment Information -->
    <div class="section">
        <div class="section-title">APPOINTMENT INFORMATION</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Date of 1st Appointment:</div>
                <div class="info-value">{{ $staff->date_of_1st_appt ? \Carbon\Carbon::parse($staff->date_of_1st_appt)->format('d/m/Y') : 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Designation at 1st Appointment:</div>
                <div class="info-value">{{ $staff->designation_of_1st_appt ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Minute No (1st Appointment):</div>
                <div class="info-value">{{ $staff->minute_no_1st_appt ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Date of Current Appointment:</div>
                <div class="info-value">{{ $staff->date_of_current_appt ? \Carbon\Carbon::parse($staff->date_of_current_appt)->format('d/m/Y') : 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Current Designation:</div>
                <div class="info-value">{{ $staff->designation_of_current_appt ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Minute No (Current Appointment):</div>
                <div class="info-value">{{ $staff->minute_no_current_appt ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Date of Confirmation:</div>
                <div class="info-value">{{ $staff->date_of_confirmation ? \Carbon\Carbon::parse($staff->date_of_confirmation)->format('d/m/Y') : 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Minute No (Confirmation):</div>
                <div class="info-value">{{ $staff->minute_no_confirmation ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Date of Current Posting:</div>
                <div class="info-value">{{ $staff->date_of_current_posting ? \Carbon\Carbon::parse($staff->date_of_current_posting)->format('d/m/Y') : 'N/A' }}</div>
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

    <!-- Education & Qualifications -->
    <div class="section">
        <div class="section-title">EDUCATION & QUALIFICATIONS</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Highest Level of Education:</div>
                <div class="info-value">{{ $staff->highest_level_of_education ?? 'N/A' }}</div>
            </div>
        </div>
        @if($staff->other_academic_qualifications)
        <div style="margin-top: 10px;">
            <strong>Other Academic Qualifications:</strong>
            <div class="comment-box">
                {{ $staff->other_academic_qualifications }}
            </div>
        </div>
        @endif
    </div>

    <!-- Next of Kin -->
    <div class="section">
        <div class="section-title">NEXT OF KIN</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Next of Kin Name:</div>
                <div class="info-value">{{ $staff->next_of_kin ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Next of Kin Telephone:</div>
                <div class="info-value">{{ $staff->next_of_kin_telephone ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Generated on {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }} | Gombe Secondary School Hub</p>
    </div>
</body>
</html>