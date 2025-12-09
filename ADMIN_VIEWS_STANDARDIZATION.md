# Admin Views Standardization Plan

## Current State Analysis

### File Organization Issues
1. **Duplicate Views:**
   - `admin/bookings.php` vs `admin/dashboard/bookings.php`
   - `admin/settings.php` vs `admin/dashboard/settings.php`
   - `admin/users.php` vs `admin/dashboard/users.php`
   - `admin/reports.php` vs `admin/reports/overview.php`
   - Multiple "new" versions of report files (e.g., `pending_bookings_new.php`)

2. **Inconsistent Naming:**
   - Mix of snake_case (`view_booking.php`, `edit_campus.php`) and descriptive names
   - Some files use plural (`bookings.php`), some singular (`booking.php`)

3. **Directory Structure:**
   - Some views in root `admin/` directory
   - Some in subdirectories (`admin/dashboard/`, `admin/reports/`)
   - No clear organizational pattern

### Layout & Template Issues
1. **Layout Usage:**
   - Most views use `layouts/admin/admin` ✅
   - One exception: `admin/reports/payments.php` uses `layouts/admin/base` ❌

2. **Section Structure:**
   - All views use `main_content` section ✅
   - Inconsistent use of `styles` and `scripts` sections

### Styling Issues
1. **CSS Organization:**
   - Inline styles scattered throughout views
   - Some styles in `admin.css`, some inline
   - No consistent component library

