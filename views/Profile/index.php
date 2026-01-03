<div class="container-fluid py-4">
    <div class="page-header">
        <h1>My Profile</h1>
        <p>Manage your profile information and security settings</p>
    </div>

    <div class="profile-container">
        <!-- Profile Information Card -->
        <div class="card">
            <div class="card-header">
                <h2>Profile Information</h2>
                <p>Your personal details</p>
            </div>
            <div class="card-body">
                <div class="profile-info">
                    <div class="avatar">
                        <?php
                        $initials = '';
                        foreach (explode(' ', trim($userDetail['username'])) as $word) {
                            $initials .= strtoupper($word[0]);
                        }
                        echo $initials;
                        ?>
                    </div>

                    <div class="user-details">
                        <h3><?= $userDetail['full_name'] ?></h3>
                        <p><?= $userDetail['email'] ?></p>
                        <span class="status-badge role-<?= $userDetail['role'] ?>"><?= ucfirst($userDetail['role']) ?></span>
                        <div class="status-badge status-active">● <?= $userDetail['statusName'] ?></div>
                    </div>
                </div>

                <form id="profileForm" method="post" onsubmit="return false;">
                    <div class="form-group">
                        <label class="form-label" for="username">Username</label>
                        <input type="text" class="form-control" id="username" value="<?= $userDetail['username'] ?>" disabled>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="full_name">Full Name</label>
                        <input type="text" class="form-control" id="full_name" value="<?= $userDetail['full_name'] ?>" disabled>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Email Address</label>
                        <input type="email" class="form-control" id="email" value="<?= $userDetail['email'] ?>" disabled>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="role">Role</label>
                        <input type="text" class="form-control" id="role" value="<?= $userDetail['role'] ?>" disabled>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="status">Status</label>
                        <input type="text" class="form-control" id="status" value="<?= $userDetail['statusName'] ?>" disabled>
                    </div>
                </form>
            </div>
        </div>

        <!-- Change Password Card -->
        <div class="card">
            <div class="card-header">
                <h2>Change Password</h2>
                <p>Update your password for security</p>
            </div>
            <div class="card-body">
                <form id="passwordForm" method="post" onsubmit="return false;">
                    <div class="form-group">
                        <label class="form-label" for="current_password">Current Password</label>
                        <input type="password" class="form-control" id="current_password" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="new_password">New Password</label>
                        <input type="password" class="form-control" id="new_password" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="confirm_password">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirm_password" required>
                    </div>

                    <div class="btn-group">
                        <button type="button" class="btn btn-outline" id="btnCancelPassword">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#passwordForm").on("submit", function(e) {
            e.preventDefault();
            submitForm();
        });
    });

    function submitForm() {
        const currentPassword = $("#current_password").val().trim();
        const newPassword = $("#new_password").val().trim();
        const confirmPassword = $("#confirm_password").val().trim();

        if (newPassword !== confirmPassword) {
            alert("New password and confirmation do not match.");
            return;
        }

        const payload = {
            userId: <?= json_encode($userDetail['id']) ?>,
            current_password: currentPassword,
            new_password: newPassword
        };

        $.ajax({
            url: 'middleware/ajax_handler.php?controller=userManagement&action=ChangePassword',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(payload),
            success: function(response) {
                const res = JSON.parse(response);
                if (res.ok) {
                    Swal.fire({
                        title: "Success!",
                        text: res.message + " Silahkan login kembali.",
                        icon: "success",
                    }).then(() => {
                        window.location.href = "logout.php";
                    });
                    $("#passwordForm")[0].reset();
                } else {
                    alert("Error: " + res.message);
                }
            },
            error: function() {
                alert("An error occurred while updating the password.");
            }
        });
    }
</script>