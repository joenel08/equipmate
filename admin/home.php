<!-- Load Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style></style>
<div class="row">
  <!-- Total Categories -->
  <div class="col-lg-3 col-6">
    <div class="small-box bg-primary">
      <div class="inner">
        <h3> <?php
        echo $conn->query("SELECT * from categories_list")->num_rows
          ?></h3>
        <p>Total Categories</p>
      </div>
      <div class="icon">
        <i class="fas fa-tags"></i>
      </div>
      <a href="./index.php?page=categories_list" class="small-box-footer">More info <i
          class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>

  <!-- Total Stock Materials/Products -->
  <div class="col-lg-3 col-6">
    <div class="small-box bg-success">
      <div class="inner">
        <h3> <?php
        echo $conn->query("SELECT * from materials_list")->num_rows
          ?></h3>
        <p>Total Materials</p>
      </div>
      <div class="icon">
        <i class="fas fa-boxes"></i>
      </div>
      <a href="./index.php?page=materials_list" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>

  <!-- Total Low Stock -->
  <div class="col-lg-3 col-6">
    <div class="small-box bg-warning">
      <div class="inner">
        <h3>
          <?php
          $qry = $conn->query("
    SELECT m.*, c.category_name,
        (m.initial_quantity 
            + IFNULL((SELECT SUM(r.quantity) FROM restock_list r WHERE r.material_id = m.material_id), 0)
            - IFNULL((SELECT SUM(d.quantity) FROM distribution_list d WHERE d.material_id = m.material_id),0)
        ) AS qty_now
    FROM materials_list m
    LEFT JOIN categories_list c ON c.cat_id = m.category_id
    ORDER BY m.date_added ASC
");

          if (!$qry) {
            die("Query Failed: " . $conn->error); // Debug
          }

          $low_count = 0;

          while ($row = $qry->fetch_assoc()):
            $initial = (int) $row['initial_quantity'];
            $now = (int) $row['qty_now'];
            $percent = $initial > 0 ? ($now / $initial) * 100 : 0;

            if ($percent < 30) {
              $low_count++;
            }
          endwhile;

          echo $low_count;
          ?>
        </h3>


        <p>Total Low Stock</p>
      </div>
      <div class="icon">
        <i class="fas fa-exclamation-triangle"></i>
      </div>
      <a href="./index.php?page=materials_list" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>

  <!-- Total Disbursed -->
  <div class="col-lg-3 col-6">
    <div class="small-box bg-danger">
      <div class="inner">
        <h3><?php
        echo $conn->query("SELECT distinct(material_id) from distribution_list")->num_rows
          ?></h3>
        <p>Total Materials Disbursed</p>
      </div>
      <div class="icon">
        <i class="fas fa-share-square"></i>
      </div>
      <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>



 <div class="col-lg-3 col-md-3 col-6">
    <div class="small-box bg-info">
      <div class="inner">
        <h3> <?php
        echo $conn->query("SELECT * from employees")->num_rows
          ?></h3>
        <p>Total Employees</p>
      </div>
      <div class="icon">
        <i class="fas fa-users"></i>
      </div>
      <a href="./index.php?page=employee_list" class="small-box-footer">More info <i
          class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>

  <div class="col-lg-3 col-md-3 col-6">
    <div class="small-box bg-purple">
      <div class="inner">
        <h3> <?php
        echo $conn->query("SELECT * from department_list")->num_rows
          ?></h3>
        <p>Total Department</p>
      </div>
      <div class="icon">
        <i class="fas fa-building"></i>
      </div>
      <a href="./index.php?page=department_list" class="small-box-footer">More info <i
          class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
</div>

<?php
$materialLabels = [];
$stockData = [];
$percentages = [];

$qry = $conn->query("
    SELECT m.material_name, m.initial_quantity, m.unit,
        (m.initial_quantity 
        + COALESCE((SELECT SUM(r.quantity) FROM restock_list r WHERE r.material_id = m.material_id), 0)
        - COALESCE((SELECT SUM(d.quantity) FROM distribution_list d WHERE d.material_id = m.material_id), 0)
        ) AS qty_now
    FROM materials_list m
    LEFT JOIN categories_list c ON c.cat_id = m.category_id
    ORDER BY m.material_name ASC
");

while ($row = $qry->fetch_assoc()) {
    $materialLabels[] = $row['material_name'].' ('.$row['unit'].')';
    $stockData[] = (int)$row['qty_now'];
    $percent = $row['initial_quantity'] > 0 ? ($row['qty_now'] / $row['initial_quantity']) * 100 : 0;
    $percentages[] = $percent;
}

?>



<!-- Charts -->
<div class="row">
  <!-- Stock Levels per Category (New Bar Chart) -->
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h5>Stock Levels</h5>
      </div>
      <div class="card-body">
        <div class="">
          <strong>Legend:</strong>
          <ul class="list-inline">
            <li class="list-inline-item"><span
                style="display:inline-block;width:12px;height:12px;background:#dc3545;"></span> Low (< 30%)</li>
            <li class="list-inline-item"><span
                style="display:inline-block;width:12px;height:12px;background:#ffc107;"></span> Mid (≥ 30% & < 70% )</li>
            <li class="list-inline-item"><span
                style="display:inline-block;width:12px;height:12px;background:#007bff;"></span> High (≥ 70%)</li>
          </ul>
        </div>
        <canvas id="stockBarChart"></canvas>
      </div>
    </div>
  </div>
<?php
// Prepare all 12 months
$allMonths = [];
for ($i = 1; $i <= 12; $i++) {
    $allMonths[$i] = 0; // default 0
}

// Query actual distributed per month this year
$monthly = $conn->query("
    SELECT MONTH(date_distributed) as month, SUM(quantity) as total
    FROM distribution_list
    WHERE YEAR(date_distributed) = YEAR(CURDATE())
    GROUP BY MONTH(date_distributed)
");

// Merge results into the array
while ($r = $monthly->fetch_assoc()) {
    $allMonths[(int)$r['month']] = (int)$r['total'];
}

// Convert to labels + data arrays
$months = [];
$monthlyData = [];
foreach ($allMonths as $num => $total) {
    $months[] = date("M", mktime(0,0,0,$num,1)); // Jan, Feb, Mar ...
    $monthlyData[] = $total;
}

// Disbursed per year
$yearly = $conn->query("
    SELECT YEAR(date_distributed) as year, SUM(quantity) as total
    FROM distribution_list
    GROUP BY YEAR(date_distributed)
");

$years = [];
$yearlyData = [];
while ($r = $yearly->fetch_assoc()) {
    $years[] = $r['year'];
    $yearlyData[] = (int)$r['total'];
}
?>

  <!-- Bar Chart -->
  <div class="col-md-6">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Distributed Materials</h5>
        <div>
          <button class="btn btn-sm btn-outline-secondary" onclick="updateBarChart('month')">Per Month</button>
          <button class="btn btn-sm btn-outline-secondary" onclick="updateBarChart('year')">Per Year</button>
        </div>
      </div>
      <div class="card-body">
        <canvas id="disbursedBarChart" height="185"></canvas>
      </div>
    </div>
  </div>
</div>
<script>
const materialLabels = <?= json_encode($materialLabels) ?>;
const stockData = <?= json_encode($stockData) ?>;
const percentages = <?= json_encode($percentages) ?>;

// Dynamic color coding based on percentage
const getColor = (percent) => {
  if (percent < 30) return '#dc3545';   // Low
  if (percent < 70) return '#ffc107';   // Mid
  return '#007bff';                     // High
};

const backgroundColors = percentages.map(p => getColor(p));

const ctxStock = document.getElementById('stockBarChart').getContext('2d');
new Chart(ctxStock, {
  type: 'bar',
  data: {
    labels: materialLabels,
    datasets: [{
      label: 'Stock Quantity',
      data: stockData,
      backgroundColor: backgroundColors
    }]
  },
  options: {
    responsive: true,
    scales: { y: { beginAtZero: true } },
    plugins: { legend: { display: false } }
  }
});
</script>


<script>
let monthLabels = <?= json_encode($months) ?>;
let monthData   = <?= json_encode($monthlyData) ?>;

let yearLabels = <?= json_encode($years) ?>;
let yearData   = <?= json_encode($yearlyData) ?>;

const ctxBar = document.getElementById('disbursedBarChart').getContext('2d');
let barChart;

function initBarChart(data, labels) {
  if (barChart) barChart.destroy();
  barChart = new Chart(ctxBar, {
    type: 'bar',
    data: { labels: labels, datasets: [{ label: 'Disbursed', data: data, backgroundColor: '#17a2b8' }] },
    options: { scales: { y: { beginAtZero: true } } }
  });
}

function updateBarChart(type) {
  if (type === 'month') {
    initBarChart(monthData, monthLabels);
  } else {
    initBarChart(yearData, yearLabels);
  }
}

// Load default
updateBarChart('month');
</script>

<!-- <script>
  // Bar Chart: Stock Levels per Category with color coding
  const ctxStock = document.getElementById('stockBarChart').getContext('2d');

  const categoryLabels = ['Tools', 'Supplies', 'Equipment', 'Chemicals', 'Parts'];
  const stockData = [90, 250, 380, 150, 60]; // dummy values

  // Determine color based on stock level
  const getColor = (value) => {
    if (value <= 100) return '#dc3545';    // red for low
    if (value <= 300) return '#ffc107';    // yellow for mid
    return '#007bff';                      // blue for high
  };

  const backgroundColors = stockData.map(value => getColor(value));

  const stockBarChart = new Chart(ctxStock, {
    type: 'bar',
    data: {
      labels: categoryLabels,
      datasets: [{
        label: 'Stock Quantity',
        data: stockData,
        backgroundColor: backgroundColors
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true
        }
      },
      plugins: {
        legend: {
          display: false
        }
      }
    }
  });
</script>

<script>


  // Bar Chart: Products disbursed (filterable)
  const ctxBar = document.getElementById('disbursedBarChart').getContext('2d');
  let barChart;

  function initBarChart(data, labels) {
    if (barChart) barChart.destroy();
    barChart = new Chart(ctxBar, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Disbursed',
          data: data,
          backgroundColor: '#17a2b8'
        }]
      },
      options: {
        scales: {
          y: { beginAtZero: true }
        }
      }
    });
  }

  function updateBarChart(type) {
    if (type === 'month') {
      initBarChart([120, 90, 150, 80, 200, 130], ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']);
    } else {
      initBarChart([600, 720, 800, 660], ['2021', '2022', '2023', '2024']);
    }
  }

  // Initialize with month view
  updateBarChart('month');
</script> -->