2. **Component Patterns:**
   - Different table structures (some use DataTables, some don't)
   - Different card/section structures
   - Different button styles and classes
   - Different modal implementations

### JavaScript Issues
1. **Script Organization:**
   - Mix of inline scripts and external files
   - Different DataTable initializations
   - Different modal implementations
   - No consistent utility functions

## Standardization Plan

### 1. File Organization Standard

#### Directory Structure
```
app/Views/admin/
├── dashboard/
│   └── overview.php          # Main dashboard
├── bookings/
│   ├── index.php             # List all bookings
│   ├── view.php              # View single booking
│   ├── manage.php            # Manage booking details
│   └── calendar.php          # Calendar view
├── campuses/
│   ├── index.php             # List all campuses
│   ├── view.php              # View single campus
│   ├── create.php            # Create new campus
│   └── edit.php              # Edit campus
├── pastors/
│   ├── index.php             # List all pastors
│   ├── view.php              # View single pastor
│   ├── create.php            # Create new pastor
│   └── edit.php              # Edit pastor
├── users/
│   ├── index.php             # List all users
│   └── view.php              # View single user
├── reports/
│   ├── index.php             # Reports overview
│   ├── bookings.php          # Booking reports
│   ├── payments.php          # Payment reports
│   ├── campus-performance.php
│   ├── pastor-performance.php
│   └── revenue.php
├── settings/
│   └── index.php             # System settings
└── blocked-dates/
    └── index.php             # Manage blocked dates
```

#### Naming Conventions
- **Files:** Use lowercase with hyphens for multi-word names
  - ✅ `view-booking.php` (not `view_booking.php`)
  - ✅ `campus-performance.php` (not `campus_performance.php`)
- **Directories:** Use lowercase, plural nouns
  - ✅ `bookings/`, `campuses/`, `pastors/`
- **View Methods:** Use RESTful naming
  - `index()` → `index.php`
  - `view($id)` → `view.php`
  - `create()` → `create.php`
  - `edit($id)` → `edit.php`

### 2. Layout Standard

#### All views MUST:
1. Extend `layouts/admin/admin`
2. Use `main_content` section for content
3. Use `styles` section for page-specific CSS (if needed)
4. Use `scripts` section for page-specific JavaScript (if needed)
5. Include page title in controller, not view

#### Standard View Structure
```php
<?= $this->extend('layouts/admin/admin') ?>

<?= $this->section('main_content') ?>
<div class="admin-page">
    <div class="page-header">
        <h1 class="page-title"><?= esc($title ?? 'Page Title') ?></h1>
        <div class="page-actions">
            <!-- Action buttons -->
        </div>
    </div>
    
    <div class="page-content">
        <!-- Main content -->
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<!-- Page-specific styles (minimal, prefer external CSS) -->
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Page-specific scripts -->
<?= $this->endSection() ?>
```

### 3. Component Standards

#### Page Header
```php
<div class="page-header">
    <div class="header-content">
        <h1 class="page-title"><?= esc($title) ?></h1>
        <?php if (!empty($subtitle)): ?>
            <p class="page-subtitle"><?= esc($subtitle) ?></p>
        <?php endif; ?>
    </div>
    <div class="header-actions">
        <!-- Action buttons -->
    </div>
</div>
```

#### Cards
```php
<div class="admin-card">
    <div class="card-header">
        <h3 class="card-title">Card Title</h3>
        <div class="card-actions">
            <!-- Optional action buttons -->
        </div>
    </div>
    <div class="card-body">
        <!-- Card content -->
    </div>
    <div class="card-footer">
        <!-- Optional footer -->
    </div>
</div>
```

#### Tables
- Use DataTables for all list views
- Standard table structure:
```php
<div class="table-container">
    <table id="dataTable" class="admin-table">
        <thead>
            <tr>
                <th>Column 1</th>
                <th>Column 2</th>
            </tr>
        </thead>
        <tbody>
            <!-- Table rows -->
        </tbody>
    </table>
</div>
```

#### Buttons
- Use standard button classes:
  - `.btn` - Base button
  - `.btn-primary` - Primary action
  - `.btn-secondary` - Secondary action
  - `.btn-success` - Success/Approve
  - `.btn-danger` - Danger/Delete
  - `.btn-warning` - Warning/Cancel
  - `.btn-info` - Info/View
  - `.btn-sm` - Small button
  - `.btn-lg` - Large button

#### Status Badges
```php
<span class="status-badge status-<?= $status ?>">
    <?= ucfirst($status) ?>
</span>
```

#### Modals
- Use consistent modal structure (defined in layout)
- Use data attributes for dynamic content

### 4. CSS Standards

#### Organization
1. **Global Styles:** `public/css/admin.css`
   - Layout, typography, colors
   - Common components (buttons, cards, tables)
   - Utility classes

2. **Component Styles:** Keep in `admin.css` or separate component files
   - Reusable components
   - No inline styles in views

3. **Page-Specific Styles:** Only when absolutely necessary
   - Keep in `styles` section
   - Document why inline is needed

#### CSS Variables
Use existing CSS variables from `admin.css`:
- `--primary-color`
- `--secondary-color`
- `--success`, `--warning`, `--danger`, `--info`
- `--white`, `--light-gray`, `--gray`, `--dark-gray`
- `--border-color`
- `--font-primary`, `--font-secondary`

### 5. JavaScript Standards

#### Organization
1. **Global Scripts:** `public/js/admin-dashboard.js`
   - Common utilities
   - Modal functions
   - DataTable defaults

2. **Page-Specific Scripts:** In `scripts` section
   - Only when needed
   - Keep minimal

#### DataTable Standard
```javascript
$(document).ready(function() {
    $('#dataTable').DataTable({
        responsive: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        order: [[0, 'desc']],
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            // ... other language settings
        }
    });
});
```

### 6. Implementation Priority

#### Phase 1: File Organization (High Priority)
1. Consolidate duplicate views
2. Reorganize into standard directory structure
3. Update controller routes to match new structure

#### Phase 2: Layout Standardization (High Priority)
1. Ensure all views use `layouts/admin/admin`
2. Standardize section usage
3. Update page headers to use standard structure

#### Phase 3: Component Standardization (Medium Priority)
1. Standardize cards, tables, buttons
2. Create reusable partials for common components
3. Update all views to use standard components

#### Phase 4: CSS Cleanup (Medium Priority)
1. Move inline styles to external CSS
2. Create component CSS classes
3. Document CSS structure

#### Phase 5: JavaScript Cleanup (Low Priority)
1. Consolidate common functions
2. Standardize DataTable initialization
3. Create utility functions

## Migration Checklist

### For Each View File:
- [ ] Located in correct directory structure
- [ ] Uses standard naming convention
- [ ] Extends `layouts/admin/admin`
- [ ] Uses standard page structure
- [ ] Uses standard components (cards, tables, buttons)
- [ ] No inline styles (or minimal, documented)
- [ ] JavaScript in `scripts` section or external file
- [ ] DataTable initialized with standard config
- [ ] Consistent with other views

## Notes

- Keep backward compatibility during migration
- Test each view after standardization
- Update documentation as views are standardized
- Consider creating view partials for common patterns

