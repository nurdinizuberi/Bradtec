# BRADTEC CO. LTD — Corporate Website

Premium, fully responsive corporate website for **BRADTEC CO. LTD** — built with **HTML, CSS, vanilla JavaScript and PHP** (no build step), designed to run on **Hostinger shared hosting** out of the box.

**BRADTEC — The Epic of Excellence.** Construction • Mining • Real Estate • Transportation

---

## 📋 Quick Overview

| Feature | Details |
|---------|---------|
| **Pages** | 9 public pages + admin dashboard |
| **Tech Stack** | PHP 7.4+, HTML5, CSS3, Vanilla JS |
| **Database** | JSON files (no MySQL required) |
| **Hosting** | Hostinger shared hosting compatible |
| **Admin Panel** | Password-protected dashboard |
| **SEO** | Schema.org, Open Graph, sitemap.xml |
| **Security** | Rate limiting, brute-force protection, CSRF-safe |

---

## 1. Project Structure

```
bradtec/
├── index.php                  Homepage (hero, services, growth, projects, stats)
├── about.php                  About (who we are, mission, vision, values)
├── services.php               Services overview
├── projects.php               Projects portfolio (filterable)
├── contact.php                Contact page + inquiry form
├── 404.php                    Custom 404 error page
├── sitemap.xml                XML sitemap for search engines
├── robots.txt                 Crawling rules for search engines
├── .htaccess                  Apache config (clean URLs, security headers)
├── router.php                 Local dev server router (not needed on Hostinger)
│
├── services/                  Division service pages
│   ├── construction.php       Construction division config
│   ├── mining.php             Mining division config
│   ├── real-estate.php        Real estate property catalogue
│   ├── transportation.php     Transportation division config
│   ├── division_page.php      ★ Shared template for division pages
│   └── sections/              Per-division extra sections
│       ├── sustainability.php Mining sustainability section
│       └── safety.php         Mining safety section
│
├── admin/                     Admin dashboard (password-protected)
│   ├── login.php              Admin login page
│   ├── index.php              Dashboard overview
│   ├── services.php           Manage divisions
│   ├── products.php           Manage products/services catalogue
│   ├── properties.php         Manage property listings
│   ├── projects.php           Manage projects
│   ├── messages.php           View contact inquiries
│   ├── team.php               Manage team members
│   ├── company.php            Edit company info, stats, socials
│   └── partials/              Admin-specific partials
│       └── header.php         Admin header with navigation
│
├── api/                       PHP API endpoints
│   ├── config.php             Constants + helpers (site URL & password from .env)
│   ├── login.php              Admin authentication
│   ├── logout.php             Admin logout
│   ├── contact.php            Contact form handler → data/inquiries.json + email
│   ├── crud.php               Auth-protected JSON CRUD API
│   └── upload.php             Image upload handler → assets/uploads/
│
├── data/                      JSON content database
│   ├── company.json           Company info, contact, mission/vision, stats
│   ├── services.json          The four divisions
│   ├── products.json          Products & services catalogue
│   ├── properties.json        Real estate listings
│   ├── projects.json          Projects portfolio
│   ├── team.json              Team members
│   └── .htaccess              Blocks direct web access to data files
│
├── partials/                  Shared template partials
│   ├── head.php               HTML head (SEO, CSS, meta tags)
│   ├── header.php             Site header with navigation
│   ├── footer.php             Site footer
│   ├── scripts.php            JavaScript includes
│   ├── division_hero.php      Division page hero section
│   ├── division_gallery.php   Division photo gallery
│   ├── catalogue.php          Products/services catalogue
│   └── featured_projects.php  Featured projects section
│
└── assets/                    Static assets
    ├── css/
    │   ├── main.css           Main stylesheet (brand colors, responsive)
    │   └── admin.css          Admin dashboard styles
    ├── js/
    │   ├── main.js            Main site JavaScript
    │   └── admin.js           Admin dashboard JavaScript
    ├── images/                Static images
    │   └── favicon.svg        Site favicon
    └── uploads/               User-uploaded images (via admin)
        └── .htaccess          Blocks direct PHP execution in uploads

(one level above the site)
├── .env                       Admin password + site URL (gitignored)
└── tools/
    └── backup.sh              Daily backup script for data/ folder
```

---

## 2. Before Going Live (3 Quick Steps)

### Step 1: Add Your Logo

Place your official BRADTEC logo at:
```
assets/images/logo.png
```
(or `assets/images/bradt.png` — either filename is recognised)

The logo automatically appears in the navigation bar and footer. Until the file exists, a temporary text wordmark is shown instead.

