@extends('layouts.app')
@section('title', 'Daily Handover')
@section('content')
@php
    if (!isset($date) || !$date) { $date = \Carbon\Carbon::now(); }
    elseif (!($date instanceof \Carbon\Carbon)) {
        try { $date = \Carbon\Carbon::parse($date); } catch (Exception $e) { $date = \Carbon\Carbon::now(); }
    }
    if (!isset($stats) || !is_array($stats)) {
        $stats = ['total' => 0, 'completed' => 0, 'pending' => 0];
    }
    if (!isset($handovers) || !is_iterable($handovers)) { $handovers = collect([]); }
    if (!isset($shiftName)) { $shiftName = 'Current Shift'; }
    if (!isset($handoverNote)) { $handoverNote = null; }
    $pendingItems   = $handovers->where('status', 'Pending');
    $completedItems = $handovers->where('status', 'Done');
@endphp
<style>
*, *::before, *::after { box-sizing: border-box; }
.handover-body { width: 100%; padding: 28px; }

/* breadcrumb */
.breadcrumb-row { display:flex; align-items:center; gap:6px; font-size:.78rem; color:#aaa; margin-bottom:20px; }
.breadcrumb-row a { color:#3b6cf8; text-decoration:none; }
.breadcrumb-row .sep { color:#ccc; }

/* title row */
.title-row { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:20px; gap:16px; flex-wrap:wrap; }
.title-row-left h2 { font-size:1.4rem; font-weight:800; color:#1a1a2e; margin:0 0 4px; }
.title-row-left p { font-size:.82rem; color:#999; margin:0; }
.title-row-right { display:flex; align-items:center; gap:10px; }

/* date nav */
.date-nav { display:flex; align-items:center; gap:6px; background:#fff; border:1px solid #e8eaf0; border-radius:10px; padding:4px 6px; box-shadow:0 1px 3px rgba(0,0,0,.04); }
.date-nav a { display:flex; align-items:center; justify-content:center; width:30px; height:30px; border-radius:8px; color:#555; text-decoration:none; font-size:.9rem; }
.date-nav a:hover { background:#f3f4f8; }
.date-nav input[type="date"] { border:none; outline:none; font-size:.82rem; color:#333; font-weight:600; padding:4px 6px; }
.btn-export { display:inline-flex; align-items:center; gap:8px; background:#3b6cf8; color:#fff; border:none; border-radius:10px; padding:10px 18px; font-size:.85rem; font-weight:600; cursor:pointer; box-shadow:0 2px 8px rgba(59,108,248,.25); }

/* stats strip */
.stats-strip { display:flex; gap:16px; margin-bottom:24px; flex-wrap:wrap; }
.strip-card { flex:1; min-width:160px; background:#fff; border-radius:12px; padding:16px 20px; box-shadow:0 1px 4px rgba(0,0,0,.06); display:flex; align-items:center; gap:14px; }
.strip-icon { width:42px; height:42px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.1rem; flex-shrink:0; }
.strip-icon.blue { background:#EFF3FF; } .strip-icon.green { background:#EDFAF4; } .strip-icon.amber { background:#FFF8EE; }
.strip-num { font-size:1.4rem; font-weight:800; color:#1a1a2e; line-height:1; }
.strip-label { font-size:.74rem; color:#999; margin-top:3px; }

/* toolbar */
.toolbar { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; gap:12px; flex-wrap:wrap; }
.search-box { display:flex; align-items:center; gap:8px; background:#fff; border:1px solid #e8eaf0; border-radius:10px; padding:8px 14px; font-size:.83rem; color:#555; box-shadow:0 1px 3px rgba(0,0,0,.04); }
.search-box input { border:none; outline:none; background:transparent; font-size:.83rem; color:#333; width:220px; }
.filter-pills { display:flex; gap:8px; }
.pill { padding:6px 14px; border-radius:20px; font-size:.78rem; font-weight:600; cursor:pointer; border:1.5px solid #e8eaf0; background:#fff; color:#888; transition:all .15s; }
.pill.active-all { background:#1a1a2e; color:#fff; border-color:#1a1a2e; }
.pill.active-done { background:#EDFAF4; color:#16a34a; border-color:#bbf7d0; }
.pill.active-pending { background:#FFF8EE; color:#f59e0b; border-color:#fde68a; }

/* table card */
.table-card { background:#fff; border-radius:14px; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; margin-bottom:20px; }

/* ISSUE 3 — handover report header bar */
.handover-report-bar {
    display:flex; align-items:center; justify-content:space-between;
    padding:16px 22px; background:#fff;
    border-bottom:1px solid #f0f0f5; flex-wrap:wrap; gap:10px;
}
.handover-report-bar .bar-left .bar-label { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:#aaa; }
.handover-report-bar .bar-left .bar-title { font-size:1.05rem; font-weight:800; color:#1a1a2e; margin-top:2px; }
.handover-report-bar .bar-left .bar-title .today-badge {
    margin-left:8px; font-size:.7rem; font-weight:600;
    background:#3b6cf8; color:#fff; padding:2px 10px; border-radius:20px; vertical-align:middle;
}
.shift-badge {
    display:inline-flex; align-items:center; gap:6px;
    background:#f4f6f9; border:1px solid #e2e6ef; border-radius:20px;
    padding:6px 14px; font-size:.8rem; font-weight:700; color:#374151;
}
.shift-badge .shift-dot { width:8px; height:8px; border-radius:50%; background:#3b6cf8; }

/* ISSUE 4 — section headers for pending / done groups */
.section-header {
    display:flex; align-items:center; gap:10px;
    padding:10px 22px; font-size:.78rem; font-weight:700;
    text-transform:uppercase; letter-spacing:.06em;
}
.section-header.pending-header { background:#fff8ed; color:#b45309; border-top:1px solid #fde68a; border-bottom:1px solid #fde68a; }
.section-header.done-header    { background:#f0fdf4; color:#15803d; border-top:1px solid #bbf7d0; border-bottom:1px solid #bbf7d0; }
.section-header .sh-count {
    margin-left:auto; font-size:.72rem; font-weight:700;
    padding:2px 10px; border-radius:20px;
}
.pending-header .sh-count { background:#fde68a; color:#92400e; }
.done-header    .sh-count { background:#bbf7d0; color:#166534; }

/* table */
table.handover-table { width:100%; border-collapse:collapse; }
.handover-table thead tr { border-bottom:1px solid #f0f0f5; }
.handover-table thead th { padding:12px 18px; font-size:.72rem; font-weight:700; color:#aaa; text-transform:uppercase; letter-spacing:.05em; background:#fafbfc; text-align:left; }
.handover-table tbody tr { border-bottom:1px solid #f8f8fb; transition:background .12s; }
.handover-table tbody tr:last-child { border-bottom:none; }
.handover-table tbody td { padding:13px 18px; font-size:.83rem; color:#444; vertical-align:middle; }
.handover-table tbody tr.pending-row { background:#fffbf0; border-left:3px solid #f59e0b; }
.handover-table tbody tr.done-row    { background:#fafffe; border-left:3px solid #22c55e; }
.handover-table tbody tr.done-row td { color:#6b7280; }
.handover-table tbody tr:hover { filter:brightness(.97); }

/* badges */
.badge-pill { display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:999px; font-size:.72rem; font-weight:600; }
.badge-pending { background:#FFF8EE; color:#f59e0b; }
.badge-done    { background:#EDFAF4; color:#16a34a; }

/* ISSUE 1 — personnel bio tooltip */
.bio-wrap { display:inline-flex; align-items:center; gap:6px; }
.bio-name { font-weight:700; color:#1a1a2e; font-size:.84rem; }
.done-row .bio-name { color:#6b7280; }
.bio-info-btn {
    position:relative; display:inline-flex; align-items:center; justify-content:center;
    width:18px; height:18px; border-radius:50%;
    background:#e8eaf0; color:#888; font-size:.68rem; font-weight:700;
    cursor:default; flex-shrink:0; user-select:none;
}
.bio-info-btn .bio-tooltip {
    display:none; position:absolute; bottom:calc(100% + 7px); left:50%;
    transform:translateX(-50%); z-index:200;
    background:#1a2540; color:#e2e8f0; font-size:.74rem; font-weight:400;
    border-radius:10px; padding:10px 14px; min-width:190px;
    box-shadow:0 8px 24px rgba(0,0,0,.22); white-space:nowrap; line-height:1.9;
    pointer-events:none;
}
.bio-info-btn .bio-tooltip::after {
    content:''; position:absolute; top:100%; left:50%; transform:translateX(-50%);
    border:6px solid transparent; border-top-color:#1a2540;
}
.bio-info-btn:hover .bio-tooltip { display:block; }
.bio-tooltip .bt-row { display:flex; align-items:baseline; gap:8px; }
.bio-tooltip .bt-label { color:#7a8ab0; font-size:.68rem; width:68px; flex-shrink:0; }
.bio-tooltip .bt-val   { color:#e2e8f0; font-weight:600; }

/* ISSUE 2 — continue work pill button */
.btn-continue-pill {
    display:inline-flex; align-items:center; gap:5px;
    border:1.5px solid #d1d5db; border-radius:20px;
    padding:5px 12px; font-size:.76rem; font-weight:600; color:#6b7280;
    text-decoration:none; background:transparent;
    transition:border-color .15s, color .15s;
}
.btn-continue-pill:hover { border-color:#3b6cf8; color:#3b6cf8; }
.btn-continue-pill .pill-arrow { font-size:.7rem; }

/* empty state */
.empty-state { text-align:center; padding:48px 20px; color:#aaa; font-size:.88rem; }

/* ISSUE 5 — handover note card */
.note-card {
    background:#fff; border-radius:14px;
    box-shadow:0 1px 4px rgba(0,0,0,.06); padding:24px 26px; margin-top:24px;
}
.note-card h3 { font-size:1rem; font-weight:800; color:#1a1a2e; margin:0 0 4px; }
.note-card .note-sub { font-size:.8rem; color:#aaa; margin:0 0 18px; }
.note-card textarea {
    width:100%; min-height:110px; padding:12px 14px;
    border:1.5px solid #e8eaf0; border-radius:10px;
    font-size:.85rem; color:#1a1a2e; font-family:inherit;
    outline:none; resize:vertical; transition:border-color .15s;
}
.note-card textarea:focus { border-color:#3b6cf8; box-shadow:0 0 0 3px rgba(59,108,248,.08); }
.note-card .note-actions { display:flex; align-items:center; gap:12px; margin-top:12px; }
.btn-save-note {
    background:#3b6cf8; color:#fff; border:none; border-radius:9px;
    padding:10px 22px; font-size:.84rem; font-weight:700; cursor:pointer;
    box-shadow:0 2px 8px rgba(59,108,248,.22);
}
.btn-save-note:hover { background:#2554e0; }
.saved-note-display {
    margin-top:20px; padding:16px 18px;
    background:#f8f9fb; border-radius:10px; border:1px solid #eef0f5;
}
.saved-note-display .sn-meta { font-size:.74rem; color:#aaa; margin-bottom:6px; }
.saved-note-display .sn-meta strong { color:#555; }
.saved-note-display .sn-text { font-size:.85rem; color:#374151; line-height:1.7; white-space:pre-wrap; }
.note-saved-flash {
    background:#EDFAF4; border:1px solid #bbf7d0; color:#15803d;
    border-radius:9px; padding:10px 16px; font-size:.83rem; font-weight:600;
    margin-bottom:14px;
}

@media (max-width: 900px) {
    .stats-strip { flex-wrap:wrap; }
    .strip-card  { flex:1 1 calc(50% - 8px); }
    .handover-body { padding:16px; }
}
@media (max-width: 600px) {
    .strip-card { flex:1 1 100%; }
    .toolbar    { flex-direction:column; align-items:flex-start; }
    .handover-report-bar { flex-direction:column; align-items:flex-start; }
}
</style>
<div class="handover-body">
<div class="breadcrumb-row">
    <a href="{{ route('dashboard') }}">Home</a>
    <span class="sep">&rsaquo;</span>
    <span style="color:#555;font-weight:500;">Daily Handover</span>
</div>

<div class="title-row">
    <div class="title-row-left">
        <h2>Daily Handover</h2>
        <p>
            @if($date->isToday())
                Today&rsquo;s updates &mdash; help the next shift know what&rsquo;s done and what needs attention
            @else
                Handover from {{ $date->format('D, j M Y') }} &mdash; review what was handed over
            @endif
        </p>
    </div>
    <div class="title-row-right">
        <form method="GET" action="{{ route('handover.show') }}" class="date-nav" id="dateForm">
            <a href="{{ route('handover.show', ['date' => $date->copy()->subDay()->toDateString()]) }}" aria-label="Previous day">&larr;</a>
            <input type="date" name="date" value="{{ $date->toDateString() }}"
                   max="{{ now()->toDateString() }}"
                   onchange="document.getElementById('dateForm').submit()">
            <a href="{{ route('handover.show', ['date' => $date->copy()->addDay()->toDateString()]) }}"
               aria-label="Next day"
               @if($date->isToday()) style="pointer-events:none;opacity:.3;" @endif>&rarr;</a>
        </form>
        <button type="button" class="btn-export" id="handoverExport">&#8659; Export</button>
    </div>
</div>

<div class="stats-strip">
    <div class="strip-card">
        <div class="strip-icon blue">&#128203;</div>
        <div>
            <div class="strip-num">{{ $stats['total'] }}</div>
            <div class="strip-label">{{ $date->isToday() ? 'Updated Today' : 'Updated This Day' }}</div>
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
    <div class="search-box">
        &#128269; <input type="text" id="handoverSearch" placeholder="Search activities, remarks, personnel..." />
    </div>
    <div class="filter-pills">
        <button class="pill active-all" data-filter="all" type="button">All</button>
        <button class="pill" data-filter="Pending" type="button">Pending</button>
        <button class="pill" data-filter="Done" type="button">Done</button>
    </div>
</div>

<div class="table-card" id="mainTableCard">
@if($handovers->isEmpty())
    <div class="empty-state">No activity updates recorded for this day yet.</div>
@else

    {{-- ISSUE 3: Handover report header bar with date + shift badge --}}
    <div class="handover-report-bar">
        <div class="bar-left">
            <div class="bar-label">Handover Report</div>
            <div class="bar-title">
                {{ $date->format('l, j F Y') }}
                @if($date->isToday())
                    <span class="today-badge">Today</span>
                @endif
            </div>
        </div>
        <div>
            <span class="shift-badge">
                <span class="shift-dot"></span>
                {{ $shiftName }}
            </span>
        </div>
    </div>

    {{-- ISSUE 4: PENDING section first --}}
    @if($pendingItems->isNotEmpty())
    <div class="section-header pending-header" data-section="Pending">
        &#9888; Pending &mdash; Requires Handover
        <span class="sh-count">{{ $pendingItems->count() }} {{ \Illuminate\Support\Str::plural('item', $pendingItems->count()) }}</span>
    </div>
    <table class="handover-table">
        <thead>
            <tr>
                <th style="width:22%">Activity Name</th>
                <th style="width:10%">Status</th>
                <th style="width:26%">Latest Remark</th>
                <th style="width:16%">Updated By</th>
                <th style="width:10%">Time</th>
                <th style="width:16%">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($pendingItems as $item)
            <tr class="pending-row"
                data-status="{{ $item->status }}"
                data-activity="{{ $item->activity_name }}"
                data-remark="{{ $item->remark }}"
                data-updated="{{ $item->updated_by }}">
                <td>
                    <div style="font-weight:700;color:#1a1a2e;font-size:.85rem;line-height:1.4;">{{ $item->activity_name }}</div>
                </td>
                <td>
                    <span class="badge-pill badge-pending">&#8987; Pending</span>
                </td>
                <td style="max-width:220px;word-break:break-word;font-size:.82rem;">{{ $item->remark }}</td>
                {{-- ISSUE 1: name + tooltip, no stacked bio in cell --}}
                <td>
                    <div class="bio-wrap">
                        <span class="bio-name">{{ $item->updated_by }}</span>
                        <span class="bio-info-btn" aria-label="Personnel details">
                            i
                            <span class="bio-tooltip">
                                @if($item->person_staff_id)
                                <div class="bt-row"><span class="bt-label">Staff ID</span><span class="bt-val">{{ $item->person_staff_id }}</span></div>
                                @endif
                                @if($item->person_department)
                                <div class="bt-row"><span class="bt-label">Department</span><span class="bt-val">{{ $item->person_department }}</span></div>
                                @endif
                                @if($item->person_shift)
                                <div class="bt-row"><span class="bt-label">Shift</span><span class="bt-val">{{ $item->person_shift }}</span></div>
                                @endif
                                @if($item->person_phone)
                                <div class="bt-row"><span class="bt-label">Phone</span><span class="bt-val">{{ $item->person_phone }}</span></div>
                                @endif
                            </span>
                        </span>
                    </div>
                </td>
                <td><strong style="color:#1a1a2e;">{{ $item->updated_at }}</strong></td>
                {{-- ISSUE 2: subtle pill button --}}
                <td>
                    <a href="{{ route('activities.update', ['id' => $item->activity_id]) }}" class="btn-continue-pill">
                        <span class="pill-arrow">&#9654;</span> Continue Work
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @endif

    {{-- ISSUE 4: COMPLETED section second --}}
    @if($completedItems->isNotEmpty())
    <div class="section-header done-header" data-section="Done">
        &#10003; Completed
        <span class="sh-count">{{ $completedItems->count() }} {{ \Illuminate\Support\Str::plural('item', $completedItems->count()) }}</span>
    </div>
    <table class="handover-table">
        <thead>
            <tr>
                <th style="width:22%">Activity Name</th>
                <th style="width:10%">Status</th>
                <th style="width:26%">Latest Remark</th>
                <th style="width:16%">Updated By</th>
                <th style="width:10%">Time</th>
                <th style="width:16%">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($completedItems as $item)
            <tr class="done-row"
                data-status="{{ $item->status }}"
                data-activity="{{ $item->activity_name }}"
                data-remark="{{ $item->remark }}"
                data-updated="{{ $item->updated_by }}">
                <td>
                    <div style="font-weight:600;font-size:.85rem;line-height:1.4;">{{ $item->activity_name }}</div>
                </td>
                <td>
                    <span class="badge-pill badge-done">&#9989; Done</span>
                </td>
                <td style="max-width:220px;word-break:break-word;font-size:.82rem;">{{ $item->remark }}</td>
                <td>
                    <div class="bio-wrap">
                        <span class="bio-name">{{ $item->updated_by }}</span>
                        <span class="bio-info-btn" aria-label="Personnel details">
                            i
                            <span class="bio-tooltip">
                                @if($item->person_staff_id)
                                <div class="bt-row"><span class="bt-label">Staff ID</span><span class="bt-val">{{ $item->person_staff_id }}</span></div>
                                @endif
                                @if($item->person_department)
                                <div class="bt-row"><span class="bt-label">Department</span><span class="bt-val">{{ $item->person_department }}</span></div>
                                @endif
                                @if($item->person_shift)
                                <div class="bt-row"><span class="bt-label">Shift</span><span class="bt-val">{{ $item->person_shift }}</span></div>
                                @endif
                                @if($item->person_phone)
                                <div class="bt-row"><span class="bt-label">Phone</span><span class="bt-val">{{ $item->person_phone }}</span></div>
                                @endif
                            </span>
                        </span>
                    </div>
                </td>
                <td><strong>{{ $item->updated_at }}</strong></td>
                <td>
                    <span style="color:#22c55e;font-size:.78rem;font-weight:600;">&#10003; Completed</span>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @endif

@endif
</div>

{{-- ISSUE 5: Shift Handover Note section --}}
<div class="note-card">
    <h3>Shift Handover Note</h3>
    <p class="note-sub">Leave a general note for the incoming shift lead about anything not captured in the activity updates above.</p>

    @if(session('note_saved'))
        <div class="note-saved-flash">{{ session('note_saved') }}</div>
    @endif

    <form method="POST" action="{{ route('handover.saveNote') }}">
        @csrf
        <input type="hidden" name="note_date" value="{{ $date->toDateString() }}">
        <textarea name="note" placeholder="e.g. Server monitoring alert cleared at 3pm. Escalate ticket #4821 if not resolved by next shift. Handover completed by Francis Ngumah."
        >{{ old('note', $handoverNote?->note) }}</textarea>
        @error('note')
            <div style="color:#ef4444;font-size:.78rem;margin-top:5px;">{{ $message }}</div>
        @enderror
        <div class="note-actions">
            <button type="submit" class="btn-save-note">Save Note</button>
            @if($handoverNote)
                <span style="font-size:.78rem;color:#aaa;">Last saved by <strong style="color:#555;">{{ $handoverNote->createdBy?->name ?? 'Unknown' }}</strong> &mdash; {{ $handoverNote->updated_at->format('j M Y, h:i A') }}</span>
            @endif
        </div>
    </form>

    @if($handoverNote)
    <div class="saved-note-display">
        <div class="sn-meta">
            Saved handover note &mdash;
            <strong>{{ $handoverNote->createdBy?->name ?? 'Unknown' }}</strong>
            &middot; {{ $handoverNote->note_date->format('l, j F Y') }}
        </div>
        <div class="sn-text">{{ $handoverNote->note }}</div>
    </div>
    @endif
</div>

</div>{{-- end handover-body --}}

<script>
(function(){
    const searchInput   = document.getElementById('handoverSearch');
    const filterButtons = document.querySelectorAll('.pill');
    const allRows       = document.querySelectorAll('tbody tr[data-status]');
    const sectionHeaders = document.querySelectorAll('.section-header');

    function applyFilter(filter) {
        const term = searchInput ? searchInput.value.trim().toLowerCase() : '';

        allRows.forEach(row => {
            const text = (
                (row.dataset.activity || '') + ' ' +
                (row.dataset.remark   || '') + ' ' +
                (row.dataset.updated  || '') + ' ' +
                (row.dataset.status   || '')
            ).toLowerCase();
            const matchFilter = filter === 'all' || row.dataset.status === filter;
            const matchSearch = !term || text.includes(term);
            row.style.display = matchFilter && matchSearch ? '' : 'none';
        });

        // Hide section headers when all their rows are hidden
        sectionHeaders.forEach(hdr => {
            const section = hdr.dataset.section;
            const rows    = Array.from(document.querySelectorAll(`tbody tr[data-status="${section}"]`));
            const anyVisible = rows.some(r => r.style.display !== 'none');
            hdr.style.display = anyVisible ? '' : 'none';
            // Also hide the table that follows
            const nextTable = hdr.nextElementSibling;
            if (nextTable && nextTable.tagName === 'TABLE') {
                nextTable.style.display = anyVisible ? '' : 'none';
            }
        });

        filterButtons.forEach(btn => {
            btn.classList.remove('active-all','active-done','active-pending');
            if (btn.dataset.filter === filter) {
                if (filter === 'Done')    btn.classList.add('active-done');
                else if (filter === 'Pending') btn.classList.add('active-pending');
                else btn.classList.add('active-all');
            }
        });
    }

    filterButtons.forEach(btn => btn.addEventListener('click', () => applyFilter(btn.dataset.filter)));

    if (searchInput) {
        searchInput.addEventListener('input', () => {
            const active = document.querySelector('.pill.active-all,.pill.active-done,.pill.active-pending');
            applyFilter(active ? active.dataset.filter : 'all');
        });
    }

    // CSV export
    document.getElementById('handoverExport')?.addEventListener('click', function(){
        const rows = Array.from(document.querySelectorAll('tbody tr[data-status]'))
            .filter(r => r.style.display !== 'none');
        const headers = ['Activity','Status','Latest Remark','Updated By','Update Time'];
        const csv = [headers.join(',')];
        rows.forEach(r => {
            const cells = r.querySelectorAll('td');
            const cols  = Array.from(cells).map(td => '"' + td.textContent.trim().replace(/"/g,'""') + '"');
            csv.push(cols.join(','));
        });
        const blob = new Blob([csv.join('\n')], {type:'text/csv'});
        const url  = URL.createObjectURL(blob);
        const a    = document.createElement('a');
        a.href = url;
        a.download = 'handover-{{ $date->toDateString() }}.csv';
        document.body.appendChild(a); a.click(); a.remove();
        URL.revokeObjectURL(url);
    });
})();
</script>
@endsection
