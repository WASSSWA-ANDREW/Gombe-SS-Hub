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
                <p style="font-size: 0.85rem; color: var(--text-light); margin-top: 5px;">{{ $student->disciplineTracks->count() }} record{{ $student->disciplineTracks->count() !== 1 ? 's' : '' }}</p>
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

                        @if($record->recordedBy)
                            <div style="font-size: 0.85rem; color: var(--text-light); margin-top: 15px; padding-top: 15px; border-top: 1px solid #E5E7EB;">
                                <i class="fas fa-user-circle" style="margin-right: 6px;"></i>
                                Recorded by <strong style="color: var(--text-dark);">{{ $record->recordedBy->staff_name }}</strong> on {{ $record->created_at->format('M d, Y \a\t g:i A') }}
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
                <p style="font-size: 0.85rem; color: var(--text-light); margin-top: 5px;">{{ $student->counsellingTracks->count() }} record{{ $student->counsellingTracks->count() !== 1 ? 's' : '' }}</p>
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
                                @if($record->counselling_type === 'life') background: #E5D4FF; color: #6B21A8;
                                @elseif($record->counselling_type === 'academic') background: #DBEAFE; color: #0C4A6E;
                                @elseif($record->counselling_type === 'behavior') background: #FED7AA; color: #92400E;
                                @elseif($record->counselling_type === 'gender') background: #FCE7F3; color: #831843;
                                @elseif($record->counselling_type === 'character') background: #D1FAE5; color: #065F46;
                                @else background: #FEE2E2; color: #7F1D1D; @endif
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
                                    <span class="detail-value" style="font-style: italic; color: var(--text-light);">Not assigned</span>
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
                                <p style="font-size: 0.8rem; color: #2E7D32; font-weight: 600; text-transform: uppercase; margin-bottom: 8px;">Session Outcome</p>
                                <p style="font-size: 0.95rem; color: var(--text-dark); line-height: 1.5;">{{ $record->outcome }}</p>
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