### Step 2: Configure Environment Variables

Create a `.env` file **one level above the webroot** (outside `public_html`):

```env
BRADTEC_ADMIN_PASSWORD=YourStrongPassword123!
BRADTEC_SITE_URL=https://your-domain.com
```

| Variable | Description |
|----------|-------------|
| `BRADTEC_ADMIN_PASSWORD` | Password for admin dashboard login |
| `BRADTEC_SITE_URL` | Your live domain (used for SEO canonicals, Open Graph) |

**Security:** The `.env` file is gitignored and must never be committed to version control.

### Step 3: Update Contact Details

Either edit `data/company.json` directly, or log in to `/admin` → **Company Info** and fill in:
- Phone number
- Email address
- Physical address
- WhatsApp number
- Social media links
- Google Maps embed URL
- Statistics (years, projects, etc.)

> **Note:** All statistics on the site are clearly-marked placeholders. Update them from **Company Info → Statistics** once you have real figures.

---

## 3. Deploying to Hostinger (hPanel)

### Prerequisites
- Hostinger shared hosting plan (Premium or Business recommended)
- PHP 7.4+ enabled (8.x recommended)
- SSL certificate active (for HTTPS)

### Deployment Steps

#### 1. Access File Manager
1. Log in to hPanel
2. Go to **Files → File Manager**
3. Navigate to `public_html`

#### 2. Upload Site Files
1. Select all files inside the `bradtec/` folder
2. Upload them directly to `public_html`
3. **Important:** Files must be at the root level:
   ```
   public_html/
   ├── index.php        ✓
   ├── admin/           ✓
   ├── api/             ✓
   ├── assets/          ✓
   ├── data/            ✓
   └── ... (all other files)
   ```

#### 3. Set Folder Permissions
1. Right-click the `data/` folder
2. Select **Permissions** or **Change Permissions**
3. Set to `755` (or `775` if writes fail)
4. This allows PHP to write JSON files for the admin dashboard

#### 4. Upload Environment File
1. Create `.env` file one level above `public_html` (if possible)
2. Or place it inside `data/` folder (blocked from web by `.htaccess`)
3. Add your admin password and site URL:
   ```env
   BRADTEC_ADMIN_PASSWORD=YourSecurePassword
   BRADTEC_SITE_URL=https://your-domain.com
   ```

#### 5. Verify Clean URLs
1. Visit `https://your-domain.com/about`
2. Should load the About page without `.php` extension
3. If not working, check that:
   - `.htaccess` file was uploaded (not hidden)
   - ModRewrite is enabled (usually is on Hostinger)
   - "Force HTTPS" is enabled in hPanel → Security → SSL

#### 6. Test Admin Dashboard
1. Go to `https://your-domain.com/admin/`
2. Log in with your configured password
3. Test editing company info, adding a project, etc.

### Automatic Backups (Recommended)

Set up daily backups of your data folder:

1. In hPanel, go to **Advanced → Cron Jobs**
2. Add this cron job:
   ```
   0 2 * * * /home/uXXXXXX/tools/backup.sh >> /home/uXXXXXX/backup.log 2>&1
   ```
   (Replace `uXXXXXX` with your Hostinger username)

3. The script keeps the newest 14 backups by default
4. To restore: `tar -xzf bradtec-backups/data_*.tar.gz -C /path/to/site`

---

## 4. Admin Dashboard

Access at: `https://your-domain.com/admin/`

### Dashboard Sections

| Section | What You Can Do |
|---------|-----------------|
| **Dashboard** | Overview counts + recent inquiries |
| **Services** | Edit the four divisions (names, headings, images, colors) |
| **Products** | Add/edit/delete catalogue items for any division |
| **Properties** | Add/edit/delete real estate listings (prices, galleries, status) |
| **Projects** | Add/edit/delete portfolio projects (images, status, dates) |
| **Team** | Add/edit/delete team member profiles |
| **Messages** | Read, mark-as-read and delete contact inquiries |
| **Company Info** | Edit company details, mission/vision/values, statistics, socials, map |

### Trash System

Deleting an item moves it to **Trash** instead of removing it instantly:
- Click **Trash** button in the panel header to view deleted items
- **Restore** to bring items back
- **Delete Forever** to permanently remove
- **Empty Trash** to clear all deleted items

Changes appear on the live site **immediately** — no rebuild required.

---

## 5. Content Management

### Seed Content
All projects, properties, products, and statistics are **placeholder data** so the site looks complete. Replace them with real BRADTEC content via the admin dashboard.

