<?= $this->extend('layouts/admin/base') ?>

<?= $this->section('styles') ?>
    <link href="<?= base_url('css/admin.css') ?>" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>

    <style>
    /* Bookings Page Styling */
    .bookings-table-container {
        background: var(--white);
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .table-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 25px;
        border-bottom: 1px solid var(--border-color);
        background: linear-gradient(135deg, #f8f9fa, #ffffff);
    }

    .table-header h3 {
        font-family: var(--font-primary);
        font-size: 1.4rem;
        color: var(--primary-color);
        margin: 0;
    }

    .table-tools {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .datatable-controls {
        display: flex;
        gap: 10px;
    }

    .datatable-controls .btn {
        padding: 6px 12px;
        font-size: 0.85rem;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .datatable-controls .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .search-box {
        position: relative;
    }

    .search-box input {
        padding: 8px 40px 8px 12px;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        font-size: 0.9rem;
        width: 250px;
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(64, 99, 240, 0.1);
        outline: none;
    }

    .search-box i {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray);
    }

    /* Filter Panel */
    .filter-panel {
        background: #f8f9fa;
        border-bottom: 1px solid var(--border-color);
        padding: 20px 25px;
    }

    .filter-content {
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-group label {
        font-weight: 500;
        color: var(--dark-gray);
        margin: 0;
    }

    .filter-group select,
    .filter-group input {
        padding: 6px 10px;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        font-size: 0.85rem;
    }

    /* Admin Table Styling */
    .admin-table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }

    .admin-table thead th {
        background: #f8f9fa;
        padding: 15px 12px;
        text-align: left;
        font-weight: 600;
        color: var(--dark-gray);
        border-bottom: 2px solid var(--border-color);
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .admin-table tbody td {
        padding: 15px 12px;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }

    .booking-row:hover {
        background-color: #f8f9fa;
    }

    .booking-id {
        color: var(--white);
        font-weight: 600;
    }

    .couple-names {
        font-weight: 500;
        color: var(--dark-gray);
    }

    .campus-badge {
        background: linear-gradient(135deg, var(--primary-color), var(--light-purple));
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .date-info strong {
        display: block;
        color: var(--dark-gray);
        font-weight: 500;
    }

    .date-info small {
        color: var(--gray);
        font-size: 0.8rem;
    }

    /* Status Badges */
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        text-transform: capitalize;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }

    .status-approved {
        background: #d1edff;
        color: #155724;
        border: 1px solid #b8e6b8;
    }

    .status-rejected {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f1aeb5;
    }

    .status-completed {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .status-cancelled {
        background: #e2e3e5;
        color: #383d41;
        border: 1px solid #d6d8db;
    }

    .status-unknown {
        background: #f8f9fa;
        color: #6c757d;
        border: 1px solid #dee2e6;
    }

    .contact-info {
        font-size: 0.85rem;
    }

    .contact-info small {
        display: block;
        color: var(--dark-gray);
    }

    .contact-info .email {
        color: var(--gray);
        font-size: 0.8rem;
    }

    .created-date {
        color: var(--gray);
        font-size: 0.85rem;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 6px;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        font-size: 0.85rem;
    }

    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .btn-action.view {
        background: linear-gradient(135deg, #17a2b8, #138496);
        color: white;
    }

    .btn-action.approve {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }

    .btn-action.reject {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
    }

    .btn-action.cancel {
        background: linear-gradient(135deg, #ffc107, #fd7e14);
        color: #212529;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--gray);
    }

    .empty-state i {
        font-size: 3rem;
        color: var(--light-gray);
        margin-bottom: 20px;
    }

    .empty-state h3 {
        font-size: 1.5rem;
        color: var(--dark-gray);
        margin-bottom: 10px;
    }

    .empty-state p {
        font-size: 1rem;
        margin-bottom: 0;
    }

    .no-data {
        padding: 0 !important;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .table-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .filter-content {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .search-box input {
            width: 100%;
        }
        
        .admin-table {
            font-size: 0.85rem;
        }
        
        .admin-table thead th,
        .admin-table tbody td {
            padding: 10px 8px;
        }
        
        .action-buttons {
            flex-wrap: wrap;
        }
    }

    /* Admin Modal Styling */
    .admin-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        animation: fadeIn 0.3s ease forwards;
    }

    .modal-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    .modal-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow: hidden;
        position: relative;
        transform: scale(0.9);
        animation: scaleIn 0.3s ease forwards;
    }

    .modal-header {
        background: linear-gradient(135deg, var(--primary-gradient));
        color: black;
        padding: 1.5rem 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modal-close {
        background: none;
        border: none;
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 50%;
        transition: background-color 0.2s ease;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-close:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .modal-body {
        padding: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: var(--dark-gray);
        margin-bottom: 0.5rem;
    }

    .form-input {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        resize: vertical;
        font-family: inherit;
    }

    .form-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(64, 99, 240, 0.1);
        outline: none;
    }

    .form-help {
        display: block;
        color: var(--gray);
        font-size: 0.8rem;
        margin-top: 0.5rem;
    }

    .modal-footer {
        background: #f8f9fa;
        padding: 1.5rem 2rem;
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        border-top: 1px solid #e9ecef;
    }

    .modal-footer .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modal-footer .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-danger {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
    }

    .btn-warning {
        background: linear-gradient(135deg, #ffc107, #fd7e14);
        color: #212529;
    }

    @keyframes fadeIn {
        to {
            opacity: 1;
        }
    }

    @keyframes scaleIn {
        to {
            transform: scale(1);
        }
    }

    /* Responsive Modal */
    @media (max-width: 768px) {
        .modal-container {
            width: 95%;
            margin: 1rem;
        }
        
        .modal-header,
        .modal-body,
        .modal-footer {
            padding: 1.5rem;
        }
        
        .modal-footer {
            flex-direction: column;
        }
        
        .modal-footer .btn {
            width: 100%;
            justify-content: center;
        }
    }

    /* DataTable Customizations - Minimal styling to preserve functionality */
    .dataTables_wrapper {
        padding: 20px 25px;
    }

    .dataTables_length,
    .dataTables_filter {
        margin-bottom: 15px;
    }

    .dataTables_info {
        padding-top: 15px;
    }

    .dataTables_paginate {
        padding-top: 15px;
    }

    /* Remove custom pagination styling - use DataTable defaults */

    /* DataTable responsive adjustments */
    @media (max-width: 768px) {
        .dataTables_wrapper {
            padding: 15px;
        }
        
        .dataTables_length {
            margin-bottom: 10px;
        }
        
        .dataTables_info,
        .dataTables_paginate {
            text-align: center;
            padding-top: 10px;
        }
    }

    /* Keep only essential table sorting indicators */
    .admin-table th.sorting:after,
    .admin-table th.sorting_asc:after,
    .admin-table th.sorting_desc:after {
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        opacity: 0.5;
        position: relative;
        top: 0;
        right: 0;
        margin-left: 8px;
    }

    .admin-table th.sorting:after {
        content: '\f0dc';
    }

    .admin-table th.sorting_asc:after {
        content: '\f0de';
        opacity: 1;
    }

    .admin-table th.sorting_desc:after {
        content: '\f0dd';
        opacity: 1;
    }
    </style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <?= $this->include('partials/admin_sidebar') ?>
    
    <div class="main-content">
        <?= $this->include('partials/admin_header', ['pageTitle' => $title ?? 'Dashboard']) ?>
        
        <!-- Flash Messages -->
        <?= $this->include('partials/flash_messages') ?>
        
        <!-- Page Content -->
        <main class="content-area">
            <div class="admin-page">
                <?= $this->renderSection('main_content') ?>
            </div>
        </main>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="<?php echo base_url('js/admin-dashboard.js') ?>"></script>
<?= $this->endSection() ?>
