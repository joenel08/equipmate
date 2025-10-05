<div class="row">
  <!-- Distribution Form -->
  <div class="col-md-4">
    <div class="card">
      <div class="card-header"><strong>Distribute Material</strong></div>
      <div class="card-body">
        <div id="msg"></div>
        <form action="" id="manage-distribution">
          <input type="hidden" name="distribution_id"
            value="<?php echo isset($distribution_id) ? $distribution_id : '' ?>">
          <div class="form-group">
            <label for="material">Material</label>
            <select class="form-control" name="material_id" id="material_id" required>
              <?php
              $i = 1;
              // Join categories_list to get category_name
              $qry = $conn->query("SELECT m.*
                        FROM materials_list m 
                        ORDER BY m.date_added ASC");


              if ($qry->num_rows > 0) {
                while ($row = $qry->fetch_assoc()):
                  $selected = (isset($material) && $material == $row['material_id']) ? 'selected' : '';
                  echo '<option value="' . $row['material_id'] . '" ' . $selected . '>' . $row['material_name'] . ' (' . $row['unit'] . ')</option>';
                endwhile;
              } else {
                echo '<option value="">No Category Added</option>';
              }
              ?>


            </select>
          </div>


          <div class="form-group">
            <label for="material">Employee Recipient</label>
            <select class="form-control" name="employee_id" id="employee_id" required>
              <?php
              $i = 1;
              // Join categories_list to get category_name
              $qry = $conn->query("SELECT *
                        FROM employees");


              if ($qry->num_rows > 0) {
                while ($row = $qry->fetch_assoc()):
                  $fullname = $row['preName'] . ' ' . $row['fName'] . ' ' . $row['mName'] . ' ' . $row['lName'] . ', ' . $row['sName'];
                  $selected = (isset($employee_id) && $employee_id == $row['employee_id']) ? 'selected' : '';
                  echo '<option value="' . $row['employee_id'] . '" ' . $selected . '>' . $fullname . '</option>';
                endwhile;
              } else {
                echo '<option value="">No Category Added</option>';
              }
              ?>


            </select>
          </div>

          <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="1">
          </div>
          <div class="d-flex justify-content-end w-100">
            <button class="btn btn-sm btn-success btn-flat mx-1"><i class="fa fa-save"></i> Distribute</button>
            <a href="./index.php?page=distribution_list" class="btn btn-sm btn-secondary btn-flat mx-1">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Distribution List -->
  <div class="col-md-8">
    <div class="card">
      <div class="card-header"><strong>Distribution List</strong></div>
      <div class="card-body table-responsive">
        <table class="table table-bordered table-striped" id="list">
          <thead>
            <tr>
              <th>#</th>
              <th>Date</th>
              <th>Material</th>
              <th>Recipient Type</th>
              <th>Recipient Name</th>
              <th>Quantity</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $i = 1;
            $qry = $conn->query("SELECT d.*, m.material_name, m.unit, 
                                      e.empType, e.preName, e.fName, e.mName, e.lName, e.sName
                                FROM distribution_list d
                                INNER JOIN materials_list m ON d.material_id = m.material_id
                                INNER JOIN employees e ON d.employee_id = e.employee_id
                                ORDER BY d.date_distributed DESC");

            if ($qry->num_rows > 0) {
              while ($row = $qry->fetch_assoc()):
                // Build full name
                $fullname = trim($row['preName'] . ' ' . $row['fName'] . ' ' . $row['mName'] . ' ' . $row['lName'] . ' ' . $row['sName']);
                ?>
                <tr>
                  <td class="text-center"><?php echo $i++; ?></td>
                  <td><?php echo date("Y-m-d H:i", strtotime($row['date_distributed'])); ?></td>
                  <td><?php echo $row['material_name'] ; ?></td>
                  <td><?php echo $row['empType']; ?></td>
                  <td><?php echo $fullname; ?></td>
                  <td><?php echo $row['quantity']. ' (' . $row['unit'] . ')'; ?></td>
                </tr>
              <?php endwhile;
            } else {
              echo '<tr><td colspan="6" class="text-center">No Distribution Records Found!</td></tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<script>
  $(document).ready(function () {

    $('#list').dataTable()
  })


  $('#manage-distribution').submit(function (e) {
    e.preventDefault();
    start_load()
    $('#msg').html('')
    $.ajax({
      url: 'ajax.php?action=save_distribution',
      method: 'POST',
      data: $(this).serialize(),
      success: function (resp) {
        if (resp == 1) {
          alert_toast("Data successfully saved.", "success");
          setTimeout(function () {
            location.replace('index.php?page=distribution_list')
          }, 750)
        } else if (resp == 2) {
          $('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Stock is not sufficient!</div>')
          end_load()
        }
      }
    })
  })
</script>