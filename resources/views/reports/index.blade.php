@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<style>
    *, *::before, *::after { box-sizing: border-box; }

    .page-header {
        display: flex; align-items: center; justify-content: space-between;
        background: #fff; padding: 0 28px; height: 64px;
        border-bottom: 1px solid #e8eaf0; position: sticky; top: 0; z-index: 100;
    }
    .page-header-left { display: flex; align-items: center; gap: 16px; }
    .page-header-left .hamburger { background: none; border: none; cursor: pointer; display: flex; flex-direction: column; gap: 5px; padding: 4px; }
    .page-header-left .hamburger span { display: block; width: 22px; height: 2px; background: #555; border-radius: 2px; }
    .page-header-left h1 { font-size: 1.35rem; font-weight: 700; color: #1a1a2e; margin: 0; }
    .page-header-right { display: flex; align-items: center; gap: 20px; }
    .notif-btn { position: relative; background: none; border: none; cursor: pointer; color: #555; font-size: 1.3rem; padding: 4px; }
    .notif-badge { position: absolute; top: 0; right: 0; background: #ef4444; color: #fff; font-size: .6rem; font-weight: 700; border-radius: 9999px; min-width: 16px; height: 16px; display: flex; align-items: center; justify-content: center; padding: 0 3px; }
    .user-chip { display: flex; align-items: center; gap: 10px; cursor: pointer; }
    .user-avatar { width: 38px; height: 38px; border-radius: 50%; background: #1e4db7; color: #fff; display: flex; align-items: center; justify-content: center; font-size: .85rem; font-weight: 700; }
    .user-info .user-name { font-size: .88rem; font-weight: 600; color: #1a1a2e; }
    .user-info .user-role { font-size: .75rem; color: #888; }

    .reports-body { padding: 28px; }
    .breadcrumb-row { display: flex; align-items: center; gap: 6px; font-size: .78rem; color: #aaa; margin-bottom: 20px; }
    .breadcrumb-row a { color: #3b6cf8; text-decoration: none; }
    .breadcrumb-row .sep { color: #ccc; }

    .title-row { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 12px; }
    .title-row-left h2 { font-size: 1.4rem; font-weight: 800; color: #1a1a2e; margin: 0 0 4px; }
    .title-row-left p { font-size: .82rem; color: #999; margin: 0; }

    /* Stats strip */
    .stats-strip { display: flex; gap: 16px; margin-bottom: 20px; flex-wrap: wrap; }
    .strip-card { flex: 1; min-width: 140px; background: #fff; border-radius: 12px; padding: 16px 20px; box-shadow: 0 1px 4px rgba(0,0,0,.06); display: flex; align-items: center; gap: 14px; }
    .strip-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
    .strip-icon.blue  { background: #EFF3FF; }
    .strip-icon.green { background: #EDFAF4; }
    .strip-icon.amber { background: #FFF8EE; }
    .strip-num   { font-size: 1.4rem; font-weight: 800; color: #1a1a2e; line-height: 1; }
    .strip-label { font-size: .74rem; color: #999; margin-top: 3px; }

    /* Filter card */
    .filter-card { background: #fff; border-radius: 14px; box-shadow: 0 1px 4px rgba(0,0,0,.06); padding: 20px 24px; margin-bottom: 20px; }
    .filter-card h3 { font-size: .88rem; font-weight: 700; color: #1a1a2e; margin: 0 0 14px; }
    .filter-grid { display: grid; grid-template-columns: 1fr 1fr 2fr; gap: 14px; align-items: end; flex-wrap: wrap; }
    .filter-grid label { display: block; font-size: .73rem; font-weight: 700; color: #6b7280; margin-bottom: 6px; text-transform: uppercase; letter-spacing: .04em; }
    .filter-grid input {
        width: 100%; padding: 10px 12px; border: 1.5px solid #e8eaf0; border-radius: 9px;
        font-size: .84rem; color: #1a1a2e; outline: none; font-family: inherit;
    }
    .filter-grid input:focus { border-color: #3b6cf8; box-shadow: 0 0 0 3px rgba(59,108,248,.1); }
    .filter-actions { display: flex; gap: 10px; margin-top: 14px; flex-wrap: wrap; }
    .btn-filter { background: #3b6cf8; color: #fff; border: none; border-radius: 9px; padding: 10px 22px; font-size: .84rem; font-weight: 700; cursor: pointer; }
    .btn-reset  { background: #f4f6f9; color: #555; border: 1.5px solid #e8eaf0; border-radius: 9px; padding: 10px 18px; font-size: .84rem; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; }
    .btn-export-csv { background: #16a34a; color: #fff; border: none; border-radius: 9px; padding: 10px 18px; font-size: .84rem; font-weight: 600; cursor: pointer; margin-left: auto; }

    /* Active filter banner */
    .filter-active-banner {
        background: #EFF3FF; border: 1px solid #c7d7ff; border-radius: 10px;
        padding: 10px 16px; margin-bottom: 16px;
        font-size: .82rem; color: #3b6cf8; font-weight: 600;
        display: flex; align-items: center; gap: 8px;
    }

    /* Table */
    .table-card { background: #fff; border-radius: 14px; box-shadow: 0 1px 4px rgba(0,0,0,.06); overflow: hidden; }
    table.report-table { width: 100%; border-collapse: collapse; }
    .report-table thead tr { border-bottom: 1px solid #f0f0f5; }
    .report-table thead th {
        padding: 14px 16px; font-size: .73rem; font-weight: 700;
        color: #aaa; text-transform: uppercase; letter-spacing: .05em;
        background: #fafbfc; text-align: left; white-space: nowrap;
    }
    .report-table tbody tr { border-bottom: 1px solid #f8f8fb; transition: background .12s; }
    .report-table tbody tr:last-child { border-bottom: none; }
    .report-table tbody tr:hover { background: #f9faff; }
    .report-table tbody td { padding: 13px 16px; font-size: .83rem; color: #444; vertical-align: middle; }

    /* Personnel bio cell */
    .bio-cell { display: flex; flex-direction: column; gap: 2px; }
    .bio-name { font-weight: 700; color: #1a1a2e; font-size: .84rem; }
    .bio-meta { font-size: .72rem; color: #999; display: flex; gap: 8px; flex-wrap: wrap; }
    .bio-tag  { display: inline-flex; align-items: center; gap: 3px; background: #f4f6f9; border-radius: 5px; padding: 1px 7px; }

    /* Activity cell */
    .act-cell .act-name { font-weight: 600; color: #1a1a2e; }
    .act-cell .act-time { font-size: .72rem; color: #aaa; margin-top: 2px; }

    .badge-pill { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 999px; font-size: .72rem; font-weight: 600; }
    .badge-pending { background: #FFF8EE; color: #f59e0b; }
    .badge-done    { background: #EDFAF4; color: #16a34a; }

    .remark-cell { max-width: 220px; word-break: break-word; font-size: .81rem; color: #555; }

    .empty-state { text-align: center; padding: 48px 20px; color: #aaa; font-size: .88rem; }
    .empty-state .empty-icon { font-size: 2.5rem; margin-bottom: 10px; }

    @media (max-width: 900px) {
        .filter-grid { grid-template-columns: 1fr 1fr; }
        .stats-strip { flex-wrap: wrap; }
        .strip-card  { flex: 1 1 calc(50% - 8px); }
        .reports-body { padding: 16px; }
    }
    @media (max-width: 600px) {
        .filter-grid { grid-template-columns: 1fr; }
        .strip-card  { flex: 1 1 100%; }
    }
</style>

<!-- Page header -->
<header class="page-header">
    <div class="page-header-left">
        <button class="hamburger"><span></span><span></span><span></span></button>
        <h1>Reports</h1>
    </div>
    <div class="page-header-right">
        <button class="notif-btn">🔔<span class="notif-badge">3</span></button>
        <div class="user-chip">
            <div class="user-avatar">ST</div>
            <div class="user-info">
                <div class="user-name">Staff User</div>
                <div class="user-role">Support</div>
            </div>
        </div>
    </div>
</header>

<div class="reports-body">

    <div class="breadcrumb-row">
        <a href="{{ route('dashboard') }}">Home</a>
        <span class="sep">›</span>
        <span style="color:#555;font-weight:500;">Reports</span>
    </div>

    <div class="title-row">
        <div class="title-row-left">
            <h2>Activity History Reports</h2>
            <p>Query and export activity update histories by custom date range</p>
        </div>
    </div>

    <!-- Stats strip -->
    <div class="stats-strip">
        <div class="strip-card">
            <div class="strip-icon blue">📋</div>
            <div><div class="strip-num">{{ $totalCount }}</div><div class="strip-label">Total Records</div></div>
        </div>
        <div class="strip-card">
            <div class="strip-icon green">✅</div>
            <div><div class="strip-num">{{ $doneCount }}</div><div class="strip-label">Done</div></div>
        </div>
        <div class="strip-card">
            <div class="strip-icon amber">⏳</div>
            <div><div class="strip-num">{{ $pendingCount }}</div><div class="strip-label">Pending</div></div>
        </div>
    </div>

    <!-- Server-side filter form -->
    <div class="filter-card">
        <h3>🔍 Filter by Date Range &amp; Keyword</h3>
        <form method="GET" action="{{ route('reports') }}">
            <div class="filter-grid">
                <div>
                    <label for="start_date">Start Date</label>
                    <input type="date" id="start_date" name="start_date"
                           value="{{ $startDate ? $startDate->toDateString() : '' }}"
                           max="{{ now()->toDateString() }}">
                </div>
                <div>
                    <label for="end_date">End Date</label>
                    <input type="date" id="end_date" name="end_date"
                           value="{{ $endDate ? $endDate->toDateString() : '' }}"
                           max="{{ now()->toDateString() }}">
                </div>
                <div>
                    <label for="search">Search Keyword</label>
                    <input type="text" id="search" name="search"
                           value="{{ $search }}"
                           placeholder="Activity name, personnel, remark, status…">
                </div>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-filter">Apply Filter</button>
                <a href="{{ route('reports') }}" class="btn-reset">Clear</a>
                <button type="button" class="btn-export-csv" id="reportsExport">⬇ Export CSV</button>
            </div>
        </form>
    </div>

    <!-- Active filter banner -->
    @if($startDate || $endDate || $search)
    <div class="filter-active-banner">
        🔎 Showing filtered results
        @if($startDate) — From: <strong>{{ $startDate->format('d M Y') }}</strong> @endif
        @if($endDate) — To: <strong>{{ $endDate->format('d M Y') }}</strong> @endif
        @if($search) — Keyword: <strong>"{{ $search }}"</strong> @endif
        &nbsp;·&nbsp; {{ $totalCount }} record(s) found
    </div>
    @endif

    <!-- Results table -->
    <div class="table-card">
        @if($reports->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                No records found for the selected filters.<br>
                <a href="{{ route('reports') }}" style="color:#3b6cf8;font-weight:600;text-decoration:none;margin-top:8px;display:inline-block;">Clear filters</a>
            </div>
        @else
            <table class="report-table" id="reportTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Activity</th>
                        <th>Personnel Bio</th>
                        <th>Status</th>
                        <th>Remark</th>
                        <th>Date</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $i => $report)
                    @php
                        $person = $report->updatedBy;
                    @endphp
                    <tr>
                        <td style="color:#ccc;font-size:.75rem;">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <div class="act-cell">
                                <div class="act-name">{{ $report->activity->name ?? '—' }}</div>
                                <div class="act-time">{{ $report->activity->description ?? '' }}</div>
                            </div>
                        </td>
                        {{-- Requirement 3: Bio details of personnel --}}
                        <td>
                            <div class="bio-cell">
                                <div class="bio-name">{{ $person->name ?? 'System' }}</div>
                                <div class="bio-meta">
                                    @if($person)
                                        @if($person->staff_id)
                                            <span class="bio-tag">🪪 {{ $person->staff_id }}</span>
                                        @endif
                                        @if($person->department)
                                            <span class="bio-tag">🏢 {{ $person->department }}</span>
                                        @endif
                                        @if($person->shift)
                                            <span class="bio-tag">🕐 {{ $person->shift }}</span>
                                        @endif
                                        @if($person->phone)
                                            <span class="bio-tag">📞 {{ $person->phone }}</span>
                                        @endif
                                    @else
                                        <span class="bio-tag">System</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge-pill {{ $report->status === 'Done' ? 'badge-done' : 'badge-pending' }}">
                                {{ $report->status === 'Done' ? '✅ Done' : '⏳ Pending' }}
                            </span>
                        </td>
                        <td class="remark-cell">{{ $report->remark ?? '—' }}</td>
                        <td style="white-space:nowrap;color:#555;">{{ $report->created_at->format('d M Y') }}</td>
                        <td style="white-space:nowrap;color:#888;">{{ $report->created_at->format('h:i A') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</div>

<script>
    // CSV export of the currently displayed (server-filtered) table
    document.getElementById('reportsExport')?.addEventListener('click', function () {
        const rows = document.querySelectorAll('#reportTable tbody tr');
        const headers = ['#', 'Activity', 'Personnel', 'Staff ID', 'Department', 'Shift', 'Phone', 'Status', 'Remark', 'Date', 'Time'];
        const csv = [headers.join(',')];

        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const num        = cells[0]?.textContent.trim() ?? '';
            const activity   = cells[1]?.querySelector('.act-name')?.textContent.trim() ?? '';
            const name       = cells[2]?.querySelector('.bio-name')?.textContent.trim() ?? '';
            const tags       = Array.from(cells[2]?.querySelectorAll('.bio-tag') ?? []).map(t => t.textContent.trim());
            const staffId    = tags.find(t => t.startsWith('🪪'))?.replace('🪪','').trim() ?? '';
            const dept       = tags.find(t => t.startsWith('🏢'))?.replace('🏢','').trim() ?? '';
            const shift      = tags.find(t => t.startsWith('🕐'))?.replace('🕐','').trim() ?? '';
            const phone      = tags.find(t => t.startsWith('📞'))?.replace('📞','').trim() ?? '';
            const status     = cells[3]?.textContent.trim() ?? '';
            const remark     = cells[4]?.textContent.trim() ?? '';
            const date       = cells[5]?.textContent.trim() ?? '';
            const time       = cells[6]?.textContent.trim() ?? '';

            const row2 = [num, activity, name, staffId, dept, shift, phone, status, remark, date, time]
                .map(v => '"' + String(v).replace(/"/g, '""') + '"');
            csv.push(row2.join(','));
        });

        const blob = new Blob([csv.join('\n')], { type: 'text/csv' });
        const url  = URL.createObjectURL(blob);
        const a    = document.createElement('a');
        const dateStr = new Date().toISOString().slice(0, 10);
        a.href = url; a.download = `activity-report-${dateStr}.csv`;
        document.body.appendChild(a); a.click(); a.remove();
        URL.revokeObjectURL(url);
    });
</script>
@endsection
