# Watoto Church Wedding Booking System Template

This template directory contains a complete wedding booking system for Watoto Church, featuring both user-facing and administrative interfaces.

## 📁 Directory Structure

```
template/
├── assets/
│   ├── css/
│   │   ├── admin.css          # Admin dashboard styles
│   │   ├── auth.css           # Authentication page styles
│   │   ├── dashboard.css      # User dashboard styles
│   │   └── style.css          # Main website styles
│   ├── images/
│   │   ├── location_*.jpg     # Venue images
│   │   └── watoto_logo.png    # Watoto Church logo
│   └── js/
│       ├── admin-dashboard.js # Admin dashboard functionality
│       ├── admin-login.js     # Admin login functionality
│       ├── auth.js           # User authentication
│       ├── dashboard.js      # User dashboard functionality
│       └── script.js         # Main website functionality
├── admin-dashboard.html       # Admin dashboard page
├── admin-login.html          # Admin login page
├── dashboard.html            # User dashboard page
├── index.html                # Main landing page
├── login.html                # User login page
├── register.html             # User registration page
└── README.md                 # This file
```

## 🎨 Features

### User Interface
- **Landing Page** (`index.html`) - Beautiful hero section with wedding banns
- **Registration** (`register.html`) - User account creation
- **Login** (`login.html`) - User authentication
- **Dashboard** (`dashboard.html`) - User booking management

### Admin Interface
- **Admin Login** (`admin-login.html`) - Administrative access
- **Admin Dashboard** (`admin-dashboard.html`) - Complete management system
  - Booking management (approve/reject/edit)
  - Venue management
  - User management
  - Reports and analytics
  - System settings

## 🎯 Design Highlights

### Color Scheme
- **Primary**: #64017f (Watoto Purple)
- **Secondary**: #8b4a9c (Light Purple)
- **Accent**: #fc5cfe (Pink) - Used sparingly
- **Background**: #f8f9fa (Light Gray)

### Typography
- **Primary Font**: Playfair Display (Headings)
- **Secondary Font**: Montserrat, Inter (Body text)

### Key Components
- **Split Hero Layout** with wedding banns
- **Responsive Design** for all devices
- **Modern UI Elements** with cards and gradients
- **Interactive Elements** with hover effects
- **Status Indicators** for bookings and venues

## 🔧 Technical Features

### Frontend
- **Responsive Design** using CSS Grid and Flexbox
- **Modern CSS** with custom properties and animations
- **Font Awesome Icons** for visual elements
- **Google Fonts** integration

### JavaScript Functionality
- **Navigation** with smooth scrolling
- **Form Validation** and submission handling
- **Admin Dashboard** with CRUD operations
- **Real-time Updates** and notifications
- **Local Storage** for session management

### Authentication
- **User Login/Registration** system
- **Admin Portal** with role-based access
- **Session Management** with remember me functionality

## 🚀 Getting Started

1. **Setup Environment**
   - Place template files in your web server directory
   - Ensure PHP/Apache is running (for MAMP/XAMPP)

2. **Access the System**
   - Open `index.html` in your browser
   - Navigate through the user interface
   - Access admin portal via footer link

3. **Admin Access**
   - Username: `admin`
   - Password: `admin123`

## 📱 Responsive Breakpoints

- **Desktop**: 1200px+
- **Tablet**: 768px - 1199px
- **Mobile**: < 768px

## 🎪 Customization

### Colors
Modify the CSS custom properties in `:root` to change the color scheme:
```css
:root {
    --primary-color: #64017f;
    --secondary-color: #8b4a9c;
    /* ... other colors */
}
```

### Fonts
Update the Google Fonts link and CSS font families to change typography.

### Content
- Update venue information in the HTML files
- Replace placeholder images with actual venue photos
- Modify contact information and branding

## 🔗 Dependencies

- **Font Awesome 6.0.0** - Icons
- **Google Fonts** - Playfair Display, Montserrat, Inter
- **Modern Browser** - CSS Grid and Flexbox support

## 📝 Notes

- This is a frontend template with simulated backend functionality
- For production use, integrate with a proper backend system
- All forms use client-side validation and local storage
- Admin functions demonstrate UI patterns for real implementation

## 🎉 Created for Watoto Church

This template was specifically designed for Watoto Church's wedding booking needs, incorporating their official branding and colors while providing a modern, user-friendly interface for both couples and administrators.

---

*Template created: July 2025*
*Version: 1.0*
