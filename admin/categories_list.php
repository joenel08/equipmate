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
        <strong>Add New Category</strong>
      </div>
      <div class="card-body">
        <form action="" id="manage-category">
          <input type="hidden" name="cat_id" value="<?php echo isset($cat_id) ? $cat_id : '' ?>">
          <div class="form-group">
            <label for="" class="text-normal font-weight-normal">Category Name</label>
            <input type="text" name="category_name" class="form-control form-control-sm"
              value="<?php echo isset($category_name) ? $category_name : '' ?>">
          </div>
          <div class="form-group">
            <label for="" class="font-weight-normal">Category Description</label>
            <textarea name="category_description" id="" class="form-control" cols="30"
              rows="10"><?php echo isset($category_description) ? $category_description : '' ?></textarea>
          </div>
          <div class="form-group">
            <label for="" class="text-normal font-weight-normal">Tags</label>
            <div id="tag-input" class="form-control form-control-sm"
              style="min-height:40px;display:flex;flex-wrap:wrap;gap:5px;">
              <input type="text" id="tag-field" style="border:none;flex:1;min-width:120px;outline:none;">
            </div>
            <input type="hidden" name="tags" id="tags-hidden" value="<?php echo isset($tags) ? $tags : '' ?>">
          </div>


          <div class="d-flex justify-content-end w-100">
            <button class="btn btn-sm btn-success btn-flat mx-1"><i class="fa fa-save"></i> Save Category</button>
            <a href="./index.php?page=categories_list" class="btn btn-sm btn-secondary btn-flat mx-1">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Table List Section -->
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <strong>Category List</strong>
      </div>
      <div class="card-body">


        <table class="table table-hover table-bordered" id="list">
          <colgroup>
            <col width="5%">
            <col width="15%">
            <col width="45%">
            <col width="25%">
            <col width="10%">
          </colgroup>
          <thead>
            <tr>
              <th class="text-center">#</th>
              <th>Category</th>
              <th>Category Description</th>
              <th>Tags</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $i = 1;
            $qry = $conn->query("SELECT * FROM categories_list ORDER BY date_added ASC");
            if ($qry->num_rows > 0) {
              while ($row = $qry->fetch_assoc()):
                ?>
                <tr>
                  <th class="text-center"><?php echo $i++ ?></th>
                  <td><?php echo $row['category_name'] ?></td>
                  <td><?php echo $row['category_description'] ?></td>
                  <td>
                    <?php
                    if (!empty($row['tags'])) {
                      $tags = explode(",", $row['tags']);
                      foreach ($tags as $tag) {
                        echo '<span class="badge bg-primary me-1">' . trim($tag) . '</span>';
                      }
                    } else {
                      echo '<span class="text-muted">No tags</span>';
                    }
                    ?>
                  </td>
                  <td class="text-center">
                    <div class="btn-group">
                      <?php $hashed_link = hash('sha256', $row['cat_id']); ?>
                      <a class="btn btn-warning btn-flat btn-sm"
                        href="./index.php?page=edit_category&cat_id=<?php echo $hashed_link ?>">Edit</a>
                      <button type="button" class="btn btn-danger btn-flat btn-sm delete-category"
                        data-id="<?php echo $row['cat_id'] ?>">
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
    $('.delete-category').click(function () {
      _conf("Are you sure to delete this category?", "delete_category", [$(this).attr('data-id')])
    })

    $('.edit-category').click(function () {
      uni_modal('Edit Category', 'edit_category.php?cat_id=' + $(this).attr('data-id'));
    });



  })
  function delete_category($cat_id) {
    start_load()
    $.ajax({
      url: 'ajax.php?action=delete_category',
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


  $('#manage-category').submit(function (e) {
    e.preventDefault();
    start_load()
    $('#msg').html('')
    $.ajax({
      url: 'ajax.php?action=save_category',
      method: 'POST',
      data: $(this).serialize(),
      success: function (resp) {
        if (resp == 1) {
          alert_toast("Data successfully saved.", "success");
          setTimeout(function () {
            location.replace('index.php?page=categories_list')
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