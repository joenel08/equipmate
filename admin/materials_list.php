<div class="row">
    <!-- Form Section -->
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                <strong>Add New Material</strong>
            </div>
            <div class="card-body">
                <form method="POST" action="" id="manage-materials">
                    <input type="hidden" name="material_id"
                        value="<?php echo isset($material_id) ? $material_id : '' ?>">

                    <!-- Supplier Selection -->
                    <div class="form-group">
                        <label for="department">Supplier</label>
                        <select class="form-control" name="supplier_id" id="supplier_id" required>
                            <option value="">Select Supplier</option>
                            <?php
                            $dept_qry = $conn->query("SELECT * FROM supplier_list");
                            while ($dept = $dept_qry->fetch_assoc()):
                                $selected = (isset($supplier_id) && $supplier_id == $dept['supplier_id']) ? 'selected' : '';
                            ?>
                                <option value="<?php echo $dept['supplier_id'] ?>" <?php echo $selected ?>>
                                    <?php echo $dept['supplier_name'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="material_name">Material Name</label>
                        <input type="text" class="form-control" id="material_name" name="material_name"
                            value="<?php echo isset($material_name) ? $material_name : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select class="form-control" name="category" id="category" required>
                            <?php
                            $qry = $conn->query("SELECT * FROM categories_list ORDER BY date_added ASC");
                            if ($qry->num_rows > 0) {
                                while ($row = $qry->fetch_assoc()):
                                    $selected = (isset($category) && $category == $row['cat_id']) ? 'selected' : '';
                                    echo '<option value="' . $row['cat_id'] . '" ' . $selected . '>' . $row['category_name'] . '</option>';
                                endwhile;
                            } else {
                                echo '<option value="">No Category Added</option>';
                            }
                            ?>
                        </select>

                    </div>
                    <div class="form-group">
                        <label for="initial_quantity">Initial Quantity</label>
                        <input type="number" class="form-control" id="initial_quantity" name="initial_quantity" required
                            value="<?php echo isset($initial_quantity) ? $initial_quantity : '' ?>">
                    </div>

                    <div class="form-group">
                        <label for="unit">Unit</label>
                        <select class="form-control" name="unit" id="unit" required>
                            <?php
                            $units = [
                                "pcs",
                                "box",
                                "pack",
                                "set",
                                "dozen",
                                "ream",
                                "bundle",
                                "pad",
                                "roll",
                                "bottle",
                                "tube",
                                "liter",
                                "ml",
                                "kg",
                                "g"
                            ];
                            foreach ($units as $u) {
                                $selected = (isset($unit) && $unit == $u) ? 'selected' : '';
                                echo "<option value='$u' $selected>$u</option>";
                            }
                            ?>
                        </select>
                    </div>


                    <div class="text-right">
                        <button type="submit" class="btn btn-success btn-sm btn-flat"><i class="fa fa-save"></i> Save
                            Material</button>
                        <button class="btn btn-sm btn-secondary btn-flat mx-1" form="manage-category"
                            type="reset">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                <strong>Materials List</strong>

            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover table-responsive" id="list">
                   
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Supplier</th>
                            <th>Material Name</th>
                            <th>Category</th>
                            <th>Initial Qty.</th>
                            <th>Current Qty.</th>
                            <th>Unit</th>
                            <th>Stock Level</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        // Join categories_list to get category_name
                        $qry = $conn->query("
                                SELECT s.supplier_name, m.*, c.category_name,
                                    (m.initial_quantity 
                                        + IFNULL((SELECT SUM(r.quantity) FROM restock_list r WHERE r.material_id = m.material_id), 0)
                                        - IFNULL(SUM(d.quantity),0)) AS qty_now
                                FROM materials_list m
                                LEFT JOIN categories_list c ON m.category_id = c.cat_id
                                LEFT JOIN distribution_list d ON m.material_id = d.material_id
                                LEFT JOIN supplier_list s ON s.supplier_id = m.supplier_id
                                GROUP BY m.material_id
                                ORDER BY m.date_added ASC
                            ");



                        if ($qry->num_rows > 0):
                            while ($row = $qry->fetch_assoc()):
                                $initial = (int) $row['initial_quantity'];
                                $now = (int) $row['qty_now']; // now is computed from query
                                $percent = $initial > 0 ? ($now / $initial) * 100 : 0;

                                if ($percent >= 70) {
                                    $level = 'High';
                                    $badge = 'badge-success';
                                } elseif ($percent >= 30) {
                                    $level = 'Medium';
                                    $badge = 'badge-warning';
                                } else {
                                    $level = 'Low';
                                    $badge = 'badge-danger';
                                }
                        ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= htmlspecialchars($row['supplier_name']) ?></td>
                                    <td><?= htmlspecialchars($row['material_name']) ?></td>
                                    <td><?= htmlspecialchars($row['category_name']) ?></td>

                                    <td>
                                        <span class="font-weight-bold"><?= $initial ?> </span><br>
                                        <a href="javascript:void(0)" class="text-sm view-restock"
                                            data-id="<?= $row['material_id'] ?>"
                                            data-name="<?= htmlspecialchars($row['material_name']) ?>"
                                            data-category="<?= htmlspecialchars($row['category_name']) ?>">
                                            View Restock List
                                        </a>

                                    </td>

                                    <td><?= $now ?></td>
                                    <td><?= htmlspecialchars($row['unit']) ?></td>
                                    <td><span class="badge <?= $badge ?>"><?= $level ?></span></td>
                                    <td>
                                        <?php if ($level == 'Low'): ?>
                                            <button class="btn btn-sm btn-primary restock-btn" data-id="<?= $row['material_id'] ?>"
                                                data-name="<?= htmlspecialchars($row['material_name']) ?>"
                                                data-category="<?= htmlspecialchars($row['category_name']) ?>">
                                                <i class="fa fa-sync"></i> Restock
                                            </button>

                                        <?php else: ?>
                                            <button class="btn btn-sm btn-secondary" disabled>
                                                <i class="fa fa-sync"></i> Restock
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php $hashed_link = hash('sha256', $row['material_id']); ?>
                                        <a class="btn btn-warning btn-flat btn-sm"
                                            href="./index.php?page=edit_material&material_id=<?php echo $hashed_link ?>">Edit</a>
                                        <button type="button" class="btn btn-danger btn-flat btn-sm delete-material"
                                            data-id="<?php echo $row['material_id'] ?>">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile;
                        else: ?>
                            <tr class="text-center">
                                <td colspan="9">No Materials Found!</td>
                            </tr>
                        <?php endif; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- Restock Modal -->
<div class="modal fade" id="restockModal" tabindex="-1" role="dialog" aria-labelledby="restockModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="" id="restock">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="restockModalLabel">Restock Material</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"
                        aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="material_id" id="restock_material_id">

                    <span class="text-sm" id="restock_category"></span><br>
                    <span class="h6" id="restock_name"></span>

                    <hr>
                    <div class="form-group">
                        <label for="restock_quantity">Add Quantity</label>
                        <input type="number" class="form-control" name="restock_quantity" id="restock_quantity"
                            required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-flat btn-sm"><i class="fa fa-plus"></i> Add
                        Stock</button>
                    <button type="button" class="btn btn-secondary btn-flat btn-sm" data-dismiss="modal"><i
                            class="fa fa-times"></i>
                        Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Restock History Modal -->
<div class="modal fade" id="restockHistoryModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Restock History - <span id="historyMaterialName"></span></h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-sm" id="restockHistoryTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody id="restockHistoryBody">
                        <tr>
                            <td colspan="2" class="text-center">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <a id="downloadExcel" href="#" class="btn btn-success btn-flat btn-sm">
                    <i class="fa fa-download"></i> Download CSV
                </a>
                <button type="button" class="btn btn-secondary btn-flat btn-sm" data-dismiss="modal">
                    <i class="fa fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    let currentMaterialName = "";
    let currentCategory = "";

    $(document).on('click', '.view-restock', function() {
        let material_id = $(this).data('id');
        currentMaterialName = $(this).data('name');
        currentCategory = $(this).data('category');

        $('#historyMaterialName').text(currentMaterialName);
        $('#restockHistoryBody').html('<tr><td colspan="2" class="text-center">Loading...</td></tr>');

        $.ajax({
            url: 'ajax.php?action=get_restock_list',
            method: 'POST',
            data: {
                material_id: material_id
            },
            success: function(resp) {
                try {
                    let data = JSON.parse(resp);
                    let rows = "";
                    if (data.length > 0) {
                        data.forEach(r => {
                            rows += `<tr>
                                <td>${r.date_added}</td>
                                <td>${r.quantity}</td>
                             </tr>`;
                        });
                    } else {
                        rows = `<tr><td colspan="2" class="text-center">No restocks found</td></tr>`;
                    }
                    $('#restockHistoryBody').html(rows);
                    $('#restockHistoryModal').modal('show');
                } catch (e) {
                    alert("Error loading data");
                }
            }
        })
    });
</script>


<script>
    function parseDateStringToJSDate(s) {
        if (!s) return null;
        // quick attempts
        var d = new Date(s);
        if (!isNaN(d)) return d;
        d = new Date(s.replace(' ', 'T'));
        if (!isNaN(d)) return d;
        // fallback for "YYYY-MM-DD HH:MM:SS" etc.
        var m = s.match(/^(\d{4})-(\d{2})-(\d{2})(?:[ T](\d{2}):(\d{2})(?::(\d{2}))?)?/);
        if (m) {
            var y = +m[1],
                mo = +m[2] - 1,
                day = +m[3],
                hh = +(m[4] || 0),
                mi = +(m[5] || 0),
                ss = +(m[6] || 0);
            return new Date(y, mo, day, hh, mi, ss);
        }
        return null;
    }

    document.getElementById("downloadExcel").addEventListener("click", function() {
        var table = document.getElementById("restockHistoryTable");
        if (!table) {
            alert("No restock table found");
            return;
        }

        // Create worksheet placing the HTML table at A3 (so header will be at row 3)
        var ws = XLSX.utils.table_to_sheet(table, {
            origin: "A3"
        });

        // Insert meta rows at A1-A2 (safe because table was placed at A3)
        XLSX.utils.sheet_add_aoa(ws, [
            ["Category:", currentCategory || ""],
            ["Material Name:", currentMaterialName || ""]
        ], {
            origin: "A1"
        });

        // Convert column A (dates) in data rows to real JS Date objects so Excel shows real dates
        var range = ws['!ref'] ? XLSX.utils.decode_range(ws['!ref']) : null;
        if (range) {
            var headerRow = range.s.r; // zero-based index of first row in sheet (should be 0 since we added A1)
            // Our table header is at row index 2 (A3 origin => zero-based 2)
            // But to be robust, find the row index of the table header: it will be the first row containing the table header text "Date"
            // Simpler: assume table header is at row index 2 (A3). Data rows start at headerRowOfTable + 1 => 3 (zero-based).
            var tableHeaderRowIndex = 2; // zero-based for A3
            var dataStart = tableHeaderRowIndex + 1;

            for (var R = dataStart; R <= range.e.r; ++R) {
                var cellRef = XLSX.utils.encode_cell({
                    c: 0,
                    r: R
                }); // column A = 0
                var cell = ws[cellRef];
                if (!cell) continue;
                // If it's a string/date-like, parse it
                if (cell.t === 's' || cell.t === 'str') {
                    var d = parseDateStringToJSDate(String(cell.v).trim());
                    if (d && !isNaN(d.getTime())) {
                        cell.t = 'd';
                        cell.v = d;
                        // set number format (optional)
                        cell.z = "yyyy-mm-dd hh:mm:ss";
                    }
                }
                // If cell is numeric string like Excel serial number accidentally present, try parse_date_code
                else if (cell.t === 'n') {
                    try {
                        var code = XLSX.SSF.parse_date_code(cell.v);
                        if (code) {
                            var dd = new Date(code.y, code.m - 1, code.d, code.H, code.M, Math.floor(code.S));
                            cell.t = 'd';
                            cell.v = dd;
                            cell.z = "yyyy-mm-dd hh:mm:ss";
                        }
                    } catch (e) {
                        // ignore
                    }
                }
            }
        }

        // Build workbook and save file
        var wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Restock List");

        var safeName = (currentMaterialName || 'restock_list').replace(/[^a-z0-9_\-]/gi, '_').slice(0, 50);
        XLSX.writeFile(wb, safeName + "_restock.xlsx");
    });
</script>

<script>
    $(document).on('click', '.restock-btn', function() {
        let id = $(this).data('id');
        let name = $(this).data('name');
        let category = $(this).data('category');

        $('#restock_material_id').val(id);
        $('#restock_category').text("Category: " + category);
        $('#restock_name').text("Material Name: " + name);
        $('#restock_quantity').val('');
        $('#restockModal').modal('show');
    });
</script>

<script>
    $(document).on('click', '.restock-btn', function() {
        let index = $(this).data('index');
        $('#restock_index').val(index);
        $('#restock_quantity').val('');
        $('#restockModal').modal('show');
    });

    $('#manage-materials').submit(function(e) {
        e.preventDefault();
        start_load()
        $('#msg').html('')
        $.ajax({
            url: 'ajax.php?action=save_materials',
            method: 'POST',
            data: $(this).serialize(),
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Data successfully saved.", "success");
                    setTimeout(function() {
                        location.replace('index.php?page=materials_list')
                    }, 750)
                } else if (resp == 2) {
                    $('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Criteria already exist.</div>')
                    end_load()
                }
            }
        })
    })

    $('#restock').submit(function(e) {
        e.preventDefault();
        start_load()
        $.ajax({
            url: 'ajax.php?action=save_restock',
            method: 'POST',
            data: $(this).serialize(),
            success: function(resp) {
                if (resp == 1) {
                    $('#restockModal').modal('hide');
                    alert_toast("Stock successfully updated!", "success");
                    setTimeout(function() {
                        location.reload();
                    }, 1000)
                } else {
                    alert_toast("Error updating stock", "danger");
                }
                end_load()
            }
        })
    })
</script>
<script>
    $(document).ready(function() {

        $('#list').dataTable()

        $('.delete-material').click(function() {
            _conf("Are you sure to delete this material?", "delete_material", [$(this).attr('data-id')])
        })

    })



    function delete_material($id) {
        start_load()
        $.ajax({
            url: 'ajax.php?action=delete_materials',
            method: 'POST',
            data: {
                id: $id
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
</script>