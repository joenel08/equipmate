<?php include 'db_connect.php' ?>

<div class="row">
  <!-- Form Section -->
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <strong>Add New Category</strong>
      </div>
      <div class="card-body">
        <form action="" id="manage-department">
          <input type="hidden" name="d_id" value="<?php echo isset($d_id) ? $d_id : '' ?>">
             <div class="form-group">
            <label for="" class="text-normal font-weight-normal">Department Abbreviation</label>
            <input type="text" name="department_abbrv" class="form-control form-control-sm"
              value="<?php echo isset($department_abbrv) ? $department_abbrv : '' ?>">
          </div>
          <div class="form-group">
            <label for="" class="text-normal font-weight-normal">Department Name</label>
            <input type="text" name="department_name" class="form-control form-control-sm"
              value="<?php echo isset($department_name) ? $department_name : '' ?>">
          </div>
        
       


          <div class="d-flex justify-content-end w-100">
            <button class="btn btn-sm btn-success btn-flat mx-1"><i class="fa fa-save"></i> Save Department</button>
            <a href="./index.php?page=department_list" class="btn btn-sm btn-secondary btn-flat mx-1">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Table List Section -->
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <strong>Department List</strong>
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
              <th>Department</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $i = 1;
            $qry = $conn->query("SELECT * FROM department_list ORDER BY date_added ASC");
            if ($qry->num_rows > 0) {
              while ($row = $qry->fetch_assoc()):
                ?>
                <tr>
                  <th class="text-center"><?php echo $i++ ?></th>
                  <td><?php echo $row['department_abbrv'].' - '.$row['department_name'] ?></td>
                 
                  <td class="text-center">
                    <div class="btn-group">
                      <?php $hashed_link = hash('sha256', $row['d_id']); ?>
                      <a class="btn btn-warning btn-flat btn-sm"
                        href="./index.php?page=edit_department&d_id=<?php echo $hashed_link ?>">Edit</a>
                      <button type="button" class="btn btn-danger btn-flat btn-sm delete-department"
                        data-id="<?php echo $row['d_id'] ?>">
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
  $(document).ready(function () {
    $('#list').dataTable()
    $('.delete-department').click(function () {
      _conf("Are you sure to delete this department?", "delete_department", [$(this).attr('data-id')])
    })

    $('.edit-department').click(function () {
      uni_modal('Edit Department', 'edit_department.php?d_id=' + $(this).attr('data-id'));
    });



  })
  function delete_category($d_id) {
    start_load()
    $.ajax({
      url: 'ajax.php?action=delete_department',
      method: 'POST',
      data: { d_id: $d_id },
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


  $('#manage-department').submit(function (e) {
    e.preventDefault();
    start_load()
    $('#msg').html('')
    $.ajax({
      url: 'ajax.php?action=save_department',
      method: 'POST',
      data: $(this).serialize(),
      success: function (resp) {
        if (resp == 1) {
          alert_toast("Data successfully saved.", "success");
          setTimeout(function () {
            location.replace('index.php?page=department_list')
          }, 750)
        } else if (resp == 2) {
          $('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Criteria already exist.</div>')
          end_load()
        }
      }
    })
  })
</script>

<script>
  let tags = [];

  function renderTags() {
    $('#tag-input .tag').remove();
    tags.forEach((tag, index) => {
      $('#tag-field').before(
        `<span class="tag">${tag}<span class="remove" data-index="${index}">&times;</span></span>`
      );
    });
    $('#tags-hidden').val(tags.join(",")); // update hidden input
  }

  // Add tag on comma
  $('#tag-field').on('keyup', function (e) {
    if (e.key === ',' || e.keyCode === 188) {
      let val = $(this).val().replace(',', '').trim();
      if (val && !tags.includes(val)) {
        tags.push(val);
        renderTags();
      }
      $(this).val('');
    }
  });

  // Remove tag when clicking ×
  $(document).on('click', '.tag .remove', function () {
    let i = $(this).data('index');
    tags.splice(i, 1);
    renderTags();
  });

  // If editing an existing record, load tags
  $(document).ready(function () {
    let existing = $('#tags-hidden').val();
    if (existing) {
      tags = existing.split(',').map(t => t.trim()).filter(t => t);
      renderTags();
    }
  });

</script>