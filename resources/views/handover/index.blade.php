{{--
    Daily Handover view
    --------------------------------------------------------------
    Expected data from the controller (HandoverController@show):

    $date       Carbon instance — the day being viewed (defaults to today)
    $stats      array  ['total' => int, 'completed' => int, 'pending' => int]
    $handovers  Collection of objects/arrays, each with:
                    id            -> activity_log id
                    activity_id   -> activities.id (used to deep-link "Continue Work")
                    activity_name -> string
                    status        -> 'Done' | 'Pending'
                    remark        -> string
                    updated_by    -> string (personnel name)
                    updated_at    -> string, already formatted e.g. "09:30 AM"

    Example controller call:
        $date = $request->date ? Carbon::parse($request->date) : now();
        $handovers = ActivityLog::with(['activity', 'user'])
            ->whereDate('created_at', $date)
            ->latest()
            ->get();
--}}
@extends('layouts.app')

@section('title', 'Daily Handover')

@section('content')
@php
    // Ensure $date is available and is a Carbon instance to avoid undefined variable errors
    if (!isset($date) || !$date) {
        $date = \Carbon\Carbon::now();
    } elseif (!($date instanceof \Carbon\Carbon)) {
        try {
            $date = \Carbon\Carbon::parse($date);
        } catch (Exception $e) {
            $date = \Carbon\Carbon::now();
        }
    }
    // Ensure $stats is available with sensible defaults to avoid undefined variable errors
    if (!isset($stats) || !is_array($stats)) {
        $total = 0; $completed = 0; $pending = 0;
        if (isset($handovers) && is_iterable($handovers)) {
            foreach ($handovers as $h) {
                $total++;
                $s = null;
                if (is_object($h) && isset($h->status)) $s = $h->status;
                elseif (is_array($h) && isset($h['status'])) $s = $h['status'];
                if ($s === 'Pending') $pending++; else $completed++;
            }
        }
        $stats = ['total' => $total, 'completed' => $completed, 'pending' => $pending];
    }
    // Ensure $handovers exists as an iterable collection to avoid undefined variable errors
    if (!isset($handovers) || !is_iterable($handovers)) {
        $handovers = collect([]);
    }
