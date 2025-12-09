# Admin Template - Design System

This is a custom-designed admin template for the Watoto Church Wedding Management System. It provides a modern, professional interface with all necessary components for admin functionality.

## Structure

```
admin_template/
├── layout.php          # Main layout template
├── dashboard.php       # Example dashboard page
├── example_list.php    # Example list/table page
└── README.md          # This file
```

## Features

### Layout Components
- **Sidebar Navigation** - Collapsible sidebar with navigation menu
- **Top Header** - Search, notifications, and user profile
- **Main Content Area** - Flexible content container
- **Flash Messages** - Auto-dismissing alert system

### UI Components

#### Cards
- Standard card with header, body, and footer
- Stat cards for dashboard metrics
- Hover effects and shadows

#### Tables
- DataTables integration
- Responsive design
- Action buttons
- Status badges

#### Forms
- Form groups with labels
- Input fields with focus states
- Select dropdowns
- Textareas
- Form validation helpers

#### Buttons
- Primary, Secondary, Success, Danger, Warning, Info variants
- Small and large sizes
- Icon buttons
- Action buttons for tables

#### Status Badges
- Success, Warning, Danger, Info, Primary, Secondary
- Customizable colors

#### Modals
- Overlay with backdrop blur
- Smooth animations
- Close on overlay click or Escape key

#### Filters
- Filter panel component
- Grid-based filter rows
- Responsive layout

## Usage

### Basic Page Structure

```php
<?= $this->extend('admin_template/layout') ?>

<?= $this->section('content') ?>
<!-- Your page content here -->
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Page-specific JavaScript -->
<?= $this->endSection() ?>
```

### Page Header

```php
<div class="page-header">
    <h1 class="page-title">Page Title</h1>
    <p class="page-subtitle">Page description</p>
    <div class="page-actions">
        <button class="btn btn-primary">Action</button>
    </div>
</div>
```

### Card Component

```php
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Card Title</h3>
        <div class="card-actions">
            <button class="btn btn-sm btn-secondary">Action</button>
        </div>
    </div>
    <div class="card-body">
        <!-- Card content -->
    </div>
    <div class="card-footer">
        <!-- Footer content -->
    </div>
</div>
```

### Stat Card

```php
<div class="stat-card">
    <div class="stat-header">
        <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
            <i class="fas fa-icon"></i>
        </div>
    </div>
    <h3 class="stat-value">123</h3>
    <p class="stat-label">Label</p>
    <span class="stat-change positive">
        <i class="fas fa-arrow-up"></i> 12% increase
    </span>
</div>
```

### Table

```php
<div class="table-wrapper">
    <div class="table-header">
        <h3 class="table-title">Table Title</h3>
        <div class="table-tools">
            <button class="btn btn-sm btn-secondary">Action</button>
        </div>
    </div>
    <table class="data-table" id="myTable">
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

### Modal

```php
<div class="modal-overlay" id="myModal" style="display: none;">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Modal Title</h3>
            <button class="modal-close" onclick="closeModal('myModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <!-- Modal content -->
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('myModal')">Cancel</button>
            <button class="btn btn-primary">Save</button>
        </div>
    </div>
</div>
```

### JavaScript Helpers

```javascript
// Show modal
showModal('modalId');

// Close modal
closeModal('modalId');

// Validate form
if (validateForm('formId')) {
    // Form is valid
}

// Confirm action
confirmAction('Are you sure?', function() {
    // Action to perform
});
```

## Color Scheme

- **Primary**: Watoto Green (#25802D)
- **Accent**: Purple (#64017f)
- **Success**: Green (#10b981)
- **Warning**: Amber (#f59e0b)
- **Danger**: Red (#ef4444)
- **Info**: Blue (#3b82f6)

## Typography

- **Headings**: Space Grotesk
- **Body**: Inter
- **Sizes**: Responsive scaling

## Responsive Design

- Mobile-first approach
- Sidebar collapses on mobile
- Tables become scrollable
- Filters stack vertically

## Files

- `public/css/admin-template.css` - All styles
- `public/js/admin-template.js` - JavaScript utilities
- `app/Views/admin_template/layout.php` - Main layout
- `app/Views/admin_template/dashboard.php` - Example dashboard
- `app/Views/admin_template/example_list.php` - Example list page

## Next Steps

1. Review the template design
2. Customize colors and branding
3. Add more example pages as needed
4. Integrate with existing admin functionality
5. Test all components

