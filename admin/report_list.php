<div class="row">

    <!-- Table Section -->
    <div class="col-sm">
        <div class="card">
            <div class="card-header ">
                <div class="row">
                    <div class="col-sm">
                        <strong class="me-2 mb-0">Report List</strong>
                    </div>
                    <div class="col-sm-8 d-flex">
                        <select name="month" id="month" class="form-control">
                            <option value="">All Months</option><?php for ($m = 1; $m <= 12; $m++): ?>
                                <option value="<?= $m ?>" <?= (isset($_GET['month']) && $_GET['month'] == $m) ? 'selected' : '' ?>>
                                    <?= date("F", mktime(0, 0, 0, $m, 1)) ?>
                                </option><?php endfor; ?>
                        </select>
                        &nbsp;
                        <select name="year" id="year" class="form-control">
                            <option value="">All Years</option><?php $yNow = date("Y");
                            for ($y = $yNow; $y >= 2000; $y--): ?>
                                <option value="<?= $y ?>" <?= (isset($_GET['year']) && $_GET['year'] == $y) ? 'selected' : '' ?>>
                                    <?= $y ?>
                                </option><?php endfor; ?>
                        </select>&nbsp;
                        <button id="filterBtn" class="btn btn-primary btn-sm w-50"><i class="fa fa-filter"></i>
                            Filter</button>&nbsp;
                        <button id="exportBtn" class="btn btn-success btn-sm w-50"><i class="fa fa-file-excel"></i>
                            Export</button>
                    </div>
                </div>

            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover" id="list">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Material Name</th>
                            <th>Category</th>
                            <th>Initial Qty.</th>
                            <th>Current Qty.</th>

                            <th>Quantity Distributed</th>
                            <th>Unit</th>
                            <th>Stock Level</th>

                        </tr>
                    </thead>
                    <tbody>


                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
    function exportTableToExcel() {
        let month = $("#month").val();
        let year = $("#year").val();

        // Fetch the data via AJAX for the selected month/year
        $.ajax({
            url: "ajax.php?action=fetch_materials",
            method: "POST",
            data: { month: month, year: year, export: 1 }, // export=1 to optionally handle in backend if needed
            success: function (data) {
                // Create a hidden table to convert to XLSX (or use existing table)
                var tempDiv = document.createElement('div');
                tempDiv.innerHTML = '<table>' + data + '</table>';
                var table = tempDiv.querySelector('table');

                var wb = XLSX.utils.book_new();
                var ws = XLSX.utils.table_to_sheet(table);
                XLSX.utils.book_append_sheet(wb, ws, "Materials List");

                let monthText = month ? $("#month option:selected").text() : 'AllMonths';
                let yearText = year || 'AllYears';

                XLSX.writeFile(wb, `Materials_${monthText}_${yearText}.xlsx`);
            }
        });
    }

    $("#exportBtn").click(function () {
        exportTableToExcel();
    });
</script>

<script>
    function loadMaterials(month = '', year = '') {
        $.ajax({
            url: "ajax.php?action=fetch_materials",
            method: "POST",
            data: { month: month, year: year },
            success: function (data) {
                $("#list tbody").html(data);
            }
        });
    }

    $(document).ready(function () {
        // Load all on page load
        loadMaterials();

        // Filter button
        $("#filterBtn").click(function () {
            let month = $("#month").val();
            let year = $("#year").val();
            loadMaterials(month, year);
        });


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
            var y = +m[1], mo = +m[2] - 1, day = +m[3], hh = +(m[4] || 0), mi = +(m[5] || 0), ss = +(m[6] || 0);
            return new Date(y, mo, day, hh, mi, ss);
        }
        return null;
    }

    document.getElementById("downloadExcel").addEventListener("click", function () {
        var table = document.getElementById("restockHistoryTable");
        if (!table) {
            alert("No restock table found");
            return;
        }

        // Create worksheet placing the HTML table at A3 (so header will be at row 3)
        var ws = XLSX.utils.table_to_sheet(table, { origin: "A3" });

        // Insert meta rows at A1-A2 (safe because table was placed at A3)
        XLSX.utils.sheet_add_aoa(ws, [
            ["Category:", currentCategory || ""],
            ["Material Name:", currentMaterialName || ""]
        ], { origin: "A1" });

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
                var cellRef = XLSX.utils.encode_cell({ c: 0, r: R }); // column A = 0
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
    $(document).ready(function () {

        $('#list').dataTable()



    })



</script>