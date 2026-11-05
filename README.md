# ZX Mini Shell

A lightweight, compact, and modern PHP Web File Manager.

## Features

- **Secure Bcrypt Authentication**: Robust password protection using `password_verify()`.
- **Compact All-In-One Status Bar**: Breadcrumb directory navigation merged with live server system info (OS, PHP version, IPs, disabled functions, MySQL & cURL status).
- **Interactive File Manager**:
  - Directory navigation, create folder, file upload.
  - In-browser file viewer and code editor with monospace typography.
  - Instant rename using SweetAlert2 modal popups.
  - Safe delete confirmation dialogs powered by SweetAlert2.
- **Custom Floating Dropdown**: Sleek obsidian dark dropdown menu replacing native OS select inputs.
- **Post-Redirect-Get (PRG) Pattern**: Eliminates accidental form resubmissions and duplicate alerts on browser refresh.
- **Responsive Dark Obsidian Theme**: Seamlessly adapts to desktop, tablet, and mobile screen sizes.

---

## Requirements

- PHP 7.4, 8.0, 8.1, 8.2, 8.3, or 8.4+
- Web Server: Apache, Nginx, or PHP Built-in Server

---

## Installation & Configuration

1. Place `zx.php` inside your web server root or desired subfolder.
2. Configure your password hash in `zx.php`:
   ```php
   $auth_pass = '$2a$12$QKXm8Ps1Yu9Vy5paliQtweQwH2dfgS6Qid2YDV.z6j3017FyiIetm';
   ```
   To generate a new Bcrypt hash:
   ```php
   echo password_hash('YourNewPassword', PASSWORD_BCRYPT);
   ```
3. Open `http://localhost/path/to/zx.php` in your browser.

---

## Credits & Authors

- **V1.0 (Original Development)**: [Wongedan](https://github.com/wongedan123)
- **V2.0 (Redesign & Modernization)**: [ZXMarchia](https://github.com/notbell1) / [Zone-Xploiter](https://github.com/Zone-Xploiter)

---

## License

This project is licensed under the [MIT License](LICENSE).

