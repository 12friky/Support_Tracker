<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Support Tracker')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; }

        html, body {
            width: 100%;
            min-height: 100%;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #F4F6F9;
            margin: 0;
            overflow-y: scroll;
            scrollbar-gutter: stable;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: 220px;
            min-height: 100vh;
            background: #1a2540;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 200;
            padding: 0;
        }

        /* Logo / brand */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 22px 20px 18px;
            text-decoration: none;
        }
        .sidebar-brand-icon {
            width: 36px; height: 36px;
            background: #3b6cf8;
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .sidebar-brand-icon svg { width: 20px; height: 20px; fill: #fff; }
        .sidebar-brand-text {
            font-size: 0.92rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
        }
        .sidebar-brand-text span {
            display: block;
            font-size: 0.7rem;
            font-weight: 400;
            color: #7a8ab0;
            margin-top: 1px;
        }

        /* Nav */
        .sidebar-nav {
            flex: 1;
            padding: 8px 12px;
            list-style: none;
            margin: 0;
        }
        .sidebar-nav li { margin-bottom: 2px; }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            color: #8a9bc0;
            transition: background .15s, color .15s;
            white-space: nowrap;
        }
        .sidebar-nav a:hover {
            background: rgba(255,255,255,.06);
            color: #fff;
        }
        .sidebar-nav a.active {
            background: #3b6cf8;
            color: #fff;
        }
        .sidebar-nav .nav-icon {
            width: 18px; height: 18px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
            opacity: .85;
        }

        /* Bottom nav items (Profile, Settings, Logout) */
        .sidebar-bottom {
            padding: 8px 12px 20px;
            list-style: none;
            margin: 0;
            border-top: 1px solid rgba(255,255,255,.07);
        }
        .sidebar-bottom li { margin-bottom: 2px; }
        .sidebar-bottom a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            color: #8a9bc0;
            transition: background .15s, color .15s;
        }
        .sidebar-bottom a:hover { background: rgba(255,255,255,.06); color: #fff; }
        .sidebar-bottom a.logout { color: #f87171; }
        .sidebar-bottom a.logout:hover { background: rgba(248,113,113,.12); color: #f87171; }
        .sidebar-bottom .nav-icon {
            width: 18px; height: 18px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; flex-shrink: 0; opacity: .85;
        }

        /* ── Main wrapper ── */
        .main-wrapper {
            margin-left: 220px;
            width: calc(100% - 220px);
            min-width: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            flex: 1 1 auto;
        }

        main {
            width: 100%;
            min-width: 0;
        }

        /* ── Responsive: collapse sidebar on small screens ── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); transition: transform .25s; }
            .sidebar.open { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
        }
    </style>
</head>
<body>

    <div style="display:flex; width:100%; min-height:100vh;">

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">

            <!-- Brand -->
            <a href="{{ route('dashboard') }}" class="sidebar-brand">
                <div class="sidebar-brand-icon">
                    <!-- Shield/support icon -->
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2L3 6v6c0 5.25 3.75 10.15 9 11.35C17.25 22.15 21 17.25 21 12V6L12 2z"/>
                    </svg>
                </div>
                <div class="sidebar-brand-text">
                    Application
                    <span>Support System</span>
                </div>
            </a>

            <!-- Main nav -->
            <ul class="sidebar-nav">
                <li>
                    <a href="{{ route('dashboard') }}"
                       class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <span class="nav-icon">🏠</span>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('activities.index') }}"
                       class="{{ request()->routeIs('activities.*') ? 'active' : '' }}">
                        <span class="nav-icon">📊</span>
                        Activities
                    </a>
                </li>
                <li>
                    <a href="{{ route('users.index') }}"
                       class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <span class="nav-icon">👥</span>
                        Users
                    </a>
                </li>
                <li>
                    <a href="{{ route('handover') }}"
                       class="{{ request()->routeIs('handover') ? 'active' : '' }}">
                        <span class="nav-icon">📋</span>
                        Daily Handover
                    </a>
                </li>
                <li>
                    <a href="{{ route('reports') }}"
                       class="{{ request()->routeIs('reports') ? 'active' : '' }}">
                        <span class="nav-icon">📄</span>
                        Reports
                    </a>
                </li>
            </ul>

            <ul class="sidebar-bottom">
                <li>
                    <a href="{{ route('profile') }}"
                       class="{{ request()->routeIs('profile') ? 'active' : '' }}">
                        <span class="nav-icon">👤</span>
                        Profile
                    </a>
                </li>
                <li>
                    <a href="{{ route('settings') }}"
                       class="{{ request()->routeIs('settings') ? 'active' : '' }}">
                        <span class="nav-icon">⚙️</span>
                        Settings
                    </a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" style="margin:0;padding:0;">
                        @csrf
                        <button type="submit" style="all:unset;display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:10px;font-size:0.85rem;font-weight:500;color:#f87171;cursor:pointer;width:100%;box-sizing:border-box;transition:background .15s;"
                            onmouseover="this.style.background='rgba(248,113,113,.12)'"
                            onmouseout="this.style.background='transparent'">
                            <span class="nav-icon">↩️</span>
                            Logout
                        </button>
                    </form>
                </li>
            </ul>

        </aside>

        <!-- Content area -->
        <div class="main-wrapper">
            <main>
                @yield('content')
            </main>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Mobile sidebar toggle (triggered by hamburger in dashboard header) -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const hamburger = document.querySelector('.hamburger');
            const sidebar   = document.getElementById('sidebar');
            if (hamburger && sidebar) {
                hamburger.addEventListener('click', function () {
                    sidebar.classList.toggle('open');
                });
            }
        });
    </script>
    <!-- Inject real session user into the frontend glue -->
    <script>
        (function(){
            // Seed from server session so the display always reflects the logged-in user
            const serverUser = {
                name: "{{ session('staff_user_name', 'Staff') }}",
                role: "{{ session('staff_user_role', 'Support') }}"
            };

            function getInitials(name) {
                return (name || 'ST').split(' ').map(s => s[0]).slice(0, 2).join('').toUpperCase();
            }

            function applyUser(u) {
                document.querySelectorAll('.user-name').forEach(el => el.textContent = u.name);
                document.querySelectorAll('.user-role').forEach(el => el.textContent = u.role || 'Support');
                document.querySelectorAll('.user-avatar').forEach(el => {
                    if (/^[A-Z]{0,3}$/.test(el.textContent.trim())) {
                        el.textContent = getInitials(u.name);
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', function () {
                applyUser(serverUser);
            });

            // Expose small API for other pages
            window.SupportTracker = {
                getUser: function() { return serverUser; },
                setUser: function(user) { applyUser(user || serverUser); }
            };
        })();
    </script>
</body>
</html>