# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0] - 2026-11-05

### Added
- Complete modern Dark Obsidian UI redesign.
- Integrated SweetAlert2 for interactive rename prompts and delete confirmation dialogs.
- Custom floating action dropdown menu replacing native browser selects.
- Luxury custom file upload component with dynamic file name indicator.
- Single-row compact header uniting directory breadcrumbs and real-time server telemetry.
- Post-Redirect-Get (PRG) pattern utilizing session flash messages to prevent duplicate form resubmissions on refresh.
- Custom dark-themed webkit scrollbar and textarea resizer.
- "About" modal detailing project attribution and version history.

### Changed
- Migrated legacy password authentication to secure Bcrypt hashing via `password_verify()`.
- Modernized layout to be fully responsive on mobile, tablet, and desktop viewports.
- Enhanced code readability and removed unused legacy variables.

### Fixed
- Fixed duplicate "Gagal menghapus folder" alert when reloading the browser after a deletion.
- Fixed oversized modal dialogs by introducing a tailored compact modal profile.

---

## [1.0.0] - Legacy

### Added
- Initial release by Wongedan.
- Core directory navigation and basic file management capabilities (create folder, upload, edit, rename, delete).

