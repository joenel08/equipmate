<?php include 'db_connect.php' ?>
<style>
  .tag {
    background: #17a2b8;
    color: white;
    padding: 2px 8px;
    border-radius: 15px;
    display: inline-flex;
    align-items: center;
    font-size: 13px;
  }

  .tag .remove {
    margin-left: 6px;
    cursor: pointer;
    font-weight: bold;
  }
</style>
<div class="row">
  <!-- Form Section -->
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <strong>Add New Supplier</strong>
      </div>
      <div class="card-body">
        <form action="" id="manage-supplier">
          <input type="hidden" name="supplier_id" value="<?php echo isset($supplier_id) ? $supplier_id : '' ?>">
          <div class="form-group">
            <label for="" class="text-normal font-weight-normal">Supplier Name</label>
            <input type="text" name="supplier_name" class="form-control form-control-sm"
              value="<?php echo isset($supplier_name) ? $supplier_name : '' ?>">
          </div>
          <div class="form-group">
            <label for="" class="font-weight-normal">Supplier Address</label>
            <textarea name="supplier_address" id="" class="form-control" cols="30"
              rows="10"><?php echo isset($supplier_address) ? $supplier_address : '' ?></textarea>
          </div>


          <div class="d-flex justify-content-end w-100">
            <button class="btn btn-sm btn-success btn-flat mx-1"><i class="fa fa-save"></i> Save Category</button>
            <a href="./index.php?page=suppliers_list" class="btn btn-sm btn-secondary btn-flat mx-1">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Table List Section -->
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <strong>Suppliers List</strong>
      </div>
      <div class="card-body">


        <table class="table table-hover table-bordered" id="list">
          <!-- <colgroup>
            <col width="5%">
            <col width="15%">
            <col width="45%">
            <col width="25%">
            <col width="10%">
          </colgroup> -->
          <thead>
            <tr>
              <th class="text-center">#</th>
              <th>Supplier Name</th>
              <th>Supplier Address</th>
    
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $i = 1;
            $qry = $conn->query("SELECT * FROM supplier_list ORDER BY date_added ASC");
            if ($qry->num_rows > 0) {
              while ($row = $qry->fetch_assoc()):
            ?>
                <tr>
                  <th class="text-center"><?php echo $i++ ?></th>
                  <td><?php echo $row['supplier_name'] ?></td>
                  <td><?php echo $row['supplier_address'] ?></td>
                
                  <td class="text-center">
                    <div class="btn-group">
                      <?php $hashed_link = hash('sha256', $row['supplier_id']); ?>
                      <a class="btn btn-warning btn-flat btn-sm"
                        href="./index.php?page=edit_supplier&supplier_id=<?php echo $hashed_link ?>">Edit</a>
                      <button type="button" class="btn btn-danger btn-flat btn-sm delete-supplier"
                        data-id="<?php echo $row['supplier_id'] ?>">
                        Delete
                      </button>
                    </div>
                  </td>
                </tr>
            <?php endwhile;
            } else {
              echo '<tr class="text-center">
              <td colspan="5">No Data found in Database!</td></tr>';
            } ?>
          </tbody>
        </table>


      </div>
    </div>

  </div>
</div>


<script>
  $(document).ready(function() {
    $('#list').dataTable()
    $('.delete-supplier').click(function() {
      _conf("Are you sure to delete this supplier?", "delete_supplier", [$(this).attr('data-id')])
    })

    $('.edit-supplier').click(function() {
      uni_modal('Edit Supplier', 'edit_supplier.php?supplier_id=' + $(this).attr('data-id'));
    });



  })

  function delete_supplier($supplier_id) {
    start_load()
    $.ajax({
      url: 'ajax.php?action=delete_supplier',
      method: 'POST',
      data: {
        supplier_id: $supplier_id
      },
      success: function(resp) {
        if (resp == 1) {
          alert_toast("Data successfully deleted", 'success')
          setTimeout(function() {
            location.reload()
          }, 1500)

        }
      }
    })
  }


  $('#manage-supplier').submit(function(e) {
    e.preventDefault();
    start_load()
    $('#msg').html('')
    $.ajax({
      url: 'ajax.php?action=save_supplier',
      method: 'POST',
      data: $(this).serialize(),
      success: function(resp) {
        if (resp == 1) {
          alert_toast("Data successfully saved.", "success");
          setTimeout(function() {
            location.replace('index.php?page=suppliers_list')
          }, 750)
        } else if (resp == 2) {
          $('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Criteria already exist.</div>')
          end_load()
        }
      }
    })
  })
</script>
