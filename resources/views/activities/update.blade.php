@extends('layouts.app')
@section('title', 'Update Activity')
@section('content')
<style>
    .form-page { padding: 28px; max-width: 680px; }
    .breadcrumb-row { display:flex;align-items:center;gap:6px;font-size:.78rem;color:#aaa;margin-bottom:20px; }
    .breadcrumb-row a { color:#3b6cf8;text-decoration:none; }
    .breadcrumb-row .sep { color:#ccc; }
    .card { background:#fff;border-radius:16px;box-shadow:0 1px 4px rgba(0,0,0,.06);padding:28px; }
    .card h2 { font-size:1.2rem;font-weight:800;color:#1a1a2e;margin:0 0 6px; }
    .card p { font-size:.83rem;color:#999;margin:0 0 24px; }
    .field { margin-bottom:18px; }
    .field label { display:block;font-size:.78rem;font-weight:700;color:#374151;margin-bottom:7px;text-transform:uppercase;letter-spacing:.04em; }
    .field input, .field textarea, .field select {
        width:100%;padding:11px 13px;border:1.5px solid #e8eaf0;border-radius:9px;
        font-size:.85rem;color:#1a1a2e;outline:none;font-family:inherit;
        transition:border-color .15s,box-shadow .15s;
    }
    .field input:focus, .field textarea:focus, .field select:focus { border-color:#3b6cf8;box-shadow:0 0 0 3px rgba(59,108,248,.1); }
    .field .readonly-field { background:#f8f9fb;color:#888;cursor:not-allowed;border-color:#eee; }
    .field textarea { resize:vertical;min-height:100px; }
    .field .error { color:#ef4444;font-size:.78rem;margin-top:5px; }
    .activity-info { background:#f8f9fb;border-radius:10px;padding:14px 16px;margin-bottom:20px;font-size:.84rem; }
    .activity-info strong { color:#1a1a2e; }
    .activity-info span { color:#888;font-size:.78rem; }
    .status-badge { display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:20px;font-size:.73rem;font-weight:600; }
    .status-done { background:#EDFAF4;color:#16a34a; }
    .status-pending { background:#FFF8EE;color:#f59e0b; }
    .actions { display:flex;gap:10px;margin-top:8px; }
    .btn-primary { background:#3b6cf8;color:#fff;border:none;border-radius:10px;padding:11px 24px;font-size:.86rem;font-weight:700;cursor:pointer; }
    .btn-secondary { background:#f4f6f9;color:#555;border:1.5px solid #e8eaf0;border-radius:10px;padding:11px 24px;font-size:.86rem;font-weight:600;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center; }
</style>

<div class="form-page">
    <div class="breadcrumb-row">
        <a href="{{ route('dashboard') }}">Home</a>
        <span class="sep">›</span>
        <a href="{{ route('activities.index') }}">Activities</a>
        <span class="sep">›</span>
        <span style="color:#555;font-weight:500;">Update Activity</span>
    </div>

    <div class="card">
        <h2>Update Activity</h2>
        <p>Log a status update and leave a remark for handover.</p>

        <div class="activity-info">
            <strong>{{ $activity->name }}</strong><br>
            <span>{{ $activity->description ?: 'No description provided' }}</span>
            <span style="margin-left:10px;">
                <span class="status-badge {{ $activity->status === 'Done' ? 'status-done' : 'status-pending' }}">
                    {{ $activity->status === 'Done' ? '✅' : '⏳' }} {{ $activity->status }}
                </span>
            </span>
        </div>

        @if($errors->any())
        <div style="background:#FEF2F2;color:#b91c1c;padding:12px 16px;border-radius:9px;margin-bottom:18px;font-size:.83rem;">
            @foreach($errors->all() as $error)
                <div>• {{ $error }}</div>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('activities.save', $id) }}">
            @csrf
            <div class="field">
                <label>Activity Name</label>
                <input type="text" class="readonly-field" value="{{ $activity->name }}" readonly>
            </div>
            <div class="field">
                <label for="status">New Status <span style="color:#ef4444;">*</span></label>
                <select id="status" name="status" required>
                    <option value="Pending" {{ old('status', $activity->status) === 'Pending' ? 'selected' : '' }}>⏳ Pending</option>
                    <option value="Done"    {{ old('status', $activity->status) === 'Done'    ? 'selected' : '' }}>✅ Done</option>
                </select>
                @error('status')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="field">
                <label for="remark">Remark <span style="color:#ef4444;">*</span></label>
                <textarea id="remark" name="remark" required
                          placeholder="Describe what was done, any issues encountered, or next steps…">{{ old('remark') }}</textarea>
                @error('remark')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="actions">
                <button type="submit" class="btn-primary">✓ Save Update</button>
                <a href="{{ route('activities.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