@endphp
<style>
    .handover-body {
        width: 100%;
        box-sizing: border-box;
        padding: 28px;
    }
    .breadcrumb-row {
        display: flex; align-items: center; gap: 6px;
        font-size: 0.78rem; color: #aaa; margin-bottom: 20px;
    }
    .breadcrumb-row a { color: #3b6cf8; text-decoration: none; }
    .breadcrumb-row .sep { color: #ccc; }
    .breadcrumb-row .current { color: #555; font-weight: 500; }

    .title-row {
        display: flex; align-items: flex-start; justify-content: space-between;
        margin-bottom: 20px; gap: 16px; flex-wrap: wrap;
    }
    .title-row-left h2 { font-size: 1.4rem; font-weight: 800; color: #1a1a2e; margin: 0 0 4px; }
    .title-row-left p { font-size: 0.82rem; color: #999; margin: 0; }
    .title-row-right { display: flex; align-items: center; gap: 10px; }

    .date-nav {
        display: flex; align-items: center; gap: 6px;
        background: #fff; border: 1px solid #e8eaf0; border-radius: 10px;
        padding: 4px 6px; box-shadow: 0 1px 3px rgba(0,0,0,.04);
    }
    .date-nav a {
        display: flex; align-items: center; justify-content: center;
        width: 30px; height: 30px; border-radius: 8px; color: #555;
        text-decoration: none; font-size: .9rem;
    }
    .date-nav a:hover { background: #f3f4f8; }
    .date-nav input[type="date"] {
        border: none; outline: none; font-size: 0.82rem; color: #333;
        font-weight: 600; padding: 4px 6px;
    }

    .btn-export {
        display: inline-flex; align-items: center; gap: 8px;
        background: #3b6cf8; color: #fff;
        border: none; border-radius: 10px;
        padding: 10px 18px; font-size: 0.85rem; font-weight: 600;
        cursor: pointer; text-decoration: none;
        box-shadow: 0 2px 8px rgba(59,108,248,.25);
    }

    .stats-strip {
        display: flex;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
        width: 100%;
    }
    .strip-card {
        flex: 1; min-width: 160px; background: #fff; border-radius: 12px; padding: 16px 20px;
        box-shadow: 0 1px 4px rgba(0,0,0,.06); display: flex; align-items: center; gap: 14px;
    }
    .strip-icon {
        width: 42px; height: 42px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; flex-shrink: 0;
    }
    .strip-icon.blue { background: #EFF3FF; }
    .strip-icon.green { background: #EDFAF4; }
    .strip-icon.amber { background: #FFF8EE; }
    .strip-num { font-size: 1.4rem; font-weight: 800; color: #1a1a2e; line-height: 1; }
    .strip-label { font-size: 0.74rem; color: #999; margin-top: 3px; }

    .toolbar {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 16px; gap: 12px; flex-wrap: wrap;
        width: 100%;
    }
    .search-box {
        display: flex; align-items: center; gap: 8px;
        background: #fff; border: 1px solid #e8eaf0; border-radius: 10px;
        padding: 8px 14px; font-size: 0.83rem; color: #555;
        box-shadow: 0 1px 3px rgba(0,0,0,.04);
    }
    .search-box input {
        border: none; outline: none; background: transparent;
        font-size: 0.83rem; color: #333; width: 220px;
    }
    .filter-pills { display: flex; gap: 8px; }
    .pill {
        padding: 6px 14px; border-radius: 20px; font-size: 0.78rem; font-weight: 600;
        cursor: pointer; border: 1.5px solid #e8eaf0; background: #fff; color: #888;
        transition: all .15s;
    }
    .pill.active-all { background: #1a1a2e; color: #fff; border-color: #1a1a2e; }
    .pill.active-done { background: #EDFAF4; color: #16a34a; border-color: #bbf7d0; }
    .pill.active-pending { background: #FFF8EE; color: #f59e0b; border-color: #fde68a; }

    .table-card {
        width: 100%;
        min-width: 0;
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        overflow: hidden;
    }
    table.handover-table {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
    }
    .handover-table thead tr { border-bottom: 1px solid #f0f0f5; }
    .handover-table thead th {
        padding: 14px 20px; font-size: 0.75rem; font-weight: 700;
        color: #aaa; text-transform: uppercase; letter-spacing: .05em;
        background: #fafbfc; text-align: left;
    }
    .handover-table tbody tr { border-bottom: 1px solid #f8f8fb; }
    .handover-table tbody td { padding: 15px 20px; font-size: 0.84rem; color: #444; vertical-align: middle; }
    .handover-table tbody tr:hover { background: #f9faff; }
    .handover-table tbody tr.pending-row { background: #fffaf3; border-left: 3px solid #f59e0b; }
    .handover-table tbody tr.done-row    { background: #f9fffe; border-left: 3px solid #22c55e; opacity: 0.88; }
    .handover-table tbody tr.done-row td { color: #6b7280; }
    .badge-pill {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 5px 10px; border-radius: 999px; font-size: 0.73rem; font-weight: 600;
    }
    .badge-pending { background: #FFF8EE; color: #f59e0b; }
    .badge-done { background: #EDFAF4; color: #16a34a; }
    .badge-info { background: #EFF3FF; color: #3b6cf8; }
    .btn-continue {
        display: inline-block;
        background: #3b6cf8; color: #fff; border: none; border-radius: 9px;
        padding: 8px 12px; font-size: 0.78rem; font-weight: 600;
        cursor: pointer; box-shadow: 0 2px 8px rgba(59,108,248,.2); text-decoration: none;
    }
    .btn-continue:hover { background: #2554e0; }
    .empty-state {
        text-align: center; padding: 48px 20px; color: #999; font-size: 0.88rem;
    }
</style>

<div class="handover-body">
    <div class="breadcrumb-row">
        <a href="{{ route('dashboard') }}">Home</a>
        <span class="sep">&rsaquo;</span>
        <span class="current">Daily Handover</span>
    </div>

    <div class="title-row">
        <div class="title-row-left">
            <h2>Daily Handover</h2>
            <p>
                @if($date->isToday())
                    Today's updates for the next support engineer to continue unfinished work
                @else
                    Updates from {{ $date->format('D, j M Y') }} — review what was handed over
                @endif
            </p>
        </div>
        <div class="title-row-right">
            {{-- Day navigation: lets this page actually act as a per-day view, not just "today" --}}
            <form method="GET" action="{{ route('handover.show') }}" class="date-nav" id="dateForm">
                <a href="{{ route('handover.show', ['date' => $date->copy()->subDay()->toDateString()]) }}" aria-label="Previous day">&larr;</a>
                <input type="date" name="date" value="{{ $date->toDateString() }}"
                       max="{{ now()->toDateString() }}"
                       onchange="document.getElementById('dateForm').submit()">
                <a href="{{ route('handover.show', ['date' => $date->copy()->addDay()->toDateString()]) }}"
                   aria-label="Next day"
                   @if($date->isToday()) style="pointer-events:none; opacity:.3;" @endif>&rarr;</a>
            </form>
            <button type="button" class="btn-export" id="handoverExport">&#8659; Export</button>
        </div>
    </div>

    <div class="stats-strip">
        <div class="strip-card">
            <div class="strip-icon blue">&#128203;</div>
            <div>
                <div class="strip-num">{{ $stats['total'] }}</div>
                <div class="strip-label">Updated {{ $date->isToday() ? 'Today' : 'This Day' }}</div>
            </div>
        </div>
        <div class="strip-card">
            <div class="strip-icon green">&#9989;</div>
            <div>
                <div class="strip-num">{{ $stats['completed'] }}</div>
                <div class="strip-label">Completed</div>
            </div>
        </div>
        <div class="strip-card">
            <div class="strip-icon amber">&#8987;</div>
            <div>
                <div class="strip-num">{{ $stats['pending'] }}</div>
                <div class="strip-label">Pending</div>
            </div>
        </div>
    </div>

    <div class="toolbar">
        <div class="search-box">&#128269; <input type="text" id="handoverSearch" placeholder="Search handover entries..." /></div>
        <div class="filter-pills">
            <button class="pill active-all" data-filter="all" type="button">All</button>
            <button class="pill" data-filter="Done" type="button">Done</button>
            <button class="pill" data-filter="Pending" type="button">Pending</button>
        </div>
    </div>

    <div class="table-card">
        @if($handovers->isEmpty())
            <div class="empty-state">No activity updates recorded for this day yet.</div>
        @else
            {{-- Date group header — makes it clear which day this handover belongs to --}}
            <div style="padding: 14px 20px 10px; border-bottom: 1px solid #f0f0f5; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <span style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #aaa;">Handover Date</span>
                    <div style="font-size: 1rem; font-weight: 800; color: #1a1a2e; margin-top: 2px;">
                        {{ $date->format('l, j F Y') }}
                        @if($date->isToday())
                            <span style="margin-left: 8px; font-size: 0.72rem; font-weight: 600; background: #3b6cf8; color: #fff; padding: 2px 10px; border-radius: 20px; vertical-align: middle;">Today</span>
                        @endif
                    </div>
                </div>
                <div style="font-size: 0.8rem; color: #aaa;">
                    {{ $stats['total'] }} {{ Str::plural('activity', $stats['total']) }} updated
                    &nbsp;·&nbsp;
                    <span style="color: #22c55e; font-weight: 600;">{{ $stats['completed'] }} done</span>
                    &nbsp;·&nbsp;
                    <span style="color: #f59e0b; font-weight: 600;">{{ $stats['pending'] }} pending</span>
                </div>
            </div>

            <table class="handover-table">
                <thead>
                    <tr>
                        <th>Activity Name</th>
                        <th>Current Status</th>
                        <th>Latest Remark</th>
                        <th>Updated By</th>
                        <th>Bio Details</th>
                        <th>Update Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($handovers as $item)
                        <tr
                            class="{{ $item->status === 'Pending' ? 'pending-row' : 'done-row' }}"
                            data-status="{{ $item->status }}"
                            data-activity="{{ $item->activity_name }}"
                            data-remark="{{ $item->remark }}"
                            data-updated="{{ $item->updated_by }}"
                            data-time="{{ $item->updated_at }}"
                        >
                            <td>
                                <div style="font-weight: 600; color: #1a1a2e; font-size: .85rem;">{{ $item->activity_name }}</div>
                            </td>
                            <td>
                                @if($item->status === 'Pending')
                                    <span class="badge-pill badge-pending">&#8987; Pending</span>
                                @else
                                    <span class="badge-pill badge-done">&#9989; Done</span>
                                @endif
                            </td>
                            <td style="max-width: 220px; word-break: break-word;">{{ $item->remark }}</td>
                            <td><span style="font-weight: 600; color: #1a1a2e;">{{ $item->updated_by }}</span></td>
                            <td>
                                <div style="font-size:.75rem;color:#777;line-height:1.8;">
                                    @if($item->person_staff_id)<div>ID: <strong>{{ $item->person_staff_id }}</strong></div>@endif
                                    @if($item->person_department)<div>{{ $item->person_department }}</div>@endif
                                    @if($item->person_shift)<div>{{ $item->person_shift }}</div>@endif
                                    @if($item->person_phone)<div>{{ $item->person_phone }}</div>@endif
                                    @if(!$item->person_staff_id && !$item->person_department)
                                        <span style="color:#ccc;">—</span>
                                    @endif
                                </div>
                            </td>
                            <td><strong style="color: #1a1a2e;">{{ $item->updated_at }}</strong></td>
                            <td>
                                @if($item->status === 'Pending')
                                    <a href="{{ route('activities.update', ['id' => $item->activity_id]) }}" class="btn-continue">Continue Work</a>
                                @else
                                    <span style="color: #22c55e; font-size: .78rem; font-weight: 600;">Completed</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

<script>
    const searchInput = document.getElementById('handoverSearch');
    const filterButtons = document.querySelectorAll('.pill');
    const rows = document.querySelectorAll('tbody tr[data-status]');

    function applyHandoverFilter(filter) {
        const term = searchInput ? searchInput.value.trim().toLowerCase() : '';

        rows.forEach(row => {
            const rowText = `${row.dataset.activity} ${row.dataset.remark} ${row.dataset.updated} ${row.dataset.time} ${row.dataset.status}`.toLowerCase();
            const matchesFilter = filter === 'all' || row.dataset.status === filter;
            const matchesSearch = !term || rowText.includes(term);

            row.style.display = matchesFilter && matchesSearch ? '' : 'none';
        });

        filterButtons.forEach(btn => {
            btn.classList.remove('active-all', 'active-done', 'active-pending');
            if (btn.dataset.filter === filter) {
                if (filter === 'Done') btn.classList.add('active-done');
                else if (filter === 'Pending') btn.classList.add('active-pending');
                else btn.classList.add('active-all');
            }
        });
    }

    filterButtons.forEach(btn => {
        btn.addEventListener('click', () => applyHandoverFilter(btn.dataset.filter));
    });

    if (searchInput) {
        searchInput.addEventListener('input', () => {
            const active = document.querySelector('.pill.active-all, .pill.active-done, .pill.active-pending');
            const currentFilter = active ? active.dataset.filter : 'all';
            applyHandoverFilter(currentFilter);
        });
    }

    // Client-side export of visible rows
    function exportHandoverCSV(filename = 'handover.csv'){
        const rows = Array.from(document.querySelectorAll('.handover-table tbody tr'))
            .filter(r => r.style.display !== 'none');
        const headers = ['Activity','Status','Remark','Updated By','Update Time'];
        const csv = [headers.join(',')];
        rows.forEach(r => {
            const cols = Array.from(r.querySelectorAll('td')).map(td => '"' + td.textContent.replace(/"/g,'""') + '"');
            csv.push(cols.join(','));
        });
        const blob = new Blob([csv.join('\n')], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a'); a.href = url; a.download = filename; document.body.appendChild(a); a.click(); a.remove(); URL.revokeObjectURL(url);
    }

    document.getElementById('handoverExport')?.addEventListener('click', function(){
        exportHandoverCSV(`handover-${{ $date->toDateString() }}.csv`);
    });
</script>
@endsection