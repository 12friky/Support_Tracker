@extends('layouts.app')

@section('title', 'Users')

@section('content')
<style>
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

    .users-body { padding: 28px; }
    .breadcrumb-row {
        display: flex; align-items: center; gap: 6px;
        font-size: 0.78rem; color: #aaa; margin-bottom: 20px;
    }
    .breadcrumb-row a { color: #3b6cf8; text-decoration: none; }
    .breadcrumb-row .sep { color: #ccc; }
    .breadcrumb-row .current { color: #555; font-weight: 500; }

    .title-row {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 24px;
    }
    .title-row-left h2 { font-size: 1.4rem; font-weight: 800; color: #1a1a2e; margin: 0 0 4px; }
    .title-row-left p { font-size: 0.82rem; color: #999; margin: 0; }
    .btn-add {
        display: inline-flex; align-items: center; gap: 8px;
        background: #3b6cf8; color: #fff; border: none; border-radius: 10px;
        padding: 10px 18px; font-size: 0.85rem; font-weight: 600;
        cursor: pointer; box-shadow: 0 2px 8px rgba(59,108,248,.25);
    }

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
    .strip-icon.blue { background: #EFF3FF; }
    .strip-icon.green { background: #EDFAF4; }
    .strip-icon.amber { background: #FFF8EE; }
    .strip-num { font-size: 1.4rem; font-weight: 800; color: #1a1a2e; line-height: 1; }
    .strip-label { font-size: 0.74rem; color: #999; margin-top: 3px; }

    .table-card { background: #fff; border-radius: 14px; box-shadow: 0 1px 4px rgba(0,0,0,.06); overflow: hidden; }
    table.user-table { width: 100%; border-collapse: collapse; }
    .user-table thead tr { border-bottom: 1px solid #f0f0f5; }
    .user-table thead th {
        padding: 14px 20px; font-size: 0.75rem; font-weight: 700;
        color: #aaa; text-transform: uppercase; letter-spacing: .05em;
        background: #fafbfc; text-align: left;
    }
    .user-table tbody tr { border-bottom: 1px solid #f8f8fb; }
    .user-table tbody td { padding: 15px 20px; font-size: 0.84rem; color: #444; vertical-align: middle; }
    .user-table tbody tr:hover { background: #f9faff; }
    .user-cell { display: flex; align-items: center; gap: 12px; }
    .user-dot {
        width: 38px; height: 38px; border-radius: 50%;
        background: #EFF3FF; color: #3b6cf8;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.78rem; font-weight: 700;
    }
    .badge-pill {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 5px 10px; border-radius: 999px; font-size: 0.73rem; font-weight: 600;
    }
    .badge-admin { background: #EFF3FF; color: #3b6cf8; }
    .badge-user { background: #EDFAF4; color: #16a34a; }
</style>

<header class="page-header">
    <div class="page-header-left">
        <button class="hamburger"><span></span><span></span><span></span></button>
        <h1>Users</h1>
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

<div class="users-body">
    <div class="breadcrumb-row">
        <a href="{{ route('dashboard') }}">Home</a>
        <span class="sep">›</span>
        <span class="current">Users</span>
    </div>

    <div class="title-row">
        <div class="title-row-left">
            <h2>Team Members</h2>
            <p>Manage users and support roles in the system</p>
        </div>
        <button class="btn-add">＋ Add User</button>
    </div>

    <div class="stats-strip">
        <div class="strip-card">
            <div class="strip-icon blue">👥</div>
            <div><div class="strip-num">{{ $total }}</div><div class="strip-label">Total Users</div></div>
        </div>
        <div class="strip-card">
            <div class="strip-icon green">✅</div>
            <div><div class="strip-num">{{ $active }}</div><div class="strip-label">Active</div></div>
        </div>
        <div class="strip-card">
            <div class="strip-icon amber">🛡️</div>
            <div><div class="strip-num">{{ $admins }}</div><div class="strip-label">Admins</div></div>
        </div>
    </div>

    <div class="table-card">
        <table class="user-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Department</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>
                        <div class="user-cell">
                            <div class="user-dot">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                            <div>
                                <div style="font-weight:600;color:#1a1a2e;">{{ $user->name }}</div>
                                <div style="font-size:.74rem;color:#aaa;">{{ $user->role }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td><span class="badge-pill {{ $user->role === 'Admin' ? 'badge-admin' : 'badge-user' }}">{{ $user->role }}</span></td>
                    <td>{{ $user->department ?: '-' }}</td>
                    <td><span class="badge-pill badge-user">{{ $user->is_active ? 'Active' : 'Inactive' }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
