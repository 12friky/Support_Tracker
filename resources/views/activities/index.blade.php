@extends('layouts.app')

@section('title', 'Activities')

@section('content')
<style>
    /* ── Base ── */
    *, *::before, *::after { box-sizing: border-box; }

    .page-header {
        display: flex; align-items: center; justify-content: space-between;
        background: #fff; padding: 0 28px; height: 64px;
        border-bottom: 1px solid #e8eaf0; position: sticky; top: 0; z-index: 100;
    }
    .page-header-left { display: flex; align-items: center; gap: 16px; }
    .page-header-left .hamburger {
        background: none; border: none; cursor: pointer;
        display: flex; flex-direction: column; gap: 5px; padding: 4px;
    }
    .page-header-left .hamburger span { display: block; width: 22px; height: 2px; background: #555; border-radius: 2px; }
    .page-header-left h1 { font-size: 1.35rem; font-weight: 700; color: #1a1a2e; margin: 0; }
    .page-header-right { display: flex; align-items: center; gap: 20px; }
    .notif-btn { position: relative; background: none; border: none; cursor: pointer; color: #555; font-size: 1.3rem; padding: 4px; }
    .notif-badge {
        position: absolute; top: 0; right: 0; background: #ef4444; color: #fff;
        font-size: 0.6rem; font-weight: 700; border-radius: 9999px;
        min-width: 16px; height: 16px; display: flex; align-items: center; justify-content: center; padding: 0 3px;
    }
    .user-chip { display: flex; align-items: center; gap: 10px; cursor: pointer; }
    .user-avatar {
        width: 38px; height: 38px; border-radius: 50%;
        background: #1e4db7; color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.85rem; font-weight: 700;
    }
    .user-info .user-name { font-size: 0.88rem; font-weight: 600; color: #1a1a2e; }
    .user-info .user-role { font-size: 0.75rem; color: #888; }

    /* ── Body ── */
    .act-body { padding: 28px; }

    .breadcrumb-row {
        display: flex; align-items: center; gap: 6px;
        font-size: 0.78rem; color: #aaa; margin-bottom: 20px;
    }
    .breadcrumb-row a { color: #3b6cf8; text-decoration: none; }
    .breadcrumb-row .sep { color: #ccc; }
    .breadcrumb-row .current { color: #555; font-weight: 500; }

    .title-row {
        display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;
    }
    .title-row-left h2 { font-size: 1.4rem; font-weight: 800; color: #1a1a2e; margin: 0 0 4px; }
    .title-row-left p  { font-size: 0.82rem; color: #999; margin: 0; }
    .title-actions {
        display: flex; align-items: center; gap: 10px;
    }

    .btn-add,
    .btn-update {
        display: inline-flex; align-items: center; gap: 8px;
        border: none; border-radius: 10px;
        padding: 10px 20px; font-size: 0.85rem; font-weight: 600;
        cursor: pointer; text-decoration: none;
        transition: background .15s, box-shadow .15s;
    }
    .btn-add {
        background: #3b6cf8; color: #fff;
        box-shadow: 0 2px 8px rgba(59,108,248,.25);
    }
    .btn-add:hover { background: #2554e0; color: #fff; box-shadow: 0 4px 14px rgba(59,108,248,.35); }
    .btn-update {
        background: #FFF8EE; color: #f59e0b;
        box-shadow: 0 2px 8px rgba(245,158,11,.18);
    }
    .btn-update:hover { background: #fef3c7; color: #d97706; }

    /* ── Stats strip ── */
    .stats-strip { display: flex; gap: 16px; margin-bottom: 24px; }
    .strip-card {
        flex: 1; background: #fff; border-radius: 12px; padding: 16px 20px;
        box-shadow: 0 1px 4px rgba(0,0,0,.06); display: flex; align-items: center; gap: 14px;
    }
    .strip-icon {
        width: 42px; height: 42px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; flex-shrink: 0;
    }
    .strip-icon.blue  { background: #EFF3FF; }
    .strip-icon.green { background: #EDFAF4; }
    .strip-icon.amber { background: #FFF8EE; }
    .strip-num   { font-size: 1.4rem; font-weight: 800; color: #1a1a2e; line-height: 1; }
    .strip-label { font-size: 0.74rem; color: #999; margin-top: 3px; }

    /* ── Toolbar ── */
    .toolbar {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 16px; gap: 12px; flex-wrap: wrap;
    }
    .search-box {
        display: flex; align-items: center; gap: 8px;
        background: #fff; border: 1px solid #e8eaf0; border-radius: 10px;
        padding: 8px 14px; font-size: 0.83rem; color: #555;
        box-shadow: 0 1px 3px rgba(0,0,0,.04);
    }
    .search-box input {
        border: none; outline: none; background: transparent;
        font-size: 0.83rem; color: #333; width: 200px;
    }
    .filter-pills { display: flex; gap: 8px; }
    .pill {
        padding: 6px 14px; border-radius: 20px; font-size: 0.78rem; font-weight: 600;
        cursor: pointer; border: 1.5px solid #e8eaf0; background: #fff; color: #888;
        transition: all .15s;
    }
    .pill.active-all     { background: #1a1a2e; color: #fff; border-color: #1a1a2e; }
    .pill.active-done    { background: #EDFAF4; color: #16a34a; border-color: #bbf7d0; }
    .pill.active-pending { background: #FFF8EE; color: #f59e0b; border-color: #fde68a; }

    /* ── Table card ── */
    .table-card { background: #fff; border-radius: 14px; box-shadow: 0 1px 4px rgba(0,0,0,.06); overflow: hidden; }
    table.act-table { width: 100%; border-collapse: collapse; }
    .act-table thead tr { border-bottom: 1px solid #f0f0f5; }
    .act-table thead th {
        padding: 14px 20px; font-size: 0.75rem; font-weight: 700;
        color: #aaa; text-transform: uppercase; letter-spacing: .05em;
        background: #fafbfc; text-align: left;
    }
    .act-table tbody tr { border-bottom: 1px solid #f8f8fb; transition: background .12s; }
    .act-table tbody tr:last-child { border-bottom: none; }
    .act-table tbody tr:hover { background: #f9faff; }
    .act-table tbody td { padding: 15px 20px; font-size: 0.84rem; color: #444; vertical-align: middle; }

    .act-name { display: flex; align-items: center; gap: 12px; }
    .act-avatar {
        width: 34px; height: 34px; border-radius: 9px;
        background: #EFF3FF; color: #3b6cf8;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem; flex-shrink: 0; font-weight: 700;
    }
    .act-name-text { font-weight: 600; color: #1a1a2e; font-size: 0.85rem; }
    .act-desc-text { font-size: 0.74rem; color: #aaa; margin-top: 2px; }

    .user-cell { display: flex; align-items: center; gap: 8px; }
    .user-dot {
        width: 28px; height: 28px; border-radius: 50%;
        background: #e8eaf0; color: #555;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.68rem; font-weight: 700; flex-shrink: 0;
    }

    .bdg {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 12px; border-radius: 20px; font-size: 0.73rem; font-weight: 600;
    }
    .bdg-done    { background: #EDFAF4; color: #16a34a; }
    .bdg-pending { background: #FFF8EE; color: #f59e0b; }
    .bdg-dot { width: 6px; height: 6px; border-radius: 50%; }
    .bdg-done .bdg-dot    { background: #22c55e; }
    .bdg-pending .bdg-dot { background: #f59e0b; }

    /* ── Action buttons ── */
    .action-btns { display: flex; align-items: center; gap: 8px; }
    .btn-icon {
        width: 32px; height: 32px; border-radius: 8px; border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.85rem; transition: background .15s, transform .1s;
    }
    .btn-icon:hover { transform: scale(1.08); }
    .btn-view   { background: #EFF3FF; color: #3b6cf8; }
    .btn-view:hover   { background: #dce7ff; }
    .btn-edit   { background: #FFF8EE; color: #f59e0b; }
    .btn-edit:hover   { background: #fef3c7; }
    .btn-delete { background: #FEF2F2; color: #ef4444; }
    .btn-delete:hover { background: #fee2e2; }

    /* Pagination */
    .pagination-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 20px; border-top: 1px solid #f0f0f5;
        font-size: 0.78rem; color: #aaa;
    }
    .pag-btns { display: flex; gap: 6px; }
    .pag-btn {
        width: 30px; height: 30px; border-radius: 7px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.78rem; font-weight: 600; cursor: pointer;
        border: 1.5px solid #e8eaf0; background: #fff; color: #555;
        text-decoration: none; transition: all .15s;
    }
    .pag-btn:hover { background: #EFF3FF; border-color: #c7d7ff; color: #3b6cf8; }
    .pag-btn.active { background: #3b6cf8; border-color: #3b6cf8; color: #fff; }

    /* ════════════════════════════════
       MODALS
    ════════════════════════════════ */
    .modal-overlay {
        position: fixed; inset: 0;
        background: rgba(15,23,42,.45); backdrop-filter: blur(3px);
        z-index: 500; align-items: center; justify-content: center;
        padding: 20px;
        display: flex;
        visibility: hidden;
        opacity: 0;
        pointer-events: none;
        transition: opacity .18s ease, visibility .18s ease;
    }
    .modal-overlay.open {
        visibility: visible;
        opacity: 1;
        pointer-events: auto;
    }

    .modal {
        display: block !important;
        position: relative;
        z-index: 1;
        background: #fff; border-radius: 18px;
        box-shadow: 0 20px 60px rgba(0,0,0,.18);
        width: 100%; max-width: 520px;
        max-height: calc(100vh - 40px);
        overflow-y: auto;
        animation: modalIn .22s ease;
    }
    @keyframes modalIn {
        from { opacity: 0; transform: translateY(16px) scale(.97); }
        to   { opacity: 1; transform: none; }
    }

    .modal-header {
        display: flex; align-items: center; gap: 14px;
        padding: 22px 26px 18px; border-bottom: 1px solid #f0f0f5;
    }
    .modal-icon {
        width: 42px; height: 42px; border-radius: 11px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.15rem; flex-shrink: 0;
    }
    .modal-icon.blue   { background: #EFF3FF; }
    .modal-icon.amber  { background: #FFF8EE; }
    .modal-icon.red    { background: #FEF2F2; }
    .modal-icon.green  { background: #EDFAF4; }
    .modal-title { flex: 1; }
    .modal-title h3 { font-size: 1rem; font-weight: 800; color: #1a1a2e; margin: 0 0 2px; }
    .modal-title p  { font-size: 0.75rem; color: #aaa; margin: 0; }
    .modal-close {
        width: 32px; height: 32px; border-radius: 8px; border: none; cursor: pointer;
        background: #f4f6f9; color: #888; font-size: 1.1rem;
        display: flex; align-items: center; justify-content: center;
        transition: background .15s;
    }
    .modal-close:hover { background: #e8eaf0; color: #333; }

    .modal-body { padding: 24px 26px; }

    /* Field groups inside modal */
    .field-group { margin-bottom: 18px; }
    .field-group:last-child { margin-bottom: 0; }
    .field-group label {
        display: block; font-size: 0.8rem; font-weight: 600;
        color: #374151; margin-bottom: 7px;
    }
    .field-group label .req { color: #ef4444; margin-left: 2px; }
    .field-group .hint { font-size: 0.71rem; color: #bbb; margin-top: 5px; }

    .f-input, .f-textarea, .f-select {
        width: 100%; padding: 10px 13px;
        border: 1.5px solid #e8eaf0; border-radius: 9px;
        font-size: 0.84rem; color: #1a1a2e; background: #fff;
        outline: none; font-family: inherit;
        transition: border-color .15s, box-shadow .15s;
    }
    .f-input:focus, .f-textarea:focus, .f-select:focus {
        border-color: #3b6cf8; box-shadow: 0 0 0 3px rgba(59,108,248,.1);
    }
    .f-input::placeholder, .f-textarea::placeholder { color: #c5c9d6; }
    .f-input.readonly { background: #f8f9fb; color: #999; cursor: not-allowed; border-color: #eee; }
    .f-textarea { resize: vertical; min-height: 90px; }

    .select-wrap { position: relative; }
    .select-wrap::after {
        content: '▾'; position: absolute; right: 13px; top: 50%;
        transform: translateY(-50%); color: #aaa; font-size: 0.82rem; pointer-events: none;
    }
    .select-wrap .f-select { padding-right: 34px; appearance: none; }
    .status-done    { border-color: #bbf7d0 !important; background: #EDFAF4 !important; color: #16a34a !important; }
    .status-pending { border-color: #fde68a !important; background: #FFF8EE !important; color: #f59e0b !important; }

    .modal-footer {
        display: flex; align-items: center; justify-content: flex-end; gap: 10px;
        padding: 16px 26px; border-top: 1px solid #f0f0f5; background: #fafbfc;
    }
    .btn-mcancel {
        padding: 9px 20px; border-radius: 9px; font-size: 0.83rem; font-weight: 600;
        border: 1.5px solid #e8eaf0; background: #fff; color: #555; cursor: pointer;
        transition: background .15s;
    }
    .btn-mcancel:hover { background: #f4f6f9; }
    .btn-mprimary {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 22px; border-radius: 9px; font-size: 0.83rem; font-weight: 700;
        background: #3b6cf8; color: #fff; border: none; cursor: pointer;
        box-shadow: 0 2px 8px rgba(59,108,248,.28); transition: background .15s;
    }
    .btn-mprimary:hover { background: #2554e0; }
    .btn-mdanger {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 22px; border-radius: 9px; font-size: 0.83rem; font-weight: 700;
        background: #ef4444; color: #fff; border: none; cursor: pointer;
        box-shadow: 0 2px 8px rgba(239,68,68,.25); transition: background .15s;
    }
    .btn-mdanger:hover { background: #dc2626; }

    /* ── View modal detail rows ── */
    .detail-row {
        display: flex; gap: 10px; padding: 10px 0;
        border-bottom: 1px solid #f5f5f8; font-size: 0.83rem;
    }
    .detail-row:last-child { border-bottom: none; padding-bottom: 0; }
    .detail-label { width: 130px; flex-shrink: 0; color: #aaa; font-weight: 600; font-size: 0.76rem; text-transform: uppercase; letter-spacing: .04em; padding-top: 2px; }
    .detail-value { color: #1a1a2e; font-weight: 500; }

    /* Delete modal */
    .delete-warning {
        background: #FEF2F2; border: 1.5px solid #fecaca;
        border-radius: 12px; padding: 16px 18px; margin-bottom: 18px;
        display: flex; align-items: flex-start; gap: 12px;
    }
    .delete-warning .warn-icon { font-size: 1.3rem; flex-shrink: 0; margin-top: 1px; }
    .delete-warning p { font-size: 0.82rem; color: #b91c1c; margin: 0; line-height: 1.5; }
    .delete-confirm-text { font-size: 0.83rem; color: #555; }
    .delete-confirm-text strong { color: #1a1a2e; }

    /* ── Responsive ── */
    @media (max-width: 900px) {
        .stats-strip { flex-wrap: wrap; }
        .strip-card  { flex: 1 1 calc(50% - 8px); }
        .act-body    { padding: 16px; }
    }
    @media (max-width: 600px) {
        .strip-card  { flex: 1 1 100%; }
        .toolbar     { flex-direction: column; align-items: flex-start; }
        .search-box input { width: 140px; }
        .modal { max-width: 100%; border-radius: 14px; }
    }
</style>

<!-- ══════════════ PAGE HEADER ══════════════ -->
<header class="page-header">
    <div class="page-header-left">
        <button class="hamburger"><span></span><span></span><span></span></button>
        <h1>Activities</h1>
    </div>
    <div class="page-header-right">
        <button class="notif-btn">🔔<span class="notif-badge">3</span></button>
        <div class="user-chip">
            <div class="user-avatar">FN</div>
            <div class="user-info">
                <div class="user-name">Francis Ngumah</div>
                <div class="user-role">Support Engineer</div>
            </div>
            <span style="color:#aaa;font-size:.8rem;">▾</span>
        </div>
    </div>
</header>

<!-- ══════════════ BODY ══════════════ -->
<div class="act-body">

    <div class="breadcrumb-row">
        <a href="{{ route('dashboard') }}">Home</a>
        <span class="sep">›</span>
        <span class="current">Activities</span>
    </div>

    @if(session('success'))
    <div style="background:#EDFAF4;color:#16a34a;padding:12px 18px;border-radius:10px;margin-bottom:18px;font-size:.85rem;font-weight:600;border:1px solid #bbf7d0;">
        ✅ {{ session('success') }}
    </div>
    @endif

    <div class="title-row">
        <div class="title-row-left">
            <h2>All Activities</h2>
            <p>Track, manage and update all support activities</p>
        </div>
        <div class="title-actions">
            <button class="btn-add" onclick="openModal('addModal')">＋ Add Activity</button>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-strip">
        <div class="strip-card">
            <div class="strip-icon blue">📋</div>
            <div><div id="totalCount" class="strip-num">{{ $total }}</div><div class="strip-label">Total Activities</div></div>
        </div>
        <div class="strip-card">
            <div class="strip-icon green">✅</div>
            <div><div id="completedCount" class="strip-num">{{ $completed }}</div><div class="strip-label">Completed</div></div>
        </div>
        <div class="strip-card">
            <div class="strip-icon amber">⏳</div>
            <div><div id="pendingCount" class="strip-num">{{ $pending }}</div><div class="strip-label">Pending</div></div>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="toolbar">
        <div class="search-box">
            🔍 <input type="text" placeholder="Search activities…" />
        </div>
        <div class="filter-pills">
            <button class="pill active-all" data-filter="all">All ({{ $total }})</button>
            <button class="pill" data-filter="Done">Done ({{ $completed }})</button>
            <button class="pill" data-filter="Pending">Pending ({{ $pending }})</button>
        </div>
    </div>

    <!-- Table -->
    <div class="table-card">
        <table class="act-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Activity Name</th>
                    <th>Status</th>
                    <th>Last Updated By</th>
                    <th>Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activities as $act)
                @php
                    $initials = strtoupper(substr($act->name, 0, 2));
                    $userName = $act->lastUpdatedBy->name ?? 'Unknown';
                    $userInitials = strtoupper(substr($userName, 0, 2));
                    $avatarBg = $act->status === 'Done' ? '#EDFAF4' : '#FFF8EE';
                    $avatarColor = $act->status === 'Done' ? '#16a34a' : '#f59e0b';
                @endphp
                <tr data-status="{{ $act->status }}" data-id="{{ $act->id }}" data-name="{{ $act->name }}">
                    <td style="color:#ccc;font-size:.78rem;">{{ str_pad($act->id, 2, '0', STR_PAD_LEFT) }}</td>
                    <td>
                        <div class="act-name">
                            <div class="act-avatar" style="background:{{ $avatarBg }};color:{{ $avatarColor }};">
                                {{ $initials }}
                            </div>
                            <div>
                                <div class="act-name-text">{{ $act->name }}</div>
                                <div class="act-desc-text">{{ $act->description ?: 'No description provided' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="bdg {{ $act->status === 'Done' ? 'bdg-done' : 'bdg-pending' }}">
                            <span class="bdg-dot"></span> {{ $act->status }}
                        </span>
                    </td>
                    <td>
                        <div class="user-cell">
                            <div class="user-dot">{{ $userInitials }}</div>
                            {{ $userName }}
                        </div>
                    </td>
                    <td style="color:#aaa;">{{ $act->updated_at ? $act->updated_at->format('h:i A') : '-' }}</td>
                    <td>
                        <div class="action-btns">
                            <button class="btn-icon btn-view" title="View"
                                onclick="openView({{ json_encode([
                                    'id' => $act->id,
                                    'name' => $act->name,
                                    'desc' => $act->description ?: 'No description provided',
                                    'status' => $act->status,
                                    'user' => $userName,
                                    'time' => $act->updated_at ? $act->updated_at->format('h:i A') : '-',
                                ]) }})">👁</button>
                            <button class="btn-icon btn-edit" title="Edit"
                                onclick="openEdit({{ json_encode([
                                    'id' => $act->id,
                                    'name' => $act->name,
                                    'desc' => $act->description ?: 'No description provided',
                                    'status' => $act->status,
                                    'user' => $userName,
                                    'time' => $act->updated_at ? $act->updated_at->format('h:i A') : '-',
                                ]) }})">✏️</button>
                            <button class="btn-icon btn-delete" title="Delete"
                                onclick="openDelete({{ $act->id }}, '{{ addslashes($act->name) }}')">🗑</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pagination-row">
            <span>Showing {{ $activities->count() }} of {{ $total }} activities</span>
            <div class="pag-btns">
                <a href="#" class="pag-btn">‹</a>
                <a href="#" class="pag-btn active">1</a>
                <a href="#" class="pag-btn">›</a>
            </div>
        </div>
    </div>
</div>


<!-- ══════════════════════════════════════
     MODAL 1 — ADD ACTIVITY
══════════════════════════════════════ -->
<div class="modal-overlay" id="addModal" onclick="overlayClose(event,'addModal')">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-icon blue">📋</div>
            <div class="modal-title">
                <h3>Add New Activity</h3>
                <p>Fill in the details to create a support activity</p>
            </div>
            <button class="modal-close" onclick="closeModal('addModal')">✕</button>
        </div>
        <form id="addActivityForm" action="{{ route('activities.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="field-group">
                    <label for="add_name">Activity Name <span class="req">*</span></label>
                    <input type="text" class="f-input" id="add_name" name="name"
                           placeholder="e.g. Daily SMS Count, Database Backup…" required>
                </div>
                <div class="field-group">
                    <label for="add_desc">Description</label>
                    <textarea class="f-textarea" id="add_desc" name="description"
                              placeholder="Briefly describe what this activity involves…"></textarea>
                    <div class="hint">Optional — helps teammates understand the scope.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-mcancel" onclick="closeModal('addModal')">Cancel</button>
                <button type="submit" class="btn-mprimary">✓ Save Activity</button>
            </div>
        </form>
    </div>
</div>


<!-- ══════════════════════════════════════
     MODAL 2 — VIEW ACTIVITY
══════════════════════════════════════ -->
<div class="modal-overlay" id="viewModal" onclick="overlayClose(event,'viewModal')">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-icon green">👁</div>
            <div class="modal-title">
                <h3>Activity Details</h3>
                <p>Read-only view of this activity</p>
            </div>
            <button class="modal-close" onclick="closeModal('viewModal')">✕</button>
        </div>
        <div class="modal-body">
            <div class="detail-row">
                <div class="detail-label">Activity</div>
                <div class="detail-value" id="v_name">—</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Description</div>
                <div class="detail-value" id="v_desc">—</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Status</div>
                <div class="detail-value" id="v_status">—</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Last Updated By</div>
                <div class="detail-value" id="v_user">—</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Time</div>
                <div class="detail-value" id="v_time">—</div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-mcancel" onclick="closeModal('viewModal')">Close</button>
        </div>
    </div>
</div>


<!-- ══════════════════════════════════════
     MODAL 3 — EDIT ACTIVITY
══════════════════════════════════════ -->
<div class="modal-overlay" id="editModal" onclick="overlayClose(event,'editModal')">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-icon amber">✏️</div>
            <div class="modal-title">
                <h3>Edit Activity</h3>
                <p>Update the status and leave a remark</p>
            </div>
            <button class="modal-close" onclick="closeModal('editModal')">✕</button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            <input type="hidden" name="_method" value="POST">
            <div class="modal-body">
                <div class="field-group">
                    <label>Activity Name</label>
                    <input type="text" class="f-input readonly" id="e_name" readonly>
                    <div class="hint">Activity name cannot be changed here.</div>
                </div>
                <div class="field-group">
                    <label for="e_status">Status <span class="req">*</span></label>
                    <div class="select-wrap">
                        <select class="f-select" id="e_status" name="status" required>
                            <option value="Pending">⏳  Pending</option>
                            <option value="Done">✅  Done</option>
                        </select>
                    </div>
                </div>
                <div class="field-group">
                    <label for="e_remark">Remark <span class="req">*</span></label>
                    <textarea class="f-textarea" id="e_remark" name="remark"
                              placeholder="Describe what was done or any issues…" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-mcancel" onclick="closeModal('editModal')">Cancel</button>
                <button type="submit" class="btn-mprimary">✓ Save Update</button>
            </div>
        </form>
    </div>
</div>


<!-- ══════════════════════════════════════
     MODAL 4 — DELETE CONFIRM
══════════════════════════════════════ -->
<div class="modal-overlay" id="deleteModal" onclick="overlayClose(event,'deleteModal')">
    <div class="modal" style="max-width:440px;">
        <div class="modal-header">
            <div class="modal-icon red">🗑</div>
            <div class="modal-title">
                <h3>Delete Activity</h3>
                <p>This action cannot be undone</p>
            </div>
            <button class="modal-close" onclick="closeModal('deleteModal')">✕</button>
        </div>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-body">
                <div class="delete-warning">
                    <span class="warn-icon">⚠️</span>
                    <p>You are about to permanently delete this activity and all its associated data.</p>
                </div>
                <p class="delete-confirm-text">
                    Are you sure you want to delete <strong id="d_name">this activity</strong>?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-mcancel" onclick="closeModal('deleteModal')">Cancel</button>
                <button type="submit" class="btn-mdanger">🗑 Yes, Delete</button>
            </div>
        </form>
    </div>
</div>


<script>
    /* ── Modal helpers ── */
    function openModal(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.add('open');
        el.style.visibility = 'visible';
        el.style.opacity = '1';
        el.style.pointerEvents = 'auto';
        document.body.style.overflow = 'hidden';
    }
    function closeModal(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.remove('open');
        el.style.visibility = 'hidden';
        el.style.opacity = '0';
        el.style.pointerEvents = 'none';
        document.body.style.overflow = '';
    }
    function overlayClose(e, id) {
        if (e.target === document.getElementById(id)) closeModal(id);
    }

    const filterButtons = document.querySelectorAll('.pill');
    const tableRows = document.querySelectorAll('tbody tr[data-status]');
    const searchInput = document.querySelector('.search-box input');

    function applyFilter(filter) {
        const term = searchInput ? searchInput.value.trim().toLowerCase() : '';

        tableRows.forEach(row => {
            const matchesFilter = filter === 'all' || row.dataset.status === filter;
            const matchesSearch = !term || row.dataset.name.toLowerCase().includes(term);
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
        btn.addEventListener('click', () => applyFilter(btn.dataset.filter));
    });

    if (searchInput) {
        searchInput.addEventListener('input', () => {
            const active = document.querySelector('.pill.active-all, .pill.active-done, .pill.active-pending');
            const currentFilter = active ? active.dataset.filter : 'all';
            applyFilter(currentFilter);
        });
    }

    /* ── View modal ── */
    function openView(act) {
        document.getElementById('v_name').textContent  = act.name;
        document.getElementById('v_desc').textContent  = act.desc;
        document.getElementById('v_user').textContent  = act.user;
        document.getElementById('v_time').textContent  = act.time;
        const statusEl = document.getElementById('v_status');
        statusEl.innerHTML = act.status === 'Done'
            ? '<span class="bdg bdg-done"><span class="bdg-dot"></span> Done</span>'
            : '<span class="bdg bdg-pending"><span class="bdg-dot"></span> Pending</span>';
        openModal('viewModal');
    }

    /* ── Edit modal ── */
    const editSel = document.getElementById('e_status');
    function tintSelect() {
        editSel.className = 'f-select';
        if (editSel.value === 'Done')    editSel.classList.add('status-done');
        if (editSel.value === 'Pending') editSel.classList.add('status-pending');
    }
    if (editSel) {
        editSel.addEventListener('change', tintSelect);
    }

    function openEdit(act) {
        document.getElementById('e_name').value    = act.name;
        document.getElementById('e_status').value  = act.status;
        document.getElementById('e_remark').value  = '';
        // POST to /activities/{id}/update
        document.getElementById('editForm').action = `/activities/${act.id}/update`;
        tintSelect();
        openModal('editModal');
    }

    /* ── Delete modal ── */
    function openDelete(id, name) {
        document.getElementById('d_name').textContent  = name;
        document.getElementById('deleteForm').action   = `/activities/${id}`;
        openModal('deleteModal');
    }

    /* Close on Escape key */
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            ['addModal','viewModal','editModal','deleteModal'].forEach(closeModal);
        }
    });

    /* Frontend behaviors: initialize counts */
    document.addEventListener('DOMContentLoaded', function(){
        updateActivityCounts();
    });

    function updateActivityCounts(){
        const rows = document.querySelectorAll('tbody tr[data-id]');
        const total = rows.length;
        let completed = 0, pending = 0;
        rows.forEach(r => { if (r.dataset.status === 'Done') completed++; else pending++; });
        document.getElementById('totalCount') && (document.getElementById('totalCount').textContent = total);
        document.getElementById('completedCount') && (document.getElementById('completedCount').textContent = completed);
        document.getElementById('pendingCount') && (document.getElementById('pendingCount').textContent = pending);
        document.querySelectorAll('.pill').forEach(p => {
            if (p.dataset.filter === 'all') p.textContent = `All (${total})`;
            if (p.dataset.filter === 'Done') p.textContent = `Done (${completed})`;
            if (p.dataset.filter === 'Pending') p.textContent = `Pending (${pending})`;
        });
    }
</script>
@endsection