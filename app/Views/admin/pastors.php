<?= $this->extend('admin_template/layout') ?>

<?= $this->section('content') ?>
<?php
$pageActions = '
    <a href="' . site_url('admin/pastor/new') . '" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Add New Pastor
    </a>
';
?>
<?= $this->include('admin_template/partials/page_header', [
    'title' => 'Pastor Management',
    'subtitle' => 'Manage pastor information and availability',
    'actions' => $pageActions
]) ?>

<!-- Pastors Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Pastors</h3>
    </div>
    <div class="card-body">
        <div class="table-wrapper">
            <table id="pastorsTable" class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Campus</th>
                        <th>Contact</th>
                        <th>Specialization</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pastors)): ?>
                        <?php foreach ($pastors as $pastor): ?>
                        <tr>
                            <td><?= esc($pastor['id']) ?></td>
                            <td>
                                <strong><?= esc($pastor['name']) ?></strong>
                                <br>
                                <small class="text-muted">ID: <?= esc($pastor['id']) ?></small>
                            </td>
                            <td>
                                <span class="badge badge-info"><?= esc($pastor['campus_name'] ?? 'No Campus') ?></span>
                            </td>
                            <td>
                                <div style="font-size: 12px;">
                                    <i class="fas fa-phone"></i> <?= esc($pastor['phone']) ?><br>
                                    <i class="fas fa-envelope"></i> <?= esc($pastor['email']) ?>
                                </div>
                            </td>
                            <td><?= esc($pastor['specialization'] ?? 'General') ?></td>
                            <td>
                                <?php if ($pastor['is_available']): ?>
                                    <span class="badge badge-success">Available</span>
                                    <br>
                                    <button type="button" class="btn btn-xs btn-outline-warning mt-1" 
                                            onclick="toggleAvailability(<?= $pastor['id'] ?>, 0)" 
                                            title="Mark as Unavailable">
                                        <i class="fas fa-toggle-on"></i>
                                    </button>
                                <?php else: ?>
                                    <span class="badge badge-warning">Unavailable</span>
                                    <br>
                                    <button type="button" class="btn btn-xs btn-outline-success mt-1" 
                                            onclick="toggleAvailability(<?= $pastor['id'] ?>, 1)" 
                                            title="Mark as Available">
                                        <i class="fas fa-toggle-off"></i>
                                    </button>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $actions = [
                                    ['type' => 'edit', 'icon' => 'fa-edit', 'title' => 'Edit', 'url' => site_url('admin/pastor/' . $pastor['id'] . '/edit')],
                                    ['type' => 'delete', 'icon' => 'fa-trash', 'title' => 'Delete', 'onclick' => 'deletePastor(' . $pastor['id'] . ')']
                                ];
                                ?>
                                <?= $this->include('admin_template/partials/action_buttons', ['actions' => $actions]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No pastors found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
#pastorsTable td {
    vertical-align: middle;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('#pastorsTable').DataTable({
        responsive: true,
        autoWidth: false,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        order: [[1, 'asc']],
        columnDefs: [
            {
                targets: [6],
                orderable: false,
                searchable: false
            },
            {
                targets: [5],
                orderable: true,
                searchable: false
            }
        ],
        language: {
            search: "Search pastors:",
            lengthMenu: "Show _MENU_ pastors per page",
            info: "Showing _START_ to _END_ of _TOTAL_ pastors",
            infoEmpty: "No pastors found",
            infoFiltered: "(filtered from _MAX_ total pastors)",
            zeroRecords: "No pastors match your search criteria",
            emptyTable: "No pastors have been added yet"
        },
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
    });
});

function deletePastor(id) {
    if (confirm('Are you sure you want to delete this pastor? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?= site_url('admin/pastor/') ?>${id}`;
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        
        form.appendChild(methodInput);
        form.appendChild(csrfInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function toggleAvailability(pastorId, newStatus) {
    if (confirm(`Are you sure you want to ${newStatus ? 'mark this pastor as available' : 'mark this pastor as unavailable'}?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?= site_url('admin/pastor/') ?>${pastorId}/toggle-availability`;
        
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'is_available';
        statusInput.value = newStatus;
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        
        form.appendChild(statusInput);
        form.appendChild(csrfInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<?= $this->endSection() ?>
