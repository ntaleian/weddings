# Admin Template Routes

Routes have been successfully created for the admin template preview pages.

## 📍 Available Routes

All routes are prefixed with `/admin/template/` and require admin authentication.

### 1. Dashboard Example
**URL:** `/admin/template/dashboard`  
**Controller Method:** `AdminDashboard::templateDashboard()`  
**View:** `admin_template/dashboard.php`  
**Description:** Example dashboard page with stats cards, recent bookings table, and quick actions.

### 2. Example List Page
**URL:** `/admin/template/list`  
**Controller Method:** `AdminDashboard::templateList()`  
**View:** `admin_template/example_list.php`  
**Description:** Example list/table page with filters, DataTable, and modal example.

### 3. Component Showcase
**URL:** `/admin/template/showcase`  
**Controller Method:** `AdminDashboard::templateShowcase()`  
**View:** `admin_template/component_showcase.php`  
**Description:** Complete showcase of all available components (buttons, badges, forms, tables, modals, etc.).

## 🔐 Authentication

All template preview routes require:
- User must be logged in
- User role must be `admin`
- If not authenticated, redirects to `/admin/login`

## 📝 Route Configuration

Routes are defined in `app/Config/Routes.php`:

```php
// Template Preview Routes
$routes->get('template/dashboard', 'AdminDashboard::templateDashboard');
$routes->get('template/list', 'AdminDashboard::templateList');
$routes->get('template/showcase', 'AdminDashboard::templateShowcase');
```

## 🎯 Controller Methods

All methods are in `app/Controllers/AdminDashboard.php`:

- `templateDashboard()` - Loads dashboard example with real data
- `templateList()` - Loads example list page
- `templateShowcase()` - Loads component showcase page

## 🚀 How to Access

1. **Login as admin** at `/admin/login`
2. **Navigate to any template preview:**
   - Dashboard: `http://your-domain.com/admin/template/dashboard`
   - List Page: `http://your-domain.com/admin/template/list`
   - Showcase: `http://your-domain.com/admin/template/showcase`

## 📊 Data Passed to Views

### Dashboard
- `title` - Page title
- `pageTitle` - Breadcrumb title
- `totalBookings` - Total booking count
- `pendingBookings` - Pending booking count
- `approvedBookings` - Approved booking count
- `totalUsers` - Total user count
- `recentBookings` - Recent 5 bookings
- `pendingCount` - Pending count for sidebar badge

### List Page
- `title` - Page title
- `pageTitle` - Breadcrumb title
- `pendingCount` - Pending count for sidebar badge

### Showcase
- `title` - Page title
- `pageTitle` - Breadcrumb title
- `pendingCount` - Pending count for sidebar badge

## ✅ Status

All routes are **active** and ready to use!

## 🔄 Next Steps

1. **Test the routes** by navigating to them in your browser
2. **Review the design** on each page
3. **Customize** as needed
4. **Integrate** into your existing admin pages

---

**Note:** These are preview routes for reviewing the template design. Once you're satisfied with the design, you can integrate it into your existing admin pages.

