<?php include 'db_connect.php' ?>

<div class="row">
    <!-- Form Section -->
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <strong>Add New Employee</strong>
            </div>
            <div class="card-body">
                <form action="" id="manage-employee">
                    <input type="hidden" name="employee_id"
                        value="<?php echo isset($employee_id) ? $employee_id : '' ?>">
                    <div class="form-group">
                        <label for="" class="text-normal font-weight-normal">Employee ID No.</label>
                        <input type="text" name="eIDno" class="form-control form-control-sm"
                            value="<?php echo isset($eIDno) ? $eIDno : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="category">Employee Type</label>
                        <select class="form-control" name="empType" id="empType" required>
                            <option value="faculty" <?php echo (isset($empType) == 'faculty') ? 'selected' : ''; ?>>
                                Faculty
                            </option>
                            <option value="staff" <?php echo (isset($empType) == 'staff') ? 'selected' : ''; ?>>Staff
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="" class="text-normal font-weight-normal">Faculty Prefix Name</label>
                        <input type="text" name="preName" class="form-control form-control-sm"
                            value="<?php echo isset($preName) ? $preName : '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="" class="text-normal font-weight-normal">Faculty Last Name</label>
                        <input type="text" name="lName" class="form-control form-control-sm"
                            value="<?php echo isset($lName) ? $lName : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="" class="text-normal font-weight-normal">Faculty First Name</label>
                        <input type="text" name="fName" class="form-control form-control-sm"
                            value="<?php echo isset($fName) ? $fName : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="" class="text-normal font-weight-normal">Faculty Middle Name</label>
                        <input type="text" name="mName" class="form-control form-control-sm"
                            value="<?php echo isset($mName) ? $mName : '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="" class="text-normal font-weight-normal">Faculty Suffix Name</label>
                        <input type="text" name="sName" class="form-control form-control-sm"
                            value="<?php echo isset($sName) ? $sName : '' ?>">
                    </div>

                    <div class="d-flex justify-content-end w-100">
                        <button class="btn btn-sm btn-success btn-flat mx-1"><i class="fa fa-save"></i> Save
                            Employee</button>
                        <a href="./index.php?page=categories_list"
                            class="btn btn-sm btn-secondary btn-flat mx-1">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Table List Section -->
    <div class="col-sm">
        <div class="card">
            <div class="card-header">
                <strong>Employee List</strong>
            </div>
            <div class="card-body">

                <table class="table table-hover table-bordered" id="list">
                    <colgroup>
                        <col width="5%">
                        <col width="10%">
                        <col width="40%">
                        <col width="15%">
                        <col width="15%">
                        <col width="15%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Employee ID No</th>
                            <th>Employee Name</th>
                            <th>Type</th>
                            <th>Date Added</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        $qry = $conn->query("SELECT `employee_id`, `eIDno`, `empType`, `preName`, `lName`, `fName`, `mName`, `sName`, `created_at` FROM `employees` ORDER BY created_at DESC");

                        if ($qry->num_rows > 0) {
                            while ($row = $qry->fetch_assoc()):
                                $fullname = $row['preName'] . ' ' . $row['fName'] . ' ' . $row['mName'] . ' ' . $row['lName'] . ', ' . $row['sName'];
                                ?>
                                <tr>
                                    <th class="text-center"><?php echo $i++ ?></th>
                                    <td><?php echo $row['eIDno'] ?></td>
                                    <td><?php echo $fullname ?></td>
                                    <td class="text-uppercase"><?php echo $row['empType'] ?></td>
                                    <td><?php echo date("M d, Y h:i A", strtotime($row['created_at'])) ?></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <?php $hashed_link = hash('sha256', $row['employee_id']); ?>
                                            <a class="btn btn-warning btn-flat btn-sm"
                                                href="./index.php?page=edit_employee&employee_id=<?php echo $hashed_link ?>">Edit</a>
                                            <button type="button" class="btn btn-danger btn-flat btn-sm delete-employee"
                                                data-id="<?php echo $row['employee_id'] ?>">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile;
                        } else {
                            echo '<tr class="text-center">
                                <td colspan="6">No Employees found in Database!</td></tr>';
                        } ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

</div>


<script>
    $(document).ready(function () {
        $('#list').dataTable()
        $('.delete-employee').click(function () {
            _conf("Are you sure to delete this employee?", "delete_employee", [$(this).attr('data-id')])
        })




    })
    function delete_employee($cat_id) {
        start_load()
        $.ajax({
            url: 'ajax.php?action=delete_employee',
            method: 'POST',
            data: { cat_id: $cat_id },
            success: function (resp) {
                if (resp == 1) {
                    alert_toast("Data successfully deleted", 'success')
                    setTimeout(function () {
                        location.reload()
                    }, 1500)

                }
            }
        })
    }


    $('#manage-employee').submit(function (e) {
        e.preventDefault();
        start_load()
        $('#msg').html('')
        $.ajax({
            url: 'ajax.php?action=save_employee',
            method: 'POST',
            data: $(this).serialize(),
            success: function (resp) {
                if (resp == 1) {
                    alert_toast("Data successfully saved.", "success");
                    setTimeout(function () {
                        location.replace('index.php?page=employee_list')
                    }, 750)
                } else if (resp == 2) {
                    $('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Criteria already exist.</div>')
                    end_load()
                }
            }
        })
    })
</script>