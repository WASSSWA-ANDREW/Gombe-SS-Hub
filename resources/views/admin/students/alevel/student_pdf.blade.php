<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A'Level Student Details - {{ $student->student_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #4F46E5;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            color: #4F46E5;
            font-size: 24px;
        }
        .header h2 {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 16px;
            font-weight: normal;
        }
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .section-title {
            background-color: #4F46E5;
            color: white;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            border-radius: 4px;
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
            padding: 8px;
            width: 35%;
            border: 1px solid #ddd;
            background-color: #f9fafb;
        }
        .info-value {
            display: table-cell;
            padding: 8px;
            border: 1px solid #ddd;
        }
        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .results-table th {
            background-color: #f3f4f6;
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
        }
        .results-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .comment-box {
            border: 1px solid #ddd;
            padding: 10px;
            background-color: #f9fafb;
            min-height: 60px;
            border-radius: 4px;
        }
        .photo-section {
            text-align: center;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f9fafb;
            border-radius: 4px;
        }
        .photo-section img {
            max-width: 150px;
            height: 180px;
            object-fit: cover;
            border: 2px solid #ddd;
            border-radius: 4px;
        }
        .photo-label {
            font-weight: bold;
            font-size: 11px;
            margin-top: 8px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>GOMBE SECONDARY SCHOOL</h1>
        <h2>A'LEVEL STUDENT DETAILS</h2>
    </div>

    <!-- Passport Photo -->
    @if($student->photo_path)
    <div class="photo-section">
        <img src="{{ Storage::url($student->photo_path) }}" alt="Passport Photo">
        <div class="photo-label">PASSPORT PHOTO</div>
    </div>
    @endif

    <!-- Student's Details -->
    <div class="section">
        <div class="section-title">STUDENT'S DETAILS</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Name of Student:</div>
                <div class="info-value">{{ $student->student_name ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Gender:</div>
                <div class="info-value">{{ $student->gender ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Learner's LIN:</div>
                <div class="info-value">{{ $student->learners_lin ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Learner's NIN:</div>
                <div class="info-value">{{ $student->learners_nin ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Date of Birth:</div>
                <div class="info-value">{{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y') : 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Religion:</div>
                <div class="info-value">{{ $student->religion ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Mobile Number:</div>
                <div class="info-value">{{ $student->mobile_number ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value">{{ $student->email ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">District of Birth:</div>
                <div class="info-value">{{ $student->district_of_birth ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Previous School:</div>
                <div class="info-value">{{ $student->previous_school ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Subject Combination:</div>
                <div class="info-value">{{ $student->subject_combination ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- UCE Results -->
    <div class="section">
        <div class="section-title">UCE RESULTS</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">UCE Index Number:</div>
                <div class="info-value">{{ $student->uce_index_number ?? 'N/A' }}</div>
            </div>
        </div>
        <table class="results-table">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>English Language</td>
                    <td>{{ is_array($student->uce_results) ? ($student->uce_results['english'] ?? 'N/A') : 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Mathematics</td>
                    <td>{{ is_array($student->uce_results) ? ($student->uce_results['mathematics'] ?? 'N/A') : 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Physics</td>
                    <td>{{ is_array($student->uce_results) ? ($student->uce_results['physics'] ?? 'N/A') : 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Chemistry</td>
                    <td>{{ is_array($student->uce_results) ? ($student->uce_results['chemistry'] ?? 'N/A') : 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Biology</td>
                    <td>{{ is_array($student->uce_results) ? ($student->uce_results['biology'] ?? 'N/A') : 'N/A' }}</td>
                </tr>
                <tr>
                    <td>History</td>
                    <td>{{ is_array($student->uce_results) ? ($student->uce_results['history'] ?? 'N/A') : 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Geography</td>
                    <td>{{ is_array($student->uce_results) ? ($student->uce_results['geography'] ?? 'N/A') : 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Literature</td>
                    <td>{{ is_array($student->uce_results) ? ($student->uce_results['literature'] ?? 'N/A') : 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Economics</td>
                    <td>{{ is_array($student->uce_results) ? ($student->uce_results['economics'] ?? 'N/A') : 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Aggregates</strong></td>
                    <td><strong>{{ is_array($student->uce_results) ? ($student->uce_results['aggregates'] ?? 'N/A') : 'N/A' }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Special Issues -->
    @if($student->special_issue)
    <div class="section">
        <div class="section-title">SPECIAL ISSUES</div>
        <div class="comment-box">
            {{ $student->special_issue }}
        </div>
    </div>
    @endif

    <!-- Parent/Guardian Information -->
    <div class="section">
        <div class="section-title">FATHER'S INFORMATION</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Full Name:</div>
                <div class="info-value">{{ $student->father_full_name ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Mobile Number:</div>
                <div class="info-value">{{ $student->father_mobile_number ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value">{{ $student->father_email ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">NIN:</div>
                <div class="info-value">{{ $student->father_nin ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Physical Address:</div>
                <div class="info-value">{{ $student->father_physical_address ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Occupation:</div>
                <div class="info-value">{{ $student->father_occupation ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status:</div>
                <div class="info-value">{{ $student->father_dead_alive ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">MOTHER'S INFORMATION</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Full Name:</div>
                <div class="info-value">{{ $student->mother_full_name ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Mobile Number:</div>
                <div class="info-value">{{ $student->mother_mobile_number ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value">{{ $student->mother_email ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">NIN:</div>
                <div class="info-value">{{ $student->mother_nin ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Physical Address:</div>
                <div class="info-value">{{ $student->mother_physical_address ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Occupation:</div>
                <div class="info-value">{{ $student->mother_occupation ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status:</div>
                <div class="info-value">{{ $student->mother_dead_alive ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">GUARDIAN'S INFORMATION</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Full Name:</div>
                <div class="info-value">{{ $student->guardian_full_name ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Mobile Number:</div>
                <div class="info-value">{{ $student->guardian_mobile_number ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value">{{ $student->guardian_email ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">NIN:</div>
                <div class="info-value">{{ $student->guardian_nin ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Physical Address:</div>
                <div class="info-value">{{ $student->guardian_physical_address ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Occupation:</div>
                <div class="info-value">{{ $student->guardian_occupation ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Relationship:</div>
                <div class="info-value">{{ $student->guardian_relationship ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Parent Passport Photos -->
    <div class="section">
        <div class="section-title">PARENT/GUARDIAN PASSPORT PHOTOS</div>
        <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
            @if($student->father_passport_photo_path)
            <div style="text-align: center;">
                <img src="{{ Storage::url($student->father_passport_photo_path) }}" alt="Father's Passport Photo" style="max-width: 120px; height: 140px; object-fit: cover; border: 2px solid #ddd; border-radius: 4px;">
                <div class="photo-label">FATHER'S PHOTO</div>
            </div>
            @endif
            @if($student->mother_passport_photo_path)
            <div style="text-align: center;">
                <img src="{{ Storage::url($student->mother_passport_photo_path) }}" alt="Mother's Passport Photo" style="max-width: 120px; height: 140px; object-fit: cover; border: 2px solid #ddd; border-radius: 4px;">
                <div class="photo-label">MOTHER'S PHOTO</div>
            </div>
            @endif
            @if($student->guardian_passport_photo_path)
            <div style="text-align: center;">
                <img src="{{ Storage::url($student->guardian_passport_photo_path) }}" alt="Guardian's Passport Photo" style="max-width: 120px; height: 140px; object-fit: cover; border: 2px solid #ddd; border-radius: 4px;">
                <div class="photo-label">GUARDIAN'S PHOTO</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Official Comment -->
    @if($student->official_comment)
    <div class="section">
        <div class="section-title">OFFICIAL COMMENT</div>
        <div class="comment-box">
            {{ $student->official_comment }}
        </div>
    </div>
    @endif

    <div class="footer">
        <p>Generated on {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }} | Gombe Secondary School Hub</p>
    </div>
</body>
</html>