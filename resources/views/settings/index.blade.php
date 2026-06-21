@extends('layouts.app')

@section('title', 'Settings')

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

    .settings-body { padding: 28px; }
    .breadcrumb-row {
        display: flex; align-items: center; gap: 6px;
        font-size: 0.78rem; color: #aaa; margin-bottom: 20px;
    }
    .breadcrumb-row a { color: #3b6cf8; text-decoration: none; }
    .breadcrumb-row .sep { color: #ccc; }
    .breadcrumb-row .current { color: #555; font-weight: 500; }

    .settings-grid { display: grid; grid-template-columns: 1.2fr 1fr; gap: 18px; }
    .card-panel {
        background: #fff; border-radius: 14px; box-shadow: 0 1px 4px rgba(0,0,0,.06);
        padding: 18px;
    }
    .card-panel h3 { font-size: 1rem; font-weight: 800; color: #1a1a2e; margin: 0 0 16px; }
    .switch-row { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 12px 0; border-bottom: 1px solid #eef0f5; }
    .switch-row:last-child { border-bottom: none; }
    .switch-row span { font-size: 0.84rem; color: #444; }
    .toggle {
        width: 48px; height: 28px; border-radius: 999px; background: #d9deea; position: relative; cursor: pointer;
    }
    .toggle::after {
        content: ''; position: absolute; top: 3px; left: 3px;
        width: 22px; height: 22px; background: #fff; border-radius: 50%;
        box-shadow: 0 1px 3px rgba(0,0,0,.15);
    }
    .toggle.on { background: #3b6cf8; }
    .toggle.on::after { left: 23px; }

    .form-group { margin-bottom: 14px; }
    .form-group label { display: block; font-size: 0.76rem; font-weight: 700; color: #6b7280; margin-bottom: 6px; text-transform: uppercase; letter-spacing: .04em; }
    .form-group input, .form-group select {
        width: 100%; padding: 10px 12px; border: 1.5px solid #e8eaf0; border-radius: 9px;
        font-size: 0.84rem; color: #1a1a2e; outline: none;
    }
    .form-group input:focus, .form-group select:focus { border-color: #3b6cf8; box-shadow: 0 0 0 3px rgba(59,108,248,.1); }
    .btn-save {
        background: #3b6cf8; color: #fff; border: none; border-radius: 10px;
        padding: 10px 18px; font-size: 0.85rem; font-weight: 600;
        cursor: pointer; box-shadow: 0 2px 8px rgba(59,108,248,.25);
    }
</style>

<header class="page-header">
    <div class="page-header-left">
        <button class="hamburger"><span></span><span></span><span></span></button>
        <h1>Settings</h1>
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

<div class="settings-body">
    <div class="breadcrumb-row">
        <a href="{{ route('dashboard') }}">Home</a>
        <span class="sep">›</span>
        <span class="current">Settings</span>
    </div>

    <div class="settings-grid">
        <div class="card-panel">
            <h3>Preferences</h3>
            <div class="switch-row"><span>Email notifications</span><div class="toggle {{ ($settings && $settings->email_notifications) ? 'on' : '' }}"></div></div>
            <div class="switch-row"><span>SMS alerts</span><div class="toggle {{ ($settings && $settings->sms_alerts) ? 'on' : '' }}"></div></div>
            <div class="switch-row"><span>Auto-save drafts</span><div class="toggle {{ ($settings && $settings->auto_save_drafts) ? 'on' : '' }}"></div></div>
        </div>

        <div class="card-panel">
            <h3>Account Settings</h3>
            <div class="form-group">
                <label for="timezone">Timezone</label>
                <select id="timezone">
                    <option {{ ($settings && $settings->timezone === 'UTC') ? 'selected' : '' }}>UTC</option>
                    <option {{ ($settings && $settings->timezone === 'GMT') ? 'selected' : '' }}>GMT</option>
                    <option {{ ($settings && $settings->timezone === 'EAT') ? 'selected' : '' }}>EAT</option>
                </select>
            </div>
            <div class="form-group">
                <label for="language">Language</label>
                <select id="language">
                    <option {{ ($settings && $settings->language === 'English') ? 'selected' : '' }}>English</option>
                    <option {{ ($settings && $settings->language === 'French') ? 'selected' : '' }}>French</option>
                </select>
            </div>
                <button class="btn-save">Save Changes</button>
                <hr style="margin:14px 0; border:none; border-top:1px solid #eef0f5;">
                <h3 style="margin-top:8px;">Frontend Profile (local-only)</h3>
                <div class="form-group">
                    <label for="displayName">Display name</label>
                    <input id="displayName" type="text" value="{{ $user->name ?? '' }}" placeholder="e.g. Francis Ngumah">
                </div>
                <div class="form-group">
                    <label for="displayRole">Role</label>
                    <input id="displayRole" type="text" value="{{ $user->role ?? '' }}" placeholder="e.g. Support Engineer">
                </div>
                <button class="btn-save" id="saveDisplay">Save Display Name</button>
                <div id="displaySaved" style="display:none;margin-top:10px;color:#16a34a;font-weight:600;">Saved locally</div>
        </div>
    </div>
</div>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const nameInput = document.getElementById('displayName');
            const roleInput = document.getElementById('displayRole');
            const saveBtn = document.getElementById('saveDisplay');
            const savedMsg = document.getElementById('displaySaved');
            const existing = window.SupportTracker?.getUser() || {};
            if (existing.name) nameInput.value = existing.name;
            if (existing.role) roleInput.value = existing.role;

            saveBtn.addEventListener('click', function(){
                const u = { name: nameInput.value.trim() || 'Francis Ngumah', role: roleInput.value.trim() || 'Support Engineer' };
                window.SupportTracker.setUser(u);
                savedMsg.style.display = 'block';
                setTimeout(()=> savedMsg.style.display = 'none', 2000);
            });
        });
    </script>
@endsection
