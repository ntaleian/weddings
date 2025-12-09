<?= $this->extend('admin_template/layout') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <h1 class="page-title">Example List Page</h1>
    <p class="page-subtitle">This is an example of a list/table page with filters and actions</p>
    <div class="page-actions">
        <button class="btn btn-primary" onclick="showModal('addModal')">
            <i class="fas fa-plus"></i> Add New
        </button>
        <button class="btn btn-secondary">
            <i class="fas fa-download"></i> Export
        </button>
    </div>
</div>

<!-- Filter Panel -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Filters</h3>
    </div>
    <div class="card-body">
        <div class="filter-row">
            <div class="form-group">
                <label class="form-label">Status</label>
                <select class="form-control">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Date From</label>
                <input type="date" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">Date To</label>
                <input type="date" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">&nbsp;</label>
                <button class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-filter"></i> Apply Filters
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Items</h3>
        <div class="card-actions">
            <button class="btn btn-sm btn-secondary">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-wrapper">
            <table class="data-table" id="exampleTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#001</td>
                        <td>Sample Item 1</td>
                        <td><span class="badge badge-success">Active</span></td>
                        <td>Dec 7, 2025</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-action view" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-action edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-action delete" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>#002</td>
                        <td>Sample Item 2</td>
                        <td><span class="badge badge-warning">Pending</span></td>
                        <td>Dec 6, 2025</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-action view" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-action edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-action delete" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal-overlay" id="addModal" style="display: none;">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Add New Item</h3>
            <button class="modal-close" onclick="closeModal('addModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="addForm">
                <div class="form-group">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" rows="4"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select class="form-control" required>
                        <option value="">Select Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('addModal')">Cancel</button>
            <button class="btn btn-primary" onclick="validateForm('addForm')">Save</button>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('#exampleTable').DataTable();
});
</script>
<?= $this->endSection() ?>

