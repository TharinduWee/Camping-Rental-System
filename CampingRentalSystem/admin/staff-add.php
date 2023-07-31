<?php require_once('header.php'); ?>

<?php
if (isset($_POST['form1'])) {
    $valid = 1;

    if (empty($_POST['full_name'])) {
        $valid = 0;
        $error_message .= 'Full Name can not be empty<br>';
    }

    if (empty($_POST['email'])) {
        $valid = 0;
        $error_message .= 'Email can not be empty<br>';
    }

    if (empty($_POST['phone'])) {
        $valid = 0;
        $error_message .= 'Phone can not be empty<br>';
    }

    if (empty($_POST['role'])) {
        $valid = 0;
        $error_message .= 'Role can not be empty<br>';
    }

    if (empty($_POST['status'])) {
        $valid = 0;
        $error_message .= 'Status can not be empty<br>';
    }

    if (empty($_POST['password'])) {
        $valid = 0;
        $error_message .= 'Password can not be empty<br>';
    }

    if ($valid == 1) {
        // Hash the password before saving it in the database
        $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $statement = $pdo->prepare("INSERT INTO tbl_user (full_name, email, phone, role, status, password) VALUES (?, ?, ?, ?, ?, ?)");
        $statement->execute(array($_POST['full_name'], $_POST['email'], $_POST['phone'], $_POST['role'], $_POST['status'], $hashed_password));

        $success_message = 'Staff is added successfully!';

        unset($_POST['full_name']);
        unset($_POST['email']);
        unset($_POST['phone']);
        unset($_POST['role']);
        unset($_POST['status']);
        unset($_POST['password']);
    }
}
?>

<section class="content-header">
    <div class="content-header-left">
        <h1>Add Staff</h1>
    </div>
    <div class="content-header-right">
        <a href="staff.php" class="btn btn-primary btn-sm">View All</a>
    </div>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">

            <?php if ($error_message): ?>
                <div class="callout callout-danger">
                    <p><?php echo $error_message; ?></p>
                </div>
            <?php endif; ?>

            <?php if ($success_message): ?>
                <div class="callout callout-success">
                    <p><?php echo $success_message; ?></p>
                </div>
            <?php endif; ?>

            <form class="form-horizontal" action="" method="post">
                <div class="box box-info">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="full_name" class="col-sm-2 control-label">Full Name <span>*</span></label>
                            <div class="col-sm-6">
                                <input type="text" autocomplete="off" class="form-control" name="full_name"
                                       value="<?php if (isset($_POST['full_name'])) {
                                           echo $_POST['full_name'];
                                       } ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-sm-2 control-label">Email <span>*</span></label>
                            <div class="col-sm-6">
                                <input type="email" class="form-control" name="email"
                                       value="<?php if (isset($_POST['email'])) {
                                           echo $_POST['email'];
                                       } ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="phone" class="col-sm-2 control-label">Phone <span>*</span></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="phone"
                                       value="<?php if (isset($_POST['phone'])) {
                                           echo $_POST['phone'];
                                       } ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="role" class="col-sm-2 control-label">Role <span>*</span></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="role"
                                       value="<?php if (isset($_POST['role'])) {
                                           echo $_POST['role'];
                                       } ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status" class="col-sm-2 control-label">Status <span>*</span></label>
                            <div class="col-sm-6">
                                <select class="form-control" name="status">
                                    <option value="Active" <?php if (isset($_POST['status']) && $_POST['status'] === 'Active') {
                                        echo 'selected';
                                    } ?>>Active
                                    </option>
                                    <option value="Inactive" <?php if (isset($_POST['status']) && $_POST['status'] === 'Inactive') {
                                        echo 'selected';
                                    } ?>>Inactive
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-sm-2 control-label">Password <span>*</span></label>
                            <div class="col-sm-6">
                                <input type="password" class="form-control" name="password" required>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-success pull-right" name="form1">Add Staff</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<?php require_once('footer.php'); ?>
