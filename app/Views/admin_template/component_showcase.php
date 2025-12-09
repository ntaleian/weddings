<?= $this->extend('admin_template/layout') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <h1 class="page-title">Component Showcase</h1>
    <p class="page-subtitle">All available components and design elements</p>
</div>

<!-- Color Palette -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Color Palette</h3>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div>
                <div style="width: 100%; height: 80px; background: var(--primary); border-radius: 10px; margin-bottom: 0.5rem;"></div>
                <strong>Primary</strong><br>
                <small class="text-muted">#25802D</small>
            </div>
            <div>
                <div style="width: 100%; height: 80px; background: var(--accent); border-radius: 10px; margin-bottom: 0.5rem;"></div>
                <strong>Accent</strong><br>
                <small class="text-muted">#64017f</small>
            </div>
            <div>
                <div style="width: 100%; height: 80px; background: var(--success); border-radius: 10px; margin-bottom: 0.5rem;"></div>
                <strong>Success</strong><br>
                <small class="text-muted">#10b981</small>
            </div>
            <div>
                <div style="width: 100%; height: 80px; background: var(--warning); border-radius: 10px; margin-bottom: 0.5rem;"></div>
                <strong>Warning</strong><br>
                <small class="text-muted">#f59e0b</small>
            </div>
            <div>
                <div style="width: 100%; height: 80px; background: var(--danger); border-radius: 10px; margin-bottom: 0.5rem;"></div>
                <strong>Danger</strong><br>
                <small class="text-muted">#ef4444</small>
            </div>
            <div>
                <div style="width: 100%; height: 80px; background: var(--info); border-radius: 10px; margin-bottom: 0.5rem;"></div>
                <strong>Info</strong><br>
                <small class="text-muted">#3b82f6</small>
            </div>
        </div>
    </div>
</div>

<!-- Buttons -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Buttons</h3>
    </div>
    <div class="card-body">
        <div style="display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
            <button class="btn btn-primary">Primary</button>
            <button class="btn btn-secondary">Secondary</button>
            <button class="btn btn-success">Success</button>
            <button class="btn btn-danger">Danger</button>
            <button class="btn btn-warning">Warning</button>
            <button class="btn btn-info">Info</button>
        </div>
        <div style="display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
            <button class="btn btn-sm btn-primary">Small</button>
            <button class="btn btn-primary">Default</button>
            <button class="btn btn-lg btn-primary">Large</button>
        </div>
        <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
            <button class="btn btn-primary">
                <i class="fas fa-plus"></i> With Icon
            </button>
            <button class="btn-icon btn-primary">
                <i class="fas fa-cog"></i>
            </button>
        </div>
    </div>
</div>

<!-- Status Badges -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Status Badges</h3>
    </div>
    <div class="card-body">
        <div style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center;">
            <span class="badge badge-success">Success</span>
            <span class="badge badge-warning">Warning</span>
            <span class="badge badge-danger">Danger</span>
            <span class="badge badge-info">Info</span>
            <span class="badge badge-primary">Primary</span>
            <span class="badge badge-secondary">Secondary</span>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Stats Cards</h3>
    </div>
    <div class="card-body">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
                <h3 class="stat-value">1,234</h3>
                <p class="stat-label">Total Bookings</p>
                <span class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> 12% increase
                </span>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <h3 class="stat-value">45</h3>
                <p class="stat-label">Pending</p>
                <span class="stat-change">Requires attention</span>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <h3 class="stat-value">892</h3>
                <p class="stat-label">Approved</p>
                <span class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> 8% increase
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Forms -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Form Elements</h3>
    </div>
    <div class="card-body">
        <form>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Text Input</label>
                    <input type="text" class="form-control" placeholder="Enter text...">
                    <small class="form-help">This is help text</small>
                </div>
                <div class="form-group">
                    <label class="form-label">Select Dropdown</label>
                    <select class="form-control">
                        <option>Option 1</option>
                        <option>Option 2</option>
                        <option>Option 3</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Textarea</label>
                <textarea class="form-control" rows="4" placeholder="Enter description..."></textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-secondary">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Alerts -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Alert Messages</h3>
    </div>
    <div class="card-body">
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            This is a success message
        </div>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            This is an error message
        </div>
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            This is a warning message
        </div>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            This is an info message
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Action Buttons</h3>
    </div>
    <div class="card-body">
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
    </div>
</div>

<!-- Table Example -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Table</h3>
    </div>
    <div class="card-body">
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#001</td>
                        <td>Sample Item</td>
                        <td><span class="badge badge-success">Active</span></td>
                        <td>Dec 7, 2025</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-action view"><i class="fas fa-eye"></i></button>
                                <button class="btn-action edit"><i class="fas fa-edit"></i></button>
                                <button class="btn-action delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>#002</td>
                        <td>Another Item</td>
                        <td><span class="badge badge-warning">Pending</span></td>
                        <td>Dec 6, 2025</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-action view"><i class="fas fa-eye"></i></button>
                                <button class="btn-action edit"><i class="fas fa-edit"></i></button>
                                <button class="btn-action delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Example -->
<button class="btn btn-primary" onclick="showModal('exampleModal')">
    <i class="fas fa-eye"></i> View Modal Example
</button>

<div class="modal-overlay" id="exampleModal" style="display: none;">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Example Modal</h3>
            <button class="modal-close" onclick="closeModal('exampleModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <p>This is an example modal dialog. It has a header, body, and footer.</p>
            <div class="form-group">
                <label class="form-label">Example Input</label>
                <input type="text" class="form-control" placeholder="Enter value...">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('exampleModal')">Cancel</button>
            <button class="btn btn-primary">Save Changes</button>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

