# OSS Studio Instructions – Setup Guide
For: Ross at On-Site Studios
Built by: Plexsoft Apps

---

## What This Is

A small 3-file PHP mini-site that lets you:
1. Upload a PNG or PDF instruction sheet via a hidden admin page
2. Have clients scan a QR code on the unit → they see the instructions instantly in their browser

---

## Files Included

| File | Purpose |
|---|---|
| `config.php` | Your settings (secret key, site URL, etc.) |
| `admin.php` | Hidden upload page — you use this |
| `index.php` | Public viewer — clients see this via QR |
| `uploads/` | Created automatically on first upload |

---

## Setup Steps

### 1. Edit config.php

Open `config.php` and update these four values:

```php
define('ADMIN_KEY', 'oss-admin-2024');   // Change to any secret password you like
define('SITE_TITLE', 'Studio Setup Instructions');
define('UPLOAD_DIR', 'uploads/');         // Leave as-is
define('PUBLIC_URL', 'https://yourdomain.com/studio-instructions/');
                                          // ↑ Change to your actual URL
```

**PUBLIC_URL** is the URL of your `index.php` page — this is what the QR code will point to.
Example: `https://onsitestudios.com/studio-guide/`

---

### 2. Upload to Your Web Host

Create a folder on your web server (e.g., `studio-instructions/`) and upload all three files into it.

The folder should be publicly accessible (not inside a `private` or restricted directory).

Make sure PHP is supported — any standard web host (cPanel, etc.) supports this.

---

### 3. Set Folder Permissions

The `uploads/` folder will be created automatically, but your server needs write permission on the parent folder.
Most hosts default to this. If you get a permission error, set the folder to `755`.

---

### 4. Upload Your Instructions

Visit your admin page in a browser:
```
https://yourdomain.com/studio-instructions/admin.php?key=oss-admin-2024
```
(Replace the domain and key with yours.)

- Drag & drop or click to select your PNG or PDF file
- Click **Upload & Publish**
- The file is now live at your public URL

**Keep the admin URL private.** Bookmark it for yourself. Don't put it on the unit.

---

### 5. Get Your QR Code

The admin page automatically shows and generates your QR code.
- Click **Download QR Code (PNG)**
- Print it and stick it on the unit!

You only need to generate the QR code once — the URL never changes.
When you upload a new version of the instructions, the same QR code still works.

---

## Updating Instructions Later

Just visit your admin URL and upload a new file. The old file is automatically replaced.
Clients who scan the QR code will immediately see the new version — no new sticker needed.

---

## File Format Notes

| Format | How it displays |
|---|---|
| **PNG** | Shown as a full-width image — great for a single-page visual guide |
| **PDF** | Shown in an embedded PDF viewer — great for multi-page docs |

Both formats include a **Download** button so clients can save a copy.

---

## Questions?

Contact Wes at Plexsoft Apps.
