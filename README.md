# CSO Maitland-Newcastle HQ - Child Theme

This is the child theme for the CSO Maitland-Newcastle HQ website, built on top of the [CSO Master Theme](file:///Users/josh/Local%20Sites/cso-master-site-head-office/app/public/wp-content/themes/cso-master/README.md). It provides specialized functionality for managing schools, policies, and directory listings.

---

## ✨ Additional Functionality

This child theme extends the master theme with several key features:

### 🏫 School Directory & Finder
- **Schools Custom Post Type**: A dedicated post type for managing school data, including principal details, contact info, and gallery assets.
- **School Taxonomies**: Organized by **School Type** (e.g., Primary, Secondary) and **Parish**.
- **Interactive School Map**: A Google Maps integration that allows users to find schools geographically.
- **Gravity Forms Integration**: Dynamically populates school lists and emails into Gravity Forms for school-specific inquiries.

### 📜 Policy Management
- **Policy Search & Listing**: Specialized template logic in `page-policies.php` for searching and filtering diocese policies.
- **Integrated Icons**: Uses consistent iconography for policy types and document links.

### 🎨 HQ Specific Styling
- **Custom Color Palette**: Implements the specific HQ brand colors (Primary Dark Blue, Primary Light Green, etc.).
- **"School Style" Block Modifier**: Provides a custom background styling (`has-school-style`) for Gutenberg blocks.
- **Responsive Layouts**: Custom footer with column-based school lists and responsive header adjustments.

---

## 🔄 Theme Updater

The child theme includes its own automated updater logic (`inc/updater.php`) that connects to GitHub. This ensures the HQ site can receive child-theme-specific updates through the WordPress dashboard.

### Configuration
The updater matches the local `Version:` in `style.css` against the latest release on GitHub:
- **Repository**: `BeechAgency/cso-master-child-head-office`
- **Mechanism**: Polls the GitHub API for the latest release tag.

---

## 🛠 How to Create a New Version (Git Release)

To deploy updates to the child theme, follow these steps:

1. **Update Version**: Open `style.css` and increment the `Version:` header (e.g., `1.5.1`).
2. **Commit & Push**:
   ```bash
   git add style.css
   git commit -m "Bump version to 1.5.1"
   git push origin master
   ```
3. **Create Git Tag**: Create a tag that matches the version number exactly.
   ```bash
   git tag 1.5.1
   git push origin 1.5.1
   ```
4. **GitHub Release**: 
   - Go to the [GitHub Repository](https://github.com/BeechAgency/cso-master-child-head-office/releases).
   - Draft a new release using the tag you just pushed.
   - Title the release the same as the version (e.g., `1.5.1`).
   - (Optional) If a packaging workflow is active, the ZIP will be attached automatically. If not, the updater will use the source ZIP provided by GitHub.

Once the release is published, WordPress will detect the update within the next update check cycle.

---

*Maintained by Beech Agency*
