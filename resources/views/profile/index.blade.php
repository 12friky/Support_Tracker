@extends('layouts.app')

@section('title', 'Profile')

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

    .profile-body { padding: 28px; }
    .breadcrumb-row {
        display: flex; align-items: center; gap: 6px;
        font-size: 0.78rem; color: #aaa; margin-bottom: 20px;
    }
    .breadcrumb-row a { color: #3b6cf8; text-decoration: none; }
    .breadcrumb-row .sep { color: #ccc; }
    .breadcrumb-row .current { color: #555; font-weight: 500; }

    .profile-card {
        background: #fff; border-radius: 18px; box-shadow: 0 1px 4px rgba(0,0,0,.06);
        padding: 24px;
    }
    .profile-top { display: flex; align-items: center; gap: 18px; }
    .avatar-large {
        width: 88px; height: 88px; border-radius: 18px;
        background: #EFF3FF; color: #3b6cf8;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem; font-weight: 800;
    }
    .profile-meta h3 { margin: 0 0 6px; font-size: 1.2rem; font-weight: 800; color: #1a1a2e; }
    .profile-meta p { margin: 0; color: #777; font-size: 0.85rem; }
    .grid-2 { display: grid; grid-template-columns: 1.1fr .9fr; gap: 18px; margin-top: 22px; }
    .panel {
        background: #fafbfc; border: 1px solid #eef0f5; border-radius: 14px;
        padding: 18px;
    }
    .panel h4 { font-size: 0.92rem; font-weight: 700; color: #1a1a2e; margin: 0 0 14px; }
    .info-row { display: flex; justify-content: space-between; gap: 12px; padding: 10px 0; border-bottom: 1px solid #eef0f5; font-size: 0.84rem; }
    .info-row:last-child { border-bottom: none; }
    .info-label { color: #999; }
    .info-value { color: #1a1a2e; font-weight: 600; }
    .activity-list { list-style: none; padding: 0; margin: 0; }
    .activity-list li {
        display: flex; align-items: center; justify-content: space-between;
        padding: 10px 0; border-bottom: 1px solid #eef0f5;
        font-size: 0.84rem;
    }
    .activity-list li:last-child { border-bottom: none; }
    .form-grid { display: grid; gap: 12px; }
    .form-group label { display: block; font-size: 0.76rem; font-weight: 700; color: #6b7280; margin-bottom: 6px; text-transform: uppercase; letter-spacing: .04em; }
    .form-group input {
        width: 100%; padding: 10px 12px; border: 1.5px solid #e8eaf0; border-radius: 9px;
        font-size: 0.84rem; color: #1a1a2e; outline: none;
    }
    .form-group input:focus { border-color: #3b6cf8; box-shadow: 0 0 0 3px rgba(59,108,248,.1); }
    .btn-save {
        background: #3b6cf8; color: #fff; border: none; border-radius: 10px;
        padding: 10px 18px; font-size: 0.85rem; font-weight: 600;
        cursor: pointer; box-shadow: 0 2px 8px rgba(59,108,248,.25);
    }
</style>

<header class="page-header">
    <div class="page-header-left">
        <button class="hamburger"><span></span><span></span><span></span></button>
        <h1>Profile</h1>
    </div>
    <div class="page-header-right">
        <div class="user-chip">
            <div class="user-avatar">{{ strtoupper(substr(session('staff_user_name', 'ST'), 0, 2)) }}</div>
            <div class="user-info">
                <div class="user-name">{{ session('staff_user_name', 'Staff') }}</div>
                <div class="user-role">{{ session('staff_user_role', 'Support') }}</div>
            </div>
        </div>
    </div>
</header>

<div class="profile-body">
    <div class="breadcrumb-row">
        <a href="{{ route('dashboard') }}">Home</a>
        <span class="sep">›</span>
        <span class="current">Profile</span>
    </div>

    <div class="profile-card">
        <div class="profile-top">
            <div class="avatar-large">{{ strtoupper(substr($user->name ?? 'ST', 0, 2)) }}</div>
            <div class="profile-meta">
                <h3>{{ $user->name ?? 'Unknown User' }}</h3>
                <p>{{ $user->role ?? 'User' }} · {{ $user->department ?: 'Support Team' }}</p>
            </div>
        </div>

        <div class="grid-2">
            <div class="panel">
                <h4>Personal Information</h4>
                <div class="info-row"><span class="info-label">Staff ID</span><span class="info-value">{{ $user->staff_id ?? '-' }}</span></div>
                <div class="info-row"><span class="info-label">Email</span><span class="info-value">{{ $user->email ?? '-' }}</span></div>
                <div class="info-row"><span class="info-label">Department</span><span class="info-value">{{ $user->department ?: '-' }}</span></div>
                <div class="info-row"><span class="info-label">Phone</span><span class="info-value">{{ $user->phone ?: '-' }}</span></div>
                <div class="info-row"><span class="info-label">Location</span><span class="info-value">{{ $user->location ?: '-' }}</span></div>
                <div class="info-row"><span class="info-label">Shift</span><span class="info-value">{{ $user->shift ?: '-' }}</span></div>
            </div>
            <div class="panel">
                <h4>Update Profile</h4>

                @if(session('profile_success'))
                    <div style="background:#EDFAF4;color:#15803d;border:1px solid #bbf7d0;border-radius:8px;padding:10px 14px;margin-bottom:14px;font-size:.82rem;font-weight:600;">
                        {{ session('profile_success') }}
                    </div>
                @endif

                @if($errors->has('profile'))
                    <div style="background:#FEF2F2;color:#b91c1c;border:1px solid #fecaca;border-radius:8px;padding:10px 14px;margin-bottom:14px;font-size:.82rem;">
                        {{ $errors->first('profile') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="profileName">Full Name</label>
                            <input id="profileName" type="text" name="name"
                                   value="{{ old('name', $user->name ?? '') }}" required>
                            @error('name')<div style="color:#ef4444;font-size:.75rem;margin-top:4px;">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="profilePhone">Phone</label>
                            <input id="profilePhone" type="text" name="phone"
                                   value="{{ old('phone', $user->phone ?? '') }}" placeholder="+233 XX XXX XXXX">
                            @error('phone')<div style="color:#ef4444;font-size:.75rem;margin-top:4px;">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="profileLocation">Location</label>
                            <input id="profileLocation" type="text" name="location"
                                   value="{{ old('location', $user->location ?? '') }}" placeholder="e.g. Accra, Ghana">
                            @error('location')<div style="color:#ef4444;font-size:.75rem;margin-top:4px;">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="btn-save">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="grid-2" style="margin-top:18px;">
            <div class="panel">
                <h4>Recent Activity</h4>
                <ul class="activity-list">
                    @forelse($recentActivities as $item)
                    <li>
                        <span>{{ $item->activity->name ?? 'Activity' }}</span>
                        <span style="color:#aaa;font-size:.78rem;">{{ $item->created_at->diffForHumans() }}</span>
                    </li>
                    @empty
                    <li style="color:#aaa;font-size:.82rem;">No recent activity yet.</li>
                    @endforelse
                </ul>
            </div>
            <div class="panel">
                <h4>Change Password</h4>

                @if(session('password_success'))
                    <div style="background:#EDFAF4;color:#15803d;border:1px solid #bbf7d0;border-radius:8px;padding:10px 14px;margin-bottom:14px;font-size:.82rem;font-weight:600;">
                        {{ session('password_success') }}
                    </div>
                @endif

                @if($errors->has('password'))
                    <div style="background:#FEF2F2;color:#b91c1c;border:1px solid #fecaca;border-radius:8px;padding:10px 14px;margin-bottom:14px;font-size:.82rem;">
                        {{ $errors->first('password') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="currentPassword">Current Password</label>
                            <input id="currentPassword" type="password" name="current_password"
                                   placeholder="Enter current password" required>
                        </div>
                        <div class="form-group">
                            <label for="newPassword">New Password</label>
                            <input id="newPassword" type="password" name="new_password"
                                   placeholder="Min 6 characters" required>
                            @error('new_password')<div style="color:#ef4444;font-size:.75rem;margin-top:4px;">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="confirmPassword">Confirm New Password</label>
                            <input id="confirmPassword" type="password" name="new_password_confirmation"
                                   placeholder="Repeat new password" required>
                        </div>
                        <button type="submit" class="btn-save">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
