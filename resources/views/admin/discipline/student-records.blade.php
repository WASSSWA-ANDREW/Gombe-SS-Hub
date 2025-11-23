@extends('layouts.admin')

@section('title', 'Student Records - ' . $student->student_name)

@section('content')
<link rel="stylesheet" href="{{ asset('css/discipline-theme.css') }}">
<div class="discipline-container">
    <!-- Student Header - Enhanced Card -->
    <div style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); padding: 25px; border-radius: var(--radius); box-shadow: var(--shadow); margin-bottom: 30px; color: #fff; display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
        <div style="display: flex; align-items: center; gap: 20px; flex: 1; min-width: 250px;">
            <div style="width: 80px; height: 80px; border-radius: 50%; border: 3px solid #fff; overflow: hidden; flex-shrink: 0;">
                @if($student->photo_path)
                    <img style="width: 100%; height: 100%; object-fit: cover;" src="{{ asset('storage/' . $student->photo_path) }}" alt="{{ $student->student_name }}">
                @else
                    <div style="width: 100%; height: 100%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 2rem;">
                        <i class="fas fa-user" style="color: #fff;"></i>
                    </div>
                @endif
            </div>
            <div>
                <h1 style="font-size: 1.8rem; font-weight: 700; margin-bottom: 10px;">{{ $student->student_name }}</h1>
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    <span class="badge" style="background: rgba(255,255,255,0.2); color: #fff;">📚 {{ $student->level ?? 'N/A' }}</span>
                    <span class="badge" style="background: rgba(255,255,255,0.2); color: #fff;">🏫 {{ $student->class ?? 'N/A' }}</span>
                    <span class="badge" style="background: rgba(255,255,255,0.2); color: #fff;">👤 {{ $student->gender }}</span>
                    <span class="badge" style="background: rgba(255,255,255,0.2); color: #fff;">🎂 Age: {{ $student->date_of_birth ? $student->date_of_birth->age : 'N/A' }}</span>
                </div>
            </div>
        </div>
        <div>
            <a href="{{ route('admin.students.olevel.show', $student) }}" class="btn" style="background: rgba(255,255,255,0.2); border: 2px solid #fff; color: #fff;">
                <i class="fas fa-eye"></i> View Profile
            </a>
        </div>
    </div>

    <!-- Discipline Records Section -->
    <div class="discipline-table-wrapper" style="margin-bottom: 30px;">
        <div class="section-header">
            <div>
                <div class="section-title">
                    <i class="fas fa-exclamation-triangle section-icon"></i>
                    Discipline Track Records
                </div>
                <p style="font-size: 0.85rem; color: #000000; margin-top: 5px;">{{ $student->disciplineTracks->count() }} record{{ $student->disciplineTracks->count() !== 1 ? 's' : '' }}</p>
            </div>
            <a href="{{ route('admin.discipline.create-discipline-track') }}?student={{ $student->id }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Record
            </a>
        </div>

        <!-- Content -->
        @if($student->disciplineTracks->count() > 0)
            <div style="margin-top: 20px;">
                @foreach($student->disciplineTracks as $record)
                    <div class="discipline-record-card">
                        <div class="record-header">
                            <h4 class="record-title">{{ $record->case_name }}</h4>
                            <span class="record-status @if($record->case_status === 'pending') status-pending @else status-resolved @endif">
                                <i class="fas fa-@if($record->case_status === 'pending')hourglass-half@else check-circle@endif"></i>
                                {{ $record->case_status_display }}
                            </span>
                        </div>

                        <!-- Details Grid -->
                        <div class="record-details">
                            <div class="detail-item">
                                <span class="detail-label">Action</span>
                                <span class="detail-value">{{ $record->disciplinary_action_display }}</span>
                            </div>
                            @if($record->resolution)
                                <div class="detail-item">
                                    <span class="detail-label">Resolution</span>
                                    <span class="detail-value">{{ $record->resolution_display }}</span>
                                </div>
                            @endif
                            @if($record->date_of_incident)
                                <div class="detail-item">
                                    <span class="detail-label">Date</span>
                                    <span class="detail-value">{{ $record->date_of_incident->format('M d, Y') }}</span>
                                </div>
                            @endif
                            <div class="detail-item">
                                <span class="detail-label">Recorded</span>
                                <span class="detail-value">{{ $record->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        @if($record->description)
                            <div class="description-block">
                                <p class="description-block-label">Description</p>
                                <p class="description-block-text">{{ $record->description }}</p>
                            </div>
                        @endif

                        @if($record->attachments && count($record->attachments) > 0)
                            <div style="margin-top: 15px; padding: 15px; background: #F0F9FF; border-left: 4px solid #3B82F6; border-radius: 6px;">
                                <p style="font-size: 0.8rem; color: #000000; font-weight: 600; text-transform: uppercase; margin-bottom: 12px;">
                                    <i class="fas fa-paperclip" style="margin-right: 6px;"></i>Attached Documents
                                </p>
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 10px;">
                                    @if(isset($record->attachments['statement_written']))
                                        <a href="{{ asset('storage/' . $record->attachments['statement_written']['path']) }}" target="_blank" class="attachment-link" style="padding: 10px; background: white; border: 1px solid #DBEAFE; border-radius: 6px; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: all 0.2s;">
                                            <i class="fas fa-file-lines" style="color: #3B82F6; font-size: 1rem;"></i>
                                            <div style="flex: 1; min-width: 0;">
                                                <p style="font-size: 0.75rem; color: #000000; font-weight: 600; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Written Statement</p>
                                                <p style="font-size: 0.7rem; color: #000000; margin: 0;">{{ basename($record->attachments['statement_written']['original_name']) }}</p>
                                            </div>
                                            <i class="fas fa-download" style="color: #3B82F6; font-size: 0.9rem;"></i>
                                        </a>
                                    @endif

                                    @if(isset($record->attachments['caution_document']))
                                        <a href="{{ asset('storage/' . $record->attachments['caution_document']['path']) }}" target="_blank" class="attachment-link" style="padding: 10px; background: white; border: 1px solid #FEF3C7; border-radius: 6px; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: all 0.2s;">
                                            <i class="fas fa-warning" style="color: #EAB308; font-size: 1rem;"></i>
                                            <div style="flex: 1; min-width: 0;">
                                                <p style="font-size: 0.75rem; color: #000000; font-weight: 600; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Caution Document</p>
                                                <p style="font-size: 0.7rem; color: #000000; margin: 0;">{{ basename($record->attachments['caution_document']['original_name']) }}</p>
                                            </div>
                                            <i class="fas fa-download" style="color: #EAB308; font-size: 0.9rem;"></i>
                                        </a>
                                    @endif

                                    @if(isset($record->attachments['counselling_agreement']))
                                        <a href="{{ asset('storage/' . $record->attachments['counselling_agreement']['path']) }}" target="_blank" class="attachment-link" style="padding: 10px; background: white; border: 1px solid #DCFCE7; border-radius: 6px; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: all 0.2s;">
                                            <i class="fas fa-handshake" style="color: #22C55E; font-size: 1rem;"></i>
                                            <div style="flex: 1; min-width: 0;">
                                                <p style="font-size: 0.75rem; color: #000000; font-weight: 600; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Counselling Agreement</p>
                                                <p style="font-size: 0.7rem; color: #000000; margin: 0;">{{ basename($record->attachments['counselling_agreement']['original_name']) }}</p>
                                            </div>
                                            <i class="fas fa-download" style="color: #22C55E; font-size: 0.9rem;"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($record->recordedBy)
                            <div style="font-size: 0.85rem; color: #000000; margin-top: 15px; padding-top: 15px; border-top: 1px solid #E5E7EB;">
                                <i class="fas fa-user-circle" style="margin-right: 6px;"></i>
                                Recorded by <strong style="color: #000000;">{{ $record->recordedBy->staff_name }}</strong> on {{ $record->created_at->format('M d, Y g:i A') }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state" style="padding-top: 50px; padding-bottom: 50px;">
                <div class="empty-state-icon">
                    <i class="fas fa-check-double"></i>
                </div>
                <h3 class="empty-state-title">Great Conduct!</h3>
                <p class="empty-state-text">This student has maintained excellent discipline.</p>
            </div>
        @endif
    </div>

    <!-- Counselling Records Section -->
    <div class="discipline-table-wrapper">
        <div class="section-header">
            <div>
                <div class="section-title">
                    <i class="fas fa-heart-pulse section-icon"></i>
                    Counselling Track Records
                </div>
                <p style="font-size: 0.85rem; color: #000000; margin-top: 5px;">{{ $student->counsellingTracks->count() }} record{{ $student->counsellingTracks->count() !== 1 ? 's' : '' }}</p>
            </div>
            <a href="{{ route('admin.counselling.tracks.create') }}?student={{ $student->id }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Record
            </a>
        </div>

        <!-- Content -->
        @if($student->counsellingTracks->count() > 0)
            <div style="margin-top: 20px;">
                @foreach($student->counsellingTracks as $record)
                    <div class="discipline-record-card" style="border-left-color: var(--primary);">
                        <!-- Type and Status Badges -->
                        <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 15px;">
                            <span class="badge" style="
                                @if($record->counselling_type === 'life') background: #E5D4FF; color: #000000;
                                @elseif($record->counselling_type === 'academic') background: #DBEAFE; color: #000000;
                                @elseif($record->counselling_type === 'behavior') background: #FED7AA; color: #000000;
                                @elseif($record->counselling_type === 'gender') background: #FCE7F3; color: #000000;
                                @elseif($record->counselling_type === 'character') background: #D1FAE5; color: #000000;
                                @else background: #FEE2E2; color: #000000; @endif
                            ">
                                {{ $record->counselling_type_display }}
                            </span>
                            <span class="record-status @if($record->status === 'ongoing') status-ongoing @else status-resolved @endif">
                                <i class="fas fa-@if($record->status === 'ongoing')circle-notch @else check-circle @endif"></i>
                                {{ $record->status_display }}
                            </span>
                        </div>

                        <!-- Details Grid -->
                        <div class="record-details">
                            @if($record->counsellor)
                                <div class="detail-item">
                                    <span class="detail-label">Counsellor</span>
                                    <span class="detail-value">{{ $record->counsellor->staff_name }}</span>
                                </div>
                            @else
                                <div class="detail-item">
                                    <span class="detail-label">Counsellor</span>
                                    <span class="detail-value" style="font-style: italic; color: #000000;">Not assigned</span>
                                </div>
                            @endif
                            <div class="detail-item">
                                <span class="detail-label">Session Date</span>
                                <span class="detail-value">{{ $record->date_of_session->format('M d, Y') }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Recorded</span>
                                <span class="detail-value">{{ $record->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Time Ago</span>
                                <span class="detail-value">{{ $record->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        <!-- Notes -->
                        @if($record->notes)
                            <div class="description-block">
                                <p class="description-block-label">Session Notes</p>
                                <p class="description-block-text">{{ $record->notes }}</p>
                            </div>
                        @endif

                        <!-- Outcome -->
                        @if($record->outcome)
                            <div style="padding: 15px; background: #E8F5E9; border-left: 4px solid #4CAF50; border-radius: 6px; margin-top: 15px;">
                                <p style="font-size: 0.8rem; color: #000000; font-weight: 600; text-transform: uppercase; margin-bottom: 8px;">Session Outcome</p>
                                <p style="font-size: 0.95rem; color: #000000; line-height: 1.5;">{{ $record->outcome }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state" style="padding-top: 50px; padding-bottom: 50px;">
                <div class="empty-state-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h3 class="empty-state-title">No Counselling Records</h3>
                <p class="empty-state-text">No counselling sessions have been recorded yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection