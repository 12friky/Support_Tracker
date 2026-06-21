<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.x/dist/tabler-icons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        * { box-sizing: border-box; margin: 0; padding: 0; }

        @keyframes floatUp {
            0%   { transform: translateY(0) scale(1); opacity: 0.6; }
            100% { transform: translateY(-110vh) scale(1.3); opacity: 0; }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(-24px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes shimmer {
            0%   { background-position: -400px 0; }
            100% { background-position:  400px 0; }
        }
        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0   rgba(59,108,248,0.4); }
            50%       { box-shadow: 0 0 0 10px rgba(59,108,248,0);   }
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.3; }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px);   }
            50%       { transform: translateY(-12px); }
        }
        @keyframes rotateSlow {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #0b1120;
            min-height: 100vh;
        }

        /* ── Page shell ── */
        .page {
            min-height: 100vh;
            display: flex;
            align-items: stretch;
            position: relative;
            overflow: hidden;
        }

        /* ── Bubbles ── */
        .bubble {
            position: fixed;
            border-radius: 50%;
            background: rgba(59,108,248,0.10);
            animation: floatUp linear infinite;
            pointer-events: none;
            z-index: 0;
        }

        /* ── LEFT PANEL (illustration) ── */
        .panel-left {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            position: relative;
            background: linear-gradient(145deg, #0f1c3a 0%, #0b1120 100%);
            border-right: 1px solid rgba(255,255,255,0.06);
            animation: fadeInLeft 0.7s cubic-bezier(0.22,1,0.36,1) both;
        }

        .panel-left-tagline {
            text-align: center;
            margin-top: 36px;
        }
        .panel-left-tagline h1 {
            font-size: 1.55rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.4px;
            line-height: 1.3;
        }
        .panel-left-tagline p {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.4);
            margin-top: 8px;
            line-height: 1.6;
        }

        .badge-row {
            display: flex;
            gap: 10px;
            margin-top: 28px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .badge {
            display: flex;
            align-items: center;
            gap: 6px;
            background: rgba(59,108,248,0.12);
            border: 1px solid rgba(59,108,248,0.22);
            border-radius: 20px;
            padding: 6px 14px;
            font-size: 0.73rem;
            font-weight: 600;
            color: rgba(255,255,255,0.6);
            letter-spacing: 0.3px;
        }
        .badge i { font-size: 13px; color: #3b6cf8; }

        /* SVG illustration wrapper */
        .illustration-wrap {
            width: 100%;
            max-width: 380px;
            animation: float 5s ease-in-out infinite;
        }

        /* ── RIGHT PANEL (form) ── */
        .panel-right {
            width: 440px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            background: #0b1120;
            position: relative;
        }

        .form-card {
            width: 100%;
            animation: fadeInUp 0.7s 0.15s cubic-bezier(0.22,1,0.36,1) both;
        }

        /* Logo */
        .logo-wrap {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 36px;
        }
        .logo-ring {
            width: 52px; height: 52px; border-radius: 14px;
            background: linear-gradient(135deg, #3b6cf8, #7c4ff8);
            display: flex; align-items: center; justify-content: center;
            position: relative;
            animation: pulse 2.5s ease-in-out infinite;
            flex-shrink: 0;
        }
        .logo-ring i { font-size: 24px; color: #fff; }
        .logo-dot {
            position: absolute; bottom: -3px; right: -3px;
            width: 13px; height: 13px; border-radius: 50%;
            background: #22c55e;
            border: 2px solid #0b1120;
            animation: blink 2s ease-in-out infinite;
        }
        .logo-text h2 {
            font-size: 1.1rem; font-weight: 700; color: #fff; line-height: 1.2;
        }
        .logo-text span {
            font-size: 0.75rem; color: rgba(255,255,255,0.38); letter-spacing: 0.4px;
        }

        /* Heading */
        .form-heading {
            margin-bottom: 30px;
        }
        .form-heading h3 {
            font-size: 1.5rem; font-weight: 700; color: #fff; letter-spacing: -0.3px;
        }
        .form-heading p {
            font-size: 0.82rem; color: rgba(255,255,255,0.38); margin-top: 4px;
        }

        /* Fields */
        .field { margin-bottom: 18px; }
        .field label {
            display: block;
            font-size: 0.73rem; font-weight: 600;
            color: rgba(255,255,255,0.45);
            letter-spacing: 0.5px; text-transform: uppercase;
            margin-bottom: 8px;
        }
        .input-wrap { position: relative; }
        .input-wrap i.ico {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            font-size: 16px; color: rgba(255,255,255,0.25);
            pointer-events: none; transition: color 0.2s;
        }
        .field:focus-within .ico { color: #3b6cf8; }
        .input-wrap input {
            width: 100%;
            border: 1px solid rgba(255,255,255,0.1);
            background: rgba(255,255,255,0.05);
            color: #fff;
            border-radius: 12px;
            padding: 13px 14px 13px 42px;
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.2s, background 0.2s;
            font-family: inherit;
        }
        .input-wrap input::placeholder { color: rgba(255,255,255,0.2); }
        .input-wrap input:focus {
            border-color: rgba(59,108,248,0.65);
            background: rgba(59,108,248,0.07);
        }

        /* Button */
        .btn {
            width: 100%; border: none; border-radius: 12px;
            background: linear-gradient(90deg, #3b6cf8 0%, #6a4ff7 100%);
            color: #fff; padding: 14px 16px;
            font-size: 0.95rem; font-weight: 700;
            cursor: pointer; margin-top: 8px;
            position: relative; overflow: hidden;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: transform 0.15s, opacity 0.15s;
            font-family: inherit;
        }
        .btn::before {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.14), transparent);
            background-size: 400px 100%;
            animation: shimmer 2.5s infinite linear;
        }
        .btn:hover  { transform: translateY(-1px); opacity: 0.92; }
        .btn:active { transform: scale(0.98); }
        .btn i { font-size: 17px; }

        /* Divider */
        .divider {
            display: flex; align-items: center; gap: 12px;
            margin: 22px 0 16px;
        }
        .divider span { flex: 1; height: 1px; background: rgba(255,255,255,0.07); }
        .divider p { font-size: 0.7rem; color: rgba(255,255,255,0.28); white-space: nowrap; }

        /* Hint */
        .hint-box {
            display: flex; align-items: center; gap: 10px;
            background: rgba(59,108,248,0.08);
            border: 1px solid rgba(59,108,248,0.18);
            border-radius: 10px; padding: 10px 14px;
        }
        .hint-box i { font-size: 16px; color: #3b6cf8; flex-shrink: 0; }
        .hint-box span { font-size: 0.75rem; color: rgba(255,255,255,0.45); line-height: 1.5; }
        .hint-box strong { color: rgba(255,255,255,0.75); font-weight: 600; }

        /* Error */
        .error-text {
            margin-top: 10px; color: #f87171;
            font-size: 0.8rem; text-align: center;
        }

        /* Footer strip */
        .form-footer {
            display: flex; align-items: center; justify-content: center; gap: 6px;
            margin-top: 28px; padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.05);
        }
        .form-footer i { font-size: 13px; color: rgba(255,255,255,0.2); }
        .form-footer span { font-size: 0.71rem; color: rgba(255,255,255,0.2); }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .page { flex-direction: column; }
            .panel-left { padding: 40px 24px 32px; border-right: none; border-bottom: 1px solid rgba(255,255,255,0.06); }
            .illustration-wrap { max-width: 260px; }
            .panel-right { width: 100%; padding: 36px 24px 48px; }
        }
    </style>
</head>
<body>

<div class="page" id="page">

    <!-- ── LEFT: Illustration ── -->
    <div class="panel-left">
        <div class="illustration-wrap">
            <svg viewBox="0 0 380 300" xmlns="http://www.w3.org/2000/svg" fill="none">
                <defs>
                    <radialGradient id="glowA" cx="50%" cy="50%" r="50%">
                        <stop offset="0%"   stop-color="#3b6cf8" stop-opacity="0.22"/>
                        <stop offset="100%" stop-color="#3b6cf8" stop-opacity="0"/>
                    </radialGradient>
                    <radialGradient id="glowB" cx="50%" cy="50%" r="50%">
                        <stop offset="0%"   stop-color="#7c4ff8" stop-opacity="0.18"/>
                        <stop offset="100%" stop-color="#7c4ff8" stop-opacity="0"/>
                    </radialGradient>
                    <linearGradient id="cardGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%"   stop-color="#1e3060"/>
                        <stop offset="100%" stop-color="#0f1c3a"/>
                    </linearGradient>
                    <linearGradient id="btnGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%"   stop-color="#3b6cf8"/>
                        <stop offset="100%" stop-color="#6a4ff7"/>
                    </linearGradient>
                    <linearGradient id="screenGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%"   stop-color="#141e38"/>
                        <stop offset="100%" stop-color="#0d1628"/>
                    </linearGradient>
                    <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
                        <feDropShadow dx="0" dy="12" stdDeviation="18" flood-color="#000" flood-opacity="0.5"/>
                    </filter>
                    <clipPath id="screenClip">
                        <rect x="60" y="30" width="260" height="240" rx="18"/>
                    </clipPath>
                </defs>

                <!-- ambient glows -->
                <ellipse cx="190" cy="150" rx="160" ry="130" fill="url(#glowA)"/>
                <ellipse cx="260" cy="80"  rx="80"  ry="70"  fill="url(#glowB)"/>

                <!-- laptop base shadow -->
                <ellipse cx="190" cy="278" rx="140" ry="12" fill="#000" fill-opacity="0.35"/>

                <!-- laptop hinge bottom -->
                <rect x="55" y="255" width="270" height="14" rx="4" fill="#1a2540"/>
                <rect x="90" y="262" width="200" height="4" rx="2" fill="rgba(59,108,248,0.25)"/>

                <!-- laptop body -->
                <rect x="55" y="25" width="270" height="234" rx="18" fill="url(#cardGrad)" filter="url(#shadow)"/>
                <!-- bezel -->
                <rect x="55" y="25" width="270" height="234" rx="18" fill="none" stroke="rgba(255,255,255,0.08)" stroke-width="1"/>

                <!-- screen background -->
                <rect x="68" y="37" width="244" height="210" rx="12" fill="url(#screenGrad)" clip-path="url(#screenClip)"/>

                <!-- camera dot -->
                <circle cx="190" cy="33" r="3" fill="#0d1628"/>
                <circle cx="190" cy="33" r="1.2" fill="rgba(59,108,248,0.6)"/>

                <!-- top bar in screen -->
                <rect x="68" y="37" width="244" height="26" rx="0" fill="rgba(255,255,255,0.03)"/>
                <circle cx="84"  cy="50" r="4" fill="rgba(255,80,80,0.5)"/>
                <circle cx="97"  cy="50" r="4" fill="rgba(255,180,0,0.5)"/>
                <circle cx="110" cy="50" r="4" fill="rgba(0,200,100,0.5)"/>
                <rect x="140" y="44" width="100" height="12" rx="6" fill="rgba(255,255,255,0.06)"/>

                <!-- login card on screen -->
                <rect x="105" y="76" width="170" height="160" rx="12" fill="rgba(255,255,255,0.04)" stroke="rgba(255,255,255,0.08)" stroke-width="0.75"/>

                <!-- avatar circle -->
                <circle cx="190" cy="107" r="18" fill="rgba(59,108,248,0.2)" stroke="rgba(59,108,248,0.4)" stroke-width="1.5"/>
                <circle cx="190" cy="104" r="6"  fill="rgba(59,108,248,0.7)"/>
                <ellipse cx="190" cy="118" rx="9" ry="5" fill="rgba(59,108,248,0.4)"/>

                <!-- title bar -->
                <rect x="155" y="133" width="70" height="7" rx="3.5" fill="rgba(255,255,255,0.3)"/>
                <rect x="165" y="146" width="50" height="5"  rx="2.5" fill="rgba(255,255,255,0.12)"/>

                <!-- input field 1 -->
                <rect x="120" y="160" width="140" height="16" rx="5" fill="rgba(255,255,255,0.05)" stroke="rgba(255,255,255,0.1)" stroke-width="0.75"/>
                <rect x="126" y="165" width="40"  height="6"  rx="3" fill="rgba(255,255,255,0.12)"/>
                <circle cx="260" cy="168" r="4" fill="rgba(59,108,248,0.4)"/>

                <!-- input field 2 -->
                <rect x="120" y="182" width="140" height="16" rx="5" fill="rgba(255,255,255,0.05)" stroke="rgba(255,255,255,0.1)" stroke-width="0.75"/>
                <circle cx="130" cy="190" r="4" fill="rgba(255,255,255,0.12)"/>
                <circle cx="140" cy="190" r="4" fill="rgba(255,255,255,0.12)"/>
                <circle cx="150" cy="190" r="4" fill="rgba(255,255,255,0.12)"/>
                <circle cx="160" cy="190" r="4" fill="rgba(255,255,255,0.12)"/>
                <circle cx="170" cy="190" r="4" fill="rgba(255,255,255,0.12)"/>

                <!-- login button -->
                <rect x="120" y="205" width="140" height="18" rx="6" fill="url(#btnGrad)"/>
                <rect x="148" y="210" width="60" height="5"  rx="2.5" fill="rgba(255,255,255,0.55)"/>
                <rect x="212" y="211" width="16" height="4"  rx="2"   fill="rgba(255,255,255,0.3)"/>

                <!-- floating stats card -->
                <rect x="10" y="120" width="82" height="50" rx="10" fill="rgba(255,255,255,0.05)" stroke="rgba(255,255,255,0.1)" stroke-width="0.75"/>
                <rect x="18" y="130" width="40" height="5"  rx="2.5" fill="rgba(59,108,248,0.6)"/>
                <rect x="18" y="140" width="60" height="8"  rx="4"   fill="rgba(255,255,255,0.2)"/>
                <rect x="18" y="153" width="30" height="5"  rx="2.5" fill="rgba(255,255,255,0.1)"/>

                <!-- floating notif card -->
                <rect x="288" y="80" width="82" height="50" rx="10" fill="rgba(255,255,255,0.05)" stroke="rgba(255,255,255,0.1)" stroke-width="0.75"/>
                <circle cx="302" cy="100" r="8" fill="rgba(59,108,248,0.25)" stroke="rgba(59,108,248,0.4)" stroke-width="1"/>
                <rect x="315" y="93"  width="45" height="5" rx="2.5" fill="rgba(255,255,255,0.3)"/>
                <rect x="315" y="103" width="35" height="4" rx="2"   fill="rgba(255,255,255,0.12)"/>
                <circle cx="355" cy="91" r="5" fill="rgba(59,108,248,0.8)"/>
                <rect x="288" y="118" width="82" height="5"  rx="2.5" fill="rgba(59,108,248,0.15)"/>

                <!-- green shield badge -->
                <rect x="290" y="178" width="72" height="30" rx="8" fill="rgba(34,197,94,0.12)" stroke="rgba(34,197,94,0.25)" stroke-width="0.75"/>
                <circle cx="304" cy="193" r="6" fill="rgba(34,197,94,0.3)"/>
                <rect x="314" y="188" width="38" height="4" rx="2" fill="rgba(255,255,255,0.3)"/>
                <rect x="314" y="196" width="26" height="3" rx="1.5" fill="rgba(255,255,255,0.12)"/>

                <!-- decorative orbit ring -->
                <circle cx="190" cy="150" r="148" stroke="rgba(59,108,248,0.07)" stroke-width="1" stroke-dasharray="4 6"/>
            </svg>
        </div>

        <div class="panel-left-tagline">
            <h1>Your support hub,<br>all in one place.</h1>
            <p>Track, manage, and resolve tickets<br>with your team in real time.</p>
        </div>

        <div class="badge-row">
            <div class="badge"><i class="ti ti-shield-check"></i> Secure access</div>
            <div class="badge"><i class="ti ti-users"></i> Team portal</div>
            <div class="badge"><i class="ti ti-activity"></i> Live tracking</div>
        </div>
    </div>

    <!-- ── RIGHT: Form ── -->
    <div class="panel-right">
        <div class="form-card">

            <div class="logo-wrap">
                <div class="logo-ring">
                    <i class="ti ti-shield-check"></i>
                    <div class="logo-dot"></div>
                </div>
                <div class="logo-text">
                    <h2>Support Tracker</h2>
                    <span>Staff portal · Secure login</span>
                </div>
            </div>

            <div class="form-heading">
                <h3>Welcome back 👋</h3>
                <p>Sign in to your staff account to continue.</p>
            </div>

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf

                <div class="field">
                    <label for="staff_id">Staff ID</label>
                    <div class="input-wrap">
                        <input type="text" id="staff_id" name="staff_id"
                               value="{{ old('staff_id') }}"
                               placeholder="e.g. 123456" required autocomplete="off">
                        <i class="ti ti-id-badge-2 ico"></i>
                    </div>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <input type="password" id="password" name="password"
                               placeholder="••••••" required>
                        <i class="ti ti-lock ico"></i>
                    </div>
                </div>

                <button type="submit" class="btn">
                    <i class="ti ti-login-2"></i>
                    Sign in to portal
                </button>

                @if ($errors->any())
                    <div class="error-text">{{ $errors->first('login') }}</div>
                @endif
            </form>

            <div class="form-footer">
                <i class="ti ti-lock"></i>
                <span>Secured connection · Staff access only</span>
            </div>

        </div>
    </div>

</div>

<script>
    const page = document.getElementById('page');
    for (let i = 0; i < 20; i++) {
        const b = document.createElement('div');
        b.className = 'bubble';
        const s = Math.random() * 12 + 5;
        b.style.cssText = `
            width:${s}px; height:${s}px;
            left:${Math.random() * 100}%;
            bottom:-${s}px;
            opacity:${Math.random() * 0.4 + 0.05};
            animation-duration:${Math.random() * 16 + 10}s;
            animation-delay:${Math.random() * 12}s;
        `;
        page.appendChild(b);
    }
</script>

</body>
</html>