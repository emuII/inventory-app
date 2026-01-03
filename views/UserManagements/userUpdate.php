<form id="userEdit" method="post" onsubmit="return false;">
    <div class="container-fluid py-4">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Edit User</h1>
                    <p class="mb-0 opacity-75">Manage user</p>
                </div>
            </div>
        </div>
        <div class="container-section">
            <div class="search-section">
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label">Username</label>
                        <input type="text" placeholder="Username" required class="form-control" value="<?= htmlspecialchars($userDetail['username']) ?>" name="username">
                    </div>
                </div>
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label">Full Name</label>
                        <input type="text" placeholder="Full Name" required class="form-control" value="<?= $userDetail["full_name"] ?>" name="full_name">
                    </div>
                </div>
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label">Email</label>
                        <input type="text" placeholder="Email" required class="form-control" value="<?= $userDetail["email"] ?>" name="email">
                    </div>
                </div>
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label">Role</label>
                        <select class="form-control select2" name="role" required>
                            <option value="0"></option>
                            <option value="requestor" <?= ($userDetail['role'] === 'requestor')   ? 'selected' : '' ?>>Requestor</option>
                            <option value="super_admin" <?= ($userDetail['role'] === 'super_admin')   ? 'selected' : '' ?>>Super Admin</option>
                            <option value="approval" <?= ($userDetail['role'] === 'approval')   ? 'selected' : '' ?>>Approval</option>
                            <option value="cashier" <?= ($userDetail['role'] === 'cashier')   ? 'selected' : '' ?>>Cashier</option>
                            <option value="warehouse" <?= ($userDetail['role'] === 'warehouse')   ? 'selected' : '' ?>>Warehouse</option>
                        </select>
                    </div>
                </div>
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label">Status</label>
                        <select class="form-control select2" name="user_status">
                            <option value="0"></option>
                            <?php foreach ($helper as $status) : ?>
                                <?php if (in_array($status['name'], ['Active', 'inActive'])) : ?>
                                    <option value="<?= $status['value'] ?>"
                                        <?= ($userDetail['status'] == $status['value']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($status['name']) ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>

                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-modern" type="button" onclick="history.back();">
                        <i class="fa-solid fa-angle-left"></i>Close
                    </button>&nbsp;
                    <button class="btn btn-primary-modern btn-modern" type="submit">Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $("#userEdit").on("submit", function(e) {
            e.preventDefault();
            submitForm();
        });
    });

    function submitForm() {
        const data = {
            userId: <?= json_encode($userDetail['id']) ?>,
            username: $('input[name="username"]').val(),
            full_name: $('input[name="full_name"]').val(),
            email: $('input[name="email"]').val(),
            role: $('select[name="role"]').val(),
            user_status: $('select[name="user_status"]').val(),
        };

        $.ajax({
            type: "POST",
            url: "middleware/ajax_handler.php?controller=userManagement&action=UpdateUser",
            contentType: "application/json",
            data: JSON.stringify(data),
            success: function(response) {
                const res = JSON.parse(response);
                if (res.ok) {
                    Swal.fire({
                        title: "Success!",
                        text: res.message,
                        icon: "success",
                    }).then(() => {
                        window.location = "/inventory-app/index.php?route=userManagements/index&success=1";
                    });
                } else {
                    alert("Failed to add user. Please fill all required fields.");
                }
            },
            error: function(err) {
                alert("Error submitting form");
            }
        });
    }
</script>