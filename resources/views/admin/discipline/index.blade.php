@extends('layouts.admin')

@section('title', 'Discipline Records Dashboard')

@section('content')
<link rel="stylesheet" href="{{ asset('css/discipline-theme.css') }}">
<div class="discipline-container">
    <!-- Header -->
    <h1 class="discipline-page-title">
        <i class="fas fa-shield-alt"></i>
        Discipline Management
    </h1>

    <!-- Statistics Cards -->
    <div class="discipline-stats">
        <!-- Total Discipline Records -->
        <div class="stat-card">
            <div class="stat-card-content">
                <p class="stat-card-label">Discipline Records</p>
                <p class="stat-card-value">{{ $totalDisciplineRecords }}</p>
            </div>
            <div class="stat-card-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>

        <!-- Pending Cases -->
        <div class="stat-card">
            <div class="stat-card-content">
                <p class="stat-card-label">Pending Cases</p>
                <p class="stat-card-value">{{ $pendingCases }}</p>
            </div>
            <div class="stat-card-icon">
                <i class="fas fa-hourglass-half"></i>
            </div>
        </div>

        <!-- Total Counselling Records -->
        <div class="stat-card">
            <div class="stat-card-content">
                <p class="stat-card-label">Counselling Records</p>
                <p class="stat-card-value">{{ $totalCounsellingRecords }}</p>
            </div>
            <div class="stat-card-icon">
                <i class="fas fa-heart"></i>
            </div>
        </div>

        <!-- Ongoing Counselling -->
        <div class="stat-card">
            <div class="stat-card-content">
                <p class="stat-card-label">Ongoing Sessions</p>
                <p class="stat-card-value">{{ $ongoingCounselling }}</p>
            </div>
            <div class="stat-card-icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>

    <!-- Record Categories -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <!-- Discipline Track Records -->
        <a href="{{ route('admin.discipline.discipline-tracks') }}" style="background: var(--surface); padding: 25px; border-radius: var(--radius); box-shadow: var(--shadow); text-decoration: none; color: inherit; transition: var(--transition); border-left: 5px solid var(--primary); display: flex; flex-direction: column;">
            <div style="margin-bottom: 15px;">
                <i class="fas fa-list" style="font-size: 2rem; color: var(--accent);"></i>
            </div>
            <h3 style="font-size: 1.2rem; font-weight: 700; color: var(--text-dark); margin-bottom: 10px;">Discipline Records</h3>
            <p style="color: var(--text-light); font-size: 0.95rem; flex: 1; margin-bottom: 15px;">Manage disciplinary actions, case resolutions, and track student behavior incidents comprehensively.</p>
            <div style="display: inline-flex; align-items: center; color: var(--accent); font-weight: 600; gap: 8px;">
                View Records
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>

        <!-- Counselling Track Records -->
        <a href="{{ route('admin.counselling.tracks.index') }}" style="background: var(--surface); padding: 25px; border-radius: var(--radius); box-shadow: var(--shadow); text-decoration: none; color: inherit; transition: var(--transition); border-left: 5px solid var(--primary); display: flex; flex-direction: column;">
            <div style="margin-bottom: 15px;">
                <i class="fas fa-heart" style="font-size: 2rem; color: var(--primary);"></i>
            </div>
            <h3 style="font-size: 1.2rem; font-weight: 700; color: var(--text-dark); margin-bottom: 10px;">Counselling Records</h3>
            <p style="color: var(--text-light); font-size: 0.95rem; flex: 1; margin-bottom: 15px;">Track counselling sessions, types of counselling provided, and student progress effectively.</p>
            <div style="display: inline-flex; align-items: center; color: var(--primary); font-weight: 600; gap: 8px;">
                View Records
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>
    </div>

    <!-- Recent Activity -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <!-- Recent Discipline Records -->
        <div class="discipline-table-wrapper">
            <div class="section-header">
                <div class="section-title">
                    <i class="fas fa-list-check section-icon"></i>
                    Recent Discipline Records
                </div>
            </div>
            <div style="margin-top: 15px;">
                @forelse($recentDisciplineRecords as $record)
                    <div style="padding: 12px; background: #F9FAFB; border-radius: 8px; margin-bottom: 10px; border-left: 3px solid var(--primary); display: flex; justify-content: space-between; align-items: center;">
                        <div style="flex: 1;">
                            <p style="font-size: 0.95rem; font-weight: 600; color: var(--text-dark);">{{ $record->student->student_name }}</p>
                            <p style="font-size: 0.85rem; color: var(--text-light); margin-top: 3px;">{{ $record->case_name }}</p>
                        </div>
                        <span class="record-status @if($record->case_status === 'pending') status-pending @else status-resolved @endif">
                            {{ $record->case_status_display }}
                        </span>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <p class="empty-state-text">No recent records</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Counselling Records -->
        <div class="discipline-table-wrapper">
            <div class="section-header">
                <div class="section-title">
                    <i class="fas fa-heart-pulse section-icon"></i>
                    Recent Counselling Records
                </div>
            </div>
            <div style="margin-top: 15px;">
                @forelse($recentCounsellingRecords as $record)
                    <div style="padding: 12px; background: #F9FAFB; border-radius: 8px; margin-bottom: 10px; border-left: 3px solid var(--primary); display: flex; justify-content: space-between; align-items: center;">
                        <div style="flex: 1;">
                            <p style="font-size: 0.95rem; font-weight: 600; color: var(--text-dark);">{{ $record->student->student_name }}</p>
                            <p style="font-size: 0.85rem; color: var(--text-light); margin-top: 3px;">{{ $record->counselling_type_display }} • {{ $record->date_of_session->format('M d, Y') }}</p>
                        </div>
                        <span class="record-status @if($record->status === 'ongoing') status-ongoing @else status-resolved @endif">
                            {{ $record->status_display }}
                        </span>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <p class="empty-state-text">No recent records</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Students with Most Issues -->
    @if($studentsWithMostIssues->count() > 0)
    <div class="discipline-table-wrapper">
        <div class="section-header">
            <div class="section-title">
                <i class="fas fa-triangle-exclamation section-icon"></i>
                Students with Most Issues
            </div>
            <span class="badge badge-accent">{{ $studentsWithMostIssues->count() }} students</span>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px; margin-top: 20px;">
            @foreach($studentsWithMostIssues as $student)
                <a href="{{ route('admin.discipline.student-records', $student->id) }}" class="discipline-record-card" style="border-left: 3px solid var(--accent);">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div style="flex: 1;">
                            <p style="font-size: 0.95rem; font-weight: 600; color: var(--text-dark);">{{ $student->student_name }}</p>
                            <p style="font-size: 0.85rem; color: var(--text-light); margin-top: 5px;">
                                <i class="fas fa-exclamation-circle" style="color: var(--accent); margin-right: 4px;"></i>
                                {{ $student->discipline_tracks_count }} incident{{ $student->discipline_tracks_count !== 1 ? 's' : '' }}
                            </p>
                        </div>
                        <div style="font-size: 1.2rem; color: var(--accent);">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection