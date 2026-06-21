@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<style>
    /* ── Reset & base ── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #F4F6F9; color: #1a1a2e; }

    /* ── Top header bar ── */
    .dash-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fff;
        padding: 0 28px;
        height: 64px;
        border-bottom: 1px solid #e8eaf0;
        position: sticky;
        top: 0;
        z-index: 100;
    }
    .dash-header-left { display: flex; align-items: center; gap: 16px; }
    .dash-header-left .hamburger {
        background: none; border: none; cursor: pointer;
        display: flex; flex-direction: column; gap: 5px; padding: 4px;
    }
    .dash-header-left .hamburger span {
        display: block; width: 22px; height: 2px; background: #555; border-radius: 2px;
    }
    .dash-header-left h1 { font-size: 1.35rem; font-weight: 700; color: #1a1a2e; }
    .dash-header-right { display: flex; align-items: center; gap: 20px; }
    .notif-btn {
        position: relative; background: none; border: none; cursor: pointer;
        color: #555; font-size: 1.3rem; padding: 4px;
    }
    .notif-badge {
        position: absolute; top: 0; right: 0;
        background: #ef4444; color: #fff; font-size: 0.6rem;
        font-weight: 700; border-radius: 9999px;
        min-width: 16px; height: 16px; display: flex; align-items: center; justify-content: center;
        padding: 0 3px; line-height: 1;
    }
    .user-chip {
        display: flex; align-items: center; gap: 10px; cursor: pointer;
    }
    .user-avatar {
        width: 38px; height: 38px; border-radius: 50%;
        background: #1e4db7; color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.85rem; font-weight: 700;
    }
    .user-info { line-height: 1.3; }
    .user-info .user-name { font-size: 0.88rem; font-weight: 600; color: #1a1a2e; }
    .user-info .user-role { font-size: 0.75rem; color: #888; }
    .user-caret { color: #888; font-size: 0.8rem; }

    /* ── Main content area ── */
    .dash-content { padding: 28px 28px 40px; }

    /* ── Stat cards ── */
    .stat-cards { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px; }
    .stat-card {
        background: #fff; border-radius: 14px; padding: 22px 24px;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        display: flex; flex-direction: column; gap: 14px;
    }
    .stat-card-top { display: flex; align-items: flex-start; gap: 16px; }
    .stat-icon {
        width: 52px; height: 52px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; flex-shrink: 0;
    }
    .stat-icon.blue   { background: #EFF3FF; color: #3b6cf8; }
    .stat-icon.green  { background: #EDFAF4; color: #22c55e; }
    .stat-icon.amber  { background: #FFF8EE; color: #f59e0b; }
    .stat-icon.purple { background: #F3F0FF; color: #8b5cf6; }

    .stat-num { font-size: 2rem; font-weight: 800; color: #1a1a2e; line-height: 1; }
    .stat-label { font-size: 0.82rem; color: #888; margin-top: 4px; }
    .stat-link {
        display: flex; align-items: center; gap: 6px;
        font-size: 0.8rem; font-weight: 600; text-decoration: none;
        border-top: 1px solid #f0f0f5; padding-top: 12px;
    }
    .stat-link.blue   { color: #3b6cf8; }
    .stat-link.green  { color: #22c55e; }
    .stat-link.amber  { color: #f59e0b; }
    .stat-link.purple { color: #8b5cf6; }

    /* ── Charts row ── */
    .charts-row { display: grid; grid-template-columns: 1fr 1.6fr; gap: 20px; margin-bottom: 24px; }
    .chart-card {
        background: #fff; border-radius: 14px; padding: 24px;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
    }
    .chart-card h2 { font-size: 1rem; font-weight: 700; margin-bottom: 20px; color: #1a1a2e; }

    /* Donut */
    .donut-wrap { display: flex; align-items: center; gap: 32px; }
    .donut-svg-wrap { position: relative; width: 160px; height: 160px; flex-shrink: 0; }
    .donut-svg-wrap svg { width: 100%; height: 100%; transform: rotate(-90deg); }
    .donut-center {
        position: absolute; inset: 0;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        pointer-events: none;
    }
    .donut-center .donut-num { font-size: 1.6rem; font-weight: 800; color: #1a1a2e; }
    .donut-center .donut-sub { font-size: 0.7rem; color: #aaa; margin-top: 2px; }

    .legend { display: flex; flex-direction: column; gap: 14px; }
    .legend-item { display: flex; align-items: center; gap: 10px; }
    .legend-dot { width: 11px; height: 11px; border-radius: 50%; flex-shrink: 0; }
    .legend-item .leg-label { font-size: 0.82rem; color: #555; flex: 1; }
    .legend-item .leg-count { font-size: 0.82rem; font-weight: 600; color: #1a1a2e; }

    /* Line chart */
    .line-chart-header { display: flex; align-items: center; justify-content: space-between; }
    .lc-legend { display: flex; gap: 18px; }
    .lc-legend-item { display: flex; align-items: center; gap: 6px; font-size: 0.78rem; color: #555; }
    .lc-legend-item span { width: 24px; height: 3px; border-radius: 2px; display: inline-block; }

    /* ── Bottom row ── */
    .bottom-row { display: grid; grid-template-columns: 1fr auto; gap: 20px; align-items: start; }

    /* Recent Updates table */
    .table-card {
        background: #fff; border-radius: 14px; padding: 24px;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
    }
    .table-card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px; }
    .table-card-header h2 { font-size: 1rem; font-weight: 700; color: #1a1a2e; }

    table.updates-table { width: 100%; border-collapse: collapse; }
    .updates-table thead th {
        text-align: left; font-size: 0.78rem; font-weight: 600;
        color: #999; padding: 0 0 10px; border-bottom: 1px solid #f0f0f5;
    }
    .updates-table tbody tr { border-bottom: 1px solid #f8f8fb; }
    .updates-table tbody tr:last-child { border-bottom: none; }
    .updates-table tbody td { padding: 12px 0; font-size: 0.83rem; color: #444; vertical-align: middle; }
    .updates-table tbody td:first-child { font-weight: 500; color: #1a1a2e; }

    .badge {
        display: inline-block; padding: 3px 10px; border-radius: 6px;
        font-size: 0.72rem; font-weight: 600;
    }
    .badge-done    { background: #EDFAF4; color: #16a34a; }
    .badge-pending { background: #FFF8EE; color: #f59e0b; border: 1px solid #fde68a; }

    .view-all-link {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 0.82rem; font-weight: 600; color: #3b6cf8; text-decoration: none;
        margin-top: 16px;
    }

    /* Quick Actions card */
    .quick-card {
        background: #fff; border-radius: 14px; padding: 24px;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        min-width: 270px;
    }
    .quick-card h2 { font-size: 1rem; font-weight: 700; color: #1a1a2e; margin-bottom: 18px; }
    .quick-actions { display: flex; flex-direction: column; gap: 4px; }
    .qa-item {
        display: flex; align-items: center; gap: 14px;
        padding: 12px 8px; border-radius: 10px; cursor: pointer;
        text-decoration: none; transition: background .15s;
    }
    .qa-item:hover { background: #F4F6F9; }
    .qa-icon {
        width: 38px; height: 38px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0;
    }
    .qa-icon.blue   { background: #EFF3FF; color: #3b6cf8; }
    .qa-icon.teal   { background: #EDFAF4; color: #22c55e; }
    .qa-icon.indigo { background: #EEF2FF; color: #6366f1; }
    .qa-icon.slate  { background: #F1F5F9; color: #64748b; }

    .qa-text .qa-title { font-size: 0.85rem; font-weight: 600; color: #1a1a2e; }
    .qa-text .qa-sub   { font-size: 0.74rem; color: #aaa; margin-top: 2px; }
    .qa-arrow { margin-left: auto; color: #ccc; font-size: 0.9rem; }

    /* ── Responsive ── */
    @media (max-width: 1100px) {
        .stat-cards { grid-template-columns: repeat(2, 1fr); }
        .charts-row { grid-template-columns: 1fr; }
        .bottom-row { grid-template-columns: 1fr; }
        .quick-card { min-width: unset; }
    }
    @media (max-width: 600px) {
        .stat-cards { grid-template-columns: 1fr; }
        .dash-content { padding: 16px; }
    }
</style>

<!-- Top header -->
<header class="dash-header">
    <div class="dash-header-left">
        <button class="hamburger" aria-label="Toggle menu">
            <span></span><span></span><span></span>
        </button>
        <h1>Dashboard</h1>
    </div>
    <div class="dash-header-right">
        <button class="notif-btn" aria-label="Notifications">
            🔔
            <span class="notif-badge">3</span>
        </button>
        <div class="user-chip">
            <div class="user-avatar">FN</div>
            <div class="user-info">
                <div class="user-name">Francis Ngumah</div>
                <div class="user-role">Support Engineer</div>
            </div>
            <span class="user-caret">▾</span>
        </div>
    </div>
</header>

<!-- Main content -->
<main class="dash-content">

    <!-- Stat Cards -->
    <div class="stat-cards">
        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon blue">📋</div>
                <div>
                    <div class="stat-num">{{ $totalActivities }}</div>
                    <div class="stat-label">Total Activities</div>
                </div>
            </div>
            <a href="{{ route('activities.index') }}" class="stat-link blue">View all activities &rarr;</a>
        </div>
        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon green">✅</div>
                <div>
                    <div class="stat-num">{{ $completed }}</div>
                    <div class="stat-label">Completed Today</div>
                </div>
            </div>
            <a href="{{ route('activities.index') }}" class="stat-link green">View completed &rarr;</a>
        </div>
        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon amber">⏳</div>
                <div>
                    <div class="stat-num">{{ $pending }}</div>
                    <div class="stat-label">Pending Today</div>
                </div>
            </div>
            <a href="{{ route('activities.index') }}" class="stat-link amber">View pending &rarr;</a>
        </div>
        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon purple">📈</div>
                <div>
                    <div class="stat-num">{{ $updatesToday }}</div>
                    <div class="stat-label">Updates Today</div>
                </div>
            </div>
            <a href="{{ route('handover') }}" class="stat-link purple">View all updates &rarr;</a>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="charts-row">

        <!-- Donut chart -->
        <div class="chart-card">
            <h2>Activities Overview</h2>
            @php
                $dTotal       = $totalActivities ?: 1;
                $completedPct = round(($completed / $dTotal) * 100);
                $pendingPct   = 100 - $completedPct;
            @endphp
            <div class="donut-wrap">
                <div class="donut-svg-wrap">
                    {{-- Donut: dynamic from real data --}}
                    <svg viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="18" cy="18" r="15.915"
                            fill="none" stroke="#22c55e" stroke-width="3.5"
                            stroke-dasharray="{{ $completedPct }} {{ $pendingPct }}" stroke-linecap="round"/>
                        <circle cx="18" cy="18" r="15.915"
                            fill="none" stroke="#f59e0b" stroke-width="3.5"
                            stroke-dasharray="{{ $pendingPct }} {{ $completedPct }}"
                            stroke-dashoffset="-{{ $completedPct }}"
                            stroke-linecap="round"/>
                    </svg>
                    <div class="donut-center">
                        <span class="donut-num">{{ $totalActivities }}</span>
                        <span class="donut-sub">Total</span>
                    </div>
                </div>
                <div class="legend">
                    <div class="legend-item">
                        <span class="legend-dot" style="background:#22c55e;"></span>
                        <span class="leg-label">Completed</span>
                        <span class="leg-count">{{ $completed }} ({{ $completedPct }}%)</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-dot" style="background:#f59e0b;"></span>
                        <span class="leg-label">Pending</span>
                        <span class="leg-count">{{ $pending }} ({{ $pendingPct }}%)</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-dot" style="background:#d1d5db;"></span>
                        <span class="leg-label">Not Updated</span>
                        <span class="leg-count">0 (0%)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Line / area chart -->
        <div class="chart-card">
            <div class="line-chart-header">
                <h2>Status Overview</h2>
                <div class="lc-legend">
                    <div class="lc-legend-item"><span style="background:#22c55e;"></span> Completed</div>
                    <div class="lc-legend-item"><span style="background:#f59e0b;"></span> Pending</div>
                </div>
            </div>

            {{-- SVG line/area chart (Mon-Sun) --}}
            <svg viewBox="0 0 580 180" xmlns="http://www.w3.org/2000/svg" style="width:100%;overflow:visible;margin-top:8px;">
                <defs>
                    <linearGradient id="gGreen" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#22c55e" stop-opacity=".25"/>
                        <stop offset="100%" stop-color="#22c55e" stop-opacity=".03"/>
                    </linearGradient>
                    <linearGradient id="gAmber" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#f59e0b" stop-opacity=".25"/>
                        <stop offset="100%" stop-color="#f59e0b" stop-opacity=".03"/>
                    </linearGradient>
                </defs>

                {{-- Y-axis labels --}}
                @foreach([0,5,10,15,20] as $y)
                    <text x="10" y="{{ 160 - $y * 7 }}" font-size="9" fill="#bbb" text-anchor="middle">{{ $y }}</text>
                @endforeach

                {{-- Horizontal grid lines --}}
                @foreach([0,5,10,15,20] as $y)
                    <line x1="25" y1="{{ 160 - $y * 7 }}" x2="575" y2="{{ 160 - $y * 7 }}"
                        stroke="#f0f0f5" stroke-width="1"/>
                @endforeach

                @php
                    $chartDays      = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
                    $chartCompleted = [10, 13, 14, 16, 17, 15, 12];
                    $chartPending   = [5,  4,  5,  6,  5,  7,  5];
                    $chartXs        = [35, 120, 205, 290, 375, 460, 545];
                    $scaleY         = fn($v) => 160 - $v * 7;
                    $pts            = fn($data) => implode(' ', array_map(fn($i,$v) => $chartXs[$i].','.$scaleY($v), array_keys($data), $data));
                @endphp

                {{-- Green area --}}
                <polygon
                    points="{{ $pts($chartCompleted) }} 545,160 35,160"
                    fill="url(#gGreen)" />
                {{-- Amber area --}}
                <polygon
                    points="{{ $pts($chartPending) }} 545,160 35,160"
                    fill="url(#gAmber)" />

                {{-- Green line --}}
                <polyline points="{{ $pts($chartCompleted) }}"
                    fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linejoin="round" stroke-linecap="round"/>
                {{-- Amber line --}}
                <polyline points="{{ $pts($chartPending) }}"
                    fill="none" stroke="#f59e0b" stroke-width="2.5" stroke-linejoin="round" stroke-linecap="round"/>

                {{-- Dots --}}
                @foreach($chartXs as $i => $x)
                    <circle cx="{{ $x }}" cy="{{ $scaleY($chartCompleted[$i]) }}" r="4" fill="#22c55e" stroke="#fff" stroke-width="1.5"/>
                    <circle cx="{{ $x }}" cy="{{ $scaleY($chartPending[$i]) }}" r="4" fill="#f59e0b" stroke="#fff" stroke-width="1.5"/>
                @endforeach

                {{-- X axis labels --}}
                @foreach($chartDays as $i => $d)
                    <text x="{{ $chartXs[$i] }}" y="175" font-size="9" fill="#aaa" text-anchor="middle">{{ $d }}</text>
                @endforeach
            </svg>
        </div>

    </div>

    <!-- Bottom Row -->
    <div class="bottom-row">

        <!-- Recent Updates table -->
        <div class="table-card">
            <div class="table-card-header">
                <h2>Recent Updates (Today)</h2>
            </div>
            <table class="updates-table">
                <thead>
                    <tr>
                        <th>Activity</th>
                        <th>Status</th>
                        <th>Updated By</th>
                        <th>Time</th>
                        <th>Remark</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentUpdates as $update)
                    <tr>
                        <td>{{ $update->activity->name ?? 'Activity' }}</td>
                        <td><span class="badge {{ $update->status === 'Done' ? 'badge-done' : 'badge-pending' }}">{{ $update->status }}</span></td>
                        <td>{{ $update->updatedBy->name ?? 'System' }}</td>
                        <td>{{ $update->created_at->format('h:i A') }}</td>
                        <td>{{ $update->remark ?: '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <a href="{{ route('handover') }}" class="view-all-link">View all updates &rarr;</a>
        </div>

        <!-- Quick Actions -->
        <div class="quick-card">
            <h2>Quick Actions</h2>
            <div class="quick-actions">
                <a href="{{ route('activities.create') }}" class="qa-item">
                    <div class="qa-icon blue">➕</div>
                    <div class="qa-text">
                        <div class="qa-title">Add New Activity</div>
                        <div class="qa-sub">Create a new activity</div>
                    </div>
                    <span class="qa-arrow">›</span>
                </a>
                <a href="{{ route('activities.update', ['id' => 1]) }}" class="qa-item">
                    <div class="qa-icon teal">🔄</div>
                    <div class="qa-text">
                        <div class="qa-title">Update Activity</div>
                        <div class="qa-sub">Update activity status</div>
                    </div>
                    <span class="qa-arrow">›</span>
                </a>
                <a href="{{ route('handover') }}" class="qa-item">
                    <div class="qa-icon indigo">📅</div>
                    <div class="qa-text">
                        <div class="qa-title">Daily Handover</div>
                        <div class="qa-sub">View today's handover</div>
                    </div>
                    <span class="qa-arrow">›</span>
                </a>
                <a href="{{ route('reports') }}" class="qa-item">
                    <div class="qa-icon slate">📄</div>
                    <div class="qa-text">
                        <div class="qa-title">Generate Report</div>
                        <div class="qa-sub">Generate activity reports</div>
                    </div>
                    <span class="qa-arrow">›</span>
                </a>
            </div>
        </div>

    </div>

</main>

@endsection