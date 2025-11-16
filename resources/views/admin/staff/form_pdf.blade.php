<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Data Form - {{ $staff->first_name }} {{ $staff->surname }}</title>
    <style>
        @page { margin: 20px; }
        body {
            font-family: DejaVu Sans, sans-serif; /* Using DejaVu Sans for better character support */
            font-size: 10px;
            line-height: 1.4;
        }
        .header { text-align: center; margin-bottom: 15px; }
        .header h1 { font-size: 16px; margin: 0; font-weight: bold; }
        .header h2 { font-size: 12px; margin: 5px 0; font-weight: bold; border-bottom: 1px solid #000; padding-bottom: 5px; }

        .form-container {
            border: 1px solid #333;
            padding: 10px;
        }
        .form-grid {
            display: flex;
            flex-wrap: wrap; /* This won't work directly in dompdf, using table for layout */
        }
        .form-column {
            width: 48%; /* Approximate for two columns */
            padding: 0 1%;
            box-sizing: border-box;
        }
        .form-field {
            margin-bottom: 8px;
            display: flex; /* For label and value alignment */
            border-bottom: 1px dotted #666; /* Dotted line for value */
            padding-bottom: 2px;
        }
        .form-field label {
            font-weight: normal;
            min-width: 180px; /* Adjust as needed for label alignment */
            display: inline-block;
        }
        .form-field .value {
            font-weight: bold;
            flex-grow: 1; /* Allow value to take remaining space */
            word-break: break-all;
        }
        .full-width { width: 100%; }
        .signature-field { margin-top: 20px; border-top: 1px solid #000; padding-top: 5px; }

        /* Using table for layout as flexbox support in dompdf is limited */
        table.form-layout { width: 100%; border-collapse: collapse; }
        table.form-layout td { vertical-align: top; padding: 0; }
        table.form-layout .column-cell { width: 50%; }
        .field-label { display: inline-block; min-width: 180px; } /* Ensure consistent label width */
        .field-value { font-weight: bold; }
        .field-line { border-bottom: 1px dotted #666; padding-bottom: 3px; margin-bottom: 8px; }
        .photo-section {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9fafb;
            border: 1px solid #ddd;
        }
        .photo-section img {
            max-width: 120px;
            height: 150px;
            object-fit: cover;
            border: 1px solid #ddd;
        }
        .photo-label {
            font-weight: bold;
            font-size: 9px;
            margin-top: 5px;
            color: #333;
        }

    </style>
</head>
<body>
    <div class="header">
        <h1>GOMBE SECONDARY SCHOOL</h1>
        <h2>STAFF DATA FORM</h2>
    </div>

    @if($staff->photo_path)
    <div class="photo-section">
        <img src="{{ Storage::url($staff->photo_path) }}" alt="Passport Photo">
        <div class="photo-label">PASSPORT PHOTO</div>
    </div>
    @endif

    <div class="form-container">
        <table class="form-layout">
            <tr>
                <td class="column-cell">
                    <div class="form-column">
                        <div class="field-line"><span class="field-label">Sur name:</span><span class="field-value">{{ $staff->surname ?? '............................................' }}</span></div>
                        <div class="field-line"><span class="field-label">First Name:</span><span class="field-value">{{ $staff->first_name ?? '............................................' }}</span></div>
                        <div class="field-line"><span class="field-label">Other Name:</span><span class="field-value">{{ $staff->other_name ?? '............................................' }}</span></div>
                        <div class="field-line"><span class="field-label">Sex:</span><span class="field-value">{{ $staff->sex ?? '............................................' }}</span></div>
                        <div class="field-line"><span class="field-label">Date of Birth:</span><span class="field-value">{{ $staff->date_of_birth ? $staff->date_of_birth->format('d M, Y') : '............................................' }}</span></div>
                        <div class="field-line"><span class="field-label">UTS file No:</span><span class="field-value">{{ $staff->uts_file_no ?? '............................................' }}</span></div>
                        <div class="field-line"><span class="field-label">District File No:</span><span class="field-value">{{ $staff->district_file_no ?? '............................................' }}</span></div>
                        <div class="field-line"><span class="field-label">Computer No:</span><span class="field-value">{{ $staff->computer_no ?? '............................................' }}</span></div>
                        <div class="field-line"><span class="field-label">National ID No:</span><span class="field-value">{{ $staff->national_id_no ?? '............................................' }}</span></div>
                        <div class="field-line"><span class="field-label">Registration No/Nos:</span><span class="field-value">{{ $staff->registration_no ?? '............................................' }}</span></div>
                        <div class="field-line"><span class="field-label">Salary Scale:</span><span class="field-value">{{ $staff->salary_scale ?? '............................................' }}</span></div>
                        <div class="field-line"><span class="field-label">Gross Salary:</span><span class="field-value">{{ $staff->gross_salary ? number_format($staff->gross_salary, 2) : '............................................' }}</span></div>
                        <div class="field-line"><span class="field-label">Net Salary:</span><span class="field-value">{{ $staff->net_salary ? number_format($staff->net_salary, 2) : '............................................' }}</span></div>
                        <div class="field-line"><span class="field-label">Tin No:</span><span class="field-value">{{ $staff->tin_no ?? '............................................' }}</span></div>
                        <div class="field-line"><span class="field-label">Date of 1st Appt:</span><span class="field-value">{{ $staff->date_of_1st_appt ? $staff->date_of_1st_appt->format('d M, Y') : '............................................' }}</span></div>
                        <div class="field-line"><span class="field-label">Designation of 1st Appt:</span><span class="field-value">{{ $staff->designation_of_1st_appt ?? '............................................' }}</span></div>
                        <div class="field-line"><span class="field-label">Minute number of 1st Appt:</span><span class="field-value">{{ $staff->minute_no_1st_appt ?? '............................................' }}</span></div>
                        <div class="field-line"><span class="field-label">Date of current Appt:</span><span class="field-value">{{ $staff->date_of_current_appt ? $staff->date_of_current_appt->format('d M, Y') : '............................................' }}</span></div>
                        <div class="field-line"><span class="field-label">Designation of current Appt:</span><span class="field-value">{{ $staff->designation_of_current_appt ?? '............................................' }}</span></div>
                        <div class="field-line"><span class="field-label">Minute number of current Appt:</span><span class="field-value">{{ $staff->minute_no_current_appt ?? '............................................' }}</span></div>
                        <div class="field-line"><span class="field-label">Date of Confirmation:</span><span class="field-value">{{ $staff->date_of_confirmation ? $staff->date_of_confirmation->format('d M, Y') : '............................................' }}</span></div>
                    </div>
                </td>
                <td class="column-cell" style="padding-left: 15px;">
                     <div class="form-column">
                        <div class="field-line"><span class="field-label">Minute number of confirmation:</span><span class="field-value">{{ $staff->minute_no_confirmation ?? '............................................' }}</span></div>
                        <div class="field-line"><span class="field-label">Date of current posting:</span><span class="field-value">{{ $staff->date_of_current_posting ? $staff->date_of_current_posting->format('d M, Y') : '............................................' }}</span></div>
                        <div class="field-line"><span class="field-label">Teaching subjects:</span><span class="field-value">{{ $staff->teaching_subjects ?? '............................................' }}</span></div>
                        <div class="field-line"><span class="field-label">Telephone contacts:</span><span class="field-value">{{ $staff->telephone_contacts ?? '............................................' }}</span></div>
                        <div class="field-line"><span class="field-label">Marital status:</span><span class="field-value">{{ $staff->marital_status ?? '............................................' }}</span></div>
                        <div class="field-line"><span class="field-label">Medical status:</span><span class="field-value">{{ $staff->medical_status ?? '............................................' }}</span></div>
                        <div class="field-line"><span class="field-label">Physical health:</span><span class="field-value">{{ $staff->physical_health ?? '............................................' }}</span></div>
                        <div class="field-line"><span class="field-label">Next of kin:</span><span class="field-value">{{ $staff->next_of_kin ?? '............................................' }}</span></div>
                        <div class="field-line"><span class="field-label">Telephone contacts of next of kin:</span><span class="field-value">{{ $staff->next_of_kin_telephone ?? '............................................' }}</span></div>
                        <div class="field-line"><span class="field-label">Email:</span><span class="field-value">{{ $staff->email ?? '............................................' }}</span></div>
                        <div class="field-line" style="min-height: 40px; align-items: flex-start;"><span class="field-label" style="min-height: 40px;">Other academic qualifications:</span><span class="field-value" style="min-height: 40px;">{{ $staff->other_academic_qualifications ?? '............................................' }}</span></div>
                        <div class="field-line"><span class="field-label">Highest level of Education:</span><span class="field-value">{{ $staff->highest_level_of_education ?? '............................................' }}</span></div>
                        <div class="field-line signature-field" style="margin-top: 30px;"><span class="field-label">Signature:</span><span class="field-value">............................................</span></div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div style="text-align: center; font-size: 8px; margin-top: 20px;">
        Form issued on: {{ $staff->created_at ? $staff->created_at->format('d M, Y H:i:s') : 'N/A' }}
    </div>
</body>
</html>