### Images
- **Default:** Free Unsplash photo URLs
- **Upload:** Use admin dashboard to upload images from your computer
- **Storage:** Uploaded files go to `assets/uploads/`
- **External:** You can still paste external URLs if preferred

### Contact Form
- Submissions save to `data/inquiries.json`
- Optionally sends email notification
- Rate-limited: max 5 submissions per IP per 15 minutes

---

## 6. SEO Features

Built-in (no plugins needed):

- **Per-page optimization:** Titles, meta descriptions, canonical URLs, Open Graph & Twitter cards
- **Structured data:** JSON-LD for Organization, BreadcrumbList, and Service schemas
- **XML Sitemap:** `sitemap.xml` with all 9 pages
- **Robots.txt:** Allows crawling, blocks `/admin/`, `/api/`, and `/data/`
- **Custom 404:** Helpful error page with navigation links
- **Mobile-ready:** Responsive design with apple-touch-icon and theme-color

**Before going live:** Update `BRADTEC_SITE_URL` in `.env` and update URLs in `sitemap.xml` and `robots.txt`.

---

## 7. Security Features

| Feature | Description |
|---------|-------------|
| **Admin Password** | Stored in `.env`, never in source code |
| **Brute-force Protection** | 5 failed attempts = 15-minute lockout |
| **Rate Limiting** | Contact form limited to 5 submissions/IP/15min |
| **HTTPS Enforcement** | Force HTTPS with HSTS headers |
| **Secure Cookies** | Session cookies marked Secure + HttpOnly |
| **Data Protection** | `data/` folder blocked from direct web access |
| **File Locking** | Prevents concurrent write conflicts |
| **Input Sanitization** | All output escaped with `htmlspecialchars()` |

---

## 8. Local Development

### Prerequisites
- PHP 7.4+ installed locally

### Run Development Server
```bash
cd bradtec/
php -S localhost:8080 router.php
```

Open `http://localhost:8080` in your browser.

### Environment Variables for Local
Create `.env` in the project root:
```env
BRADTEC_ADMIN_PASSWORD=localdev123
BRADTEC_SITE_URL=http://localhost:8080
```

---

## 9. Troubleshooting

### Common Issues

| Problem | Solution |
|---------|----------|
| **Clean URLs not working** | Check `.htaccess` exists, ModRewrite enabled, Force HTTPS on |
| **Admin login fails** | Verify `.env` exists with `BRADTEC_ADMIN_PASSWORD` set |
| **"Could not write file"** | Set `data/` folder permissions to 755 or 775 |
| **Images not uploading** | Check `assets/uploads/` exists and is writable |
| **404 errors** | Ensure all files uploaded to `public_html` root |
| **Styles not loading** | Verify `assets/css/main.css` uploaded correctly |

### PHP Requirements
- PHP 7.4+ (8.x recommended)
- Extensions: JSON, Sessions, mail() — all standard on Hostinger
- No Composer or build tools required

---

## 10. File Reference

### Key Files

| File | Purpose |
|------|---------|
| `index.php` | Homepage |
| `services/mining.php` | Mining division config |
| `services/division_page.php` | Shared division page template |
| `data/services.json` | Division data (images, descriptions) |
| `data/company.json` | Company info and statistics |
| `api/config.php` | Core configuration and helpers |
| `.htaccess` | Apache configuration |
| `.env` | Environment variables (not in repo) |

### Data Files

| File | Content |
|------|---------|
| `company.json` | Name, contact, mission, vision, stats |
| `services.json` | Four divisions with images and descriptions |
| `products.json` | Products/services catalogue |
| `properties.json` | Real estate listings |
| `projects.json` | Portfolio projects |
| `team.json` | Team member profiles |
| `inquiries.json` | Contact form submissions (auto-created) |

---

## 11. Support & Maintenance

### Regular Tasks
- **Daily:** Automatic backups via cron job
- **Weekly:** Check admin messages/inquiries
- **Monthly:** Review and update content via admin dashboard
- **Quarterly:** Update statistics, add new projects

### Updating Content
1. Log in to admin dashboard
2. Navigate to the section you want to update
3. Make changes and save
4. Changes appear immediately on live site

### Backup Restoration
```bash
# List available backups
ls -la bradtec-backups/

# Restore specific backup
tar -xzf bradtec-backups/data_20260822_020000.tar.gz -C /path/to/public_html
```

---

## 12. License & Credits

© 2026 BRADTEC CO. LTD. All Rights Reserved.

**Built with:**
- PHP 7.4+
- HTML5
- CSS3
- Vanilla JavaScript
- Unsplash (free images)

**Designed for:** Hostinger Shared Hosting (hPanel)

---

**BRADTEC — The Epic of Excellence.**
