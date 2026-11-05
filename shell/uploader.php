<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Uploader</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            border-radius: 0;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #0d0f12;
            color: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        .upload-card {
            background-color: #15181e;
            border: 1px solid #262c36;
            width: 100%;
            max-width: 440px;
            padding: 24px;
        }
        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid #262c36;
            padding-bottom: 12px;
        }
        .title {
            font-size: 15px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .badge-target {
            font-size: 11px;
            color: #64748b;
            margin-top: 6px;
            word-break: break-all;
        }
        .notice {
            padding: 10px 14px;
            margin-bottom: 16px;
            font-size: 12.5px;
            border: 1px solid transparent;
            font-weight: 500;
        }
        .notice-success {
            background: rgba(34, 197, 94, 0.12);
            border-color: #22c55e;
            color: #4ade80;
        }
        .notice-error {
            background: rgba(239, 68, 68, 0.12);
            border-color: #ef4444;
            color: #f87171;
        }
        .form-group {
            margin-bottom: 16px;
        }
        input[type="file"] {
            background: #090b0e;
            border: 1px solid #262c36;
            color: #94a3b8;
            padding: 8px 12px;
            width: 100%;
            font-size: 13px;
            outline: none;
        }
        .btn-upload {
            background-color: #22c55e;
            border: 1px solid #22c55e;
            color: #000000;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 10px 16px;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.15s ease;
        }
        .btn-upload:hover {
            background-color: #4ade80;
            border-color: #4ade80;
        }
    </style>
</head>
<body>

<?php
$message = null;
$messageType = null;
$uploadDir = __DIR__ . DIRECTORY_SEPARATOR;

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (isset($_FILES['userfile']) && !empty($_FILES['userfile']['name'])) {
        if ($_FILES['userfile']['error'] === UPLOAD_ERR_OK) {
            $destFile = $uploadDir . basename($_FILES['userfile']['name']);
            if (move_uploaded_file($_FILES['userfile']['tmp_name'], $destFile)) {
                $message = "Uploaded successfully: " . htmlspecialchars(basename($_FILES['userfile']['name']));
                $messageType = "success";
            } else {
                $message = "Failed to move uploaded file. Check folder permissions.";
                $messageType = "error";
            }
        } else {
            $message = "Upload error code: " . $_FILES['userfile']['error'];
            $messageType = "error";
        }
    } else {
        $message = "Please choose a file to upload.";
        $messageType = "error";
    }
}
?>

<div class="upload-card">
    <div class="header">
        <div class="title">Upload File</div>
        <div class="badge-target">Dest: <?php echo htmlspecialchars($uploadDir); ?></div>
    </div>

    <?php if ($message): ?>
        <div class="notice notice-<?php echo $messageType; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form enctype="multipart/form-data" method="POST">
        <div class="form-group">
            <input type="file" name="userfile" required>
        </div>
        <button type="submit" class="btn-upload">Upload File</button>
    </form>
</div>

</body>
</html>
