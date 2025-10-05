
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script src="https://unpkg.com/chart.js-plugin-labels-dv/dist/chartjs-plugin-labels.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-export"></script>

<div class="row">
    <div class="col-sm-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
            <h4 class="text-center">Borrowed Items (current Year)</h4>
            <button class="btn btn-success btn-sm ml-auto" onclick="downloadChartAsPNG()">Download Chart as PNG</button>
            </div>
            <div class="card-body">
            
                
                <canvas id="totalBorrowedPerMonthChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="card">
        <div class="card-header d-flex justify-content-between">
        <h4 class="text-center">Returned (current Year)</h4>
            <button class="btn btn-success btn-sm ml-auto" onclick="downloadChartAsPNGreturn()">Download Chart as PNG</button>
            </div>
            <div class="card-body">
                
                <canvas id="totalReturnedPerMonthChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="card">
        <div class="card-header d-flex justify-content-between">
        <h4 class="text-center">Most Borrowed Items (current Year)</h4>
            <button class="btn btn-success btn-sm ml-auto" onclick="downloadChartAsPNGreturnmost()">Download Chart as PNG</button>
            </div>
            <div class="card-body">
            
                <canvas id="mostBorrowedItemsChart" width="200" height="200"></canvas>
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="card">
        <div class="card-header d-flex justify-content-between">
        <h4 class="text-center">Total Item Counts (current Year)</h4>
            <button class="btn btn-success btn-sm ml-auto" onclick="downloadChartAsPNGreturnall()">Download Chart as PNG</button>
            </div>
            <div class="card-body">
            
                <canvas id="itemCounts" width="200" height="200"></canvas>
            </div>
        </div>
    </div>
</div>



<script>
    // Fetch data from the backend
    fetch('ajax.php?action=fetch_totalBorrowed')
        .then(response => response.json())
        .then(data => {
            const months = Object.keys(data);
            const totals = Object.values(data);
            renderTotalBorrowedPerMonthChart(months, totals);
        })
        .catch(error => console.error('Error fetching total borrowed per month data:', error));

    function renderTotalBorrowedPerMonthChart(months, totals) {
        const ctx = document.getElementById('totalBorrowedPerMonthChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Total Borrowed Items Per Month',
                    data: totals,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: true
                    },
                    export: {
                        enabled: true, // Enable exporting
                        formats: ['png', 'jpg', 'pdf'], // Specify supported formats
                        filename: 'chart', // Default filename
                    }
                },
                hover: {
                    animationDuration: 0
                },
                animation: {
                    duration: 1,
                    onComplete: function() {
                        var chartInstance = this.chart;
                        var ctx = chartInstance.ctx;

                        ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'bottom';

                        this.data.datasets.forEach(function(dataset, i) {
                            var meta = chartInstance.controller.getDatasetMeta(i);
                            meta.data.forEach(function(bar, index) {
                                var data = dataset.data[index];
                                ctx.fillText(data, bar._model.x, bar._model.y - 5);
                            });
                        });
                    }
                },
                tooltips: {
                    enabled: false
                },
                scales: {
                    yAxes: [{
                        display: true,
                        gridLines: {
                            display: true
                        },
                        ticks: {
                            max: Math.max(...totals) + 10,
                            display: true,
                            beginAtZero: true
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: true
                        },
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    }

    function downloadChartAsPNG() {
        // Get the chart canvas
        var canvas = document.getElementById("totalBorrowedPerMonthChart");

        // Convert the canvas to a data URL
        var url = canvas.toDataURL("image/png");

        // Create a temporary link element
        var link = document.createElement('a');
        link.href = url;
        link.download = 'totalBorrowedPerMonthChart.png'; // Set the filename for the downloaded image
        document.body.appendChild(link);

        // Trigger the download
        link.click();

        // Remove the link element
        document.body.removeChild(link);
    }
</script>


<script>
    fetch('ajax.php?action=fetch_totalReturned')
        .then(response => response.json())
        .then(data => {
            const months = Object.keys(data);
            const totals = Object.values(data);
            renderTotalReturnedPerMonthChart(months, totals);
        })
        .catch(error => console.error('Error fetching total returned per month data:', error));

    function renderTotalReturnedPerMonthChart(months, totals) {
        const ctx = document.getElementById('totalReturnedPerMonthChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Total Returned Items Per Month',
                    data: totals,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                hover: {
                    animationDuration: 0
                },
                animation: {
                    duration: 1,
                    onComplete: function() {
                        var chartInstance = this.chart;
                        var ctx = chartInstance.ctx;

                        ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'bottom';

                        this.data.datasets.forEach(function(dataset, i) {
                            var meta = chartInstance.controller.getDatasetMeta(i);
                            meta.data.forEach(function(bar, index) {
                                var data = dataset.data[index];
                                ctx.fillText(data, bar._model.x, bar._model.y - 5);
                            });
                        });
                    }
                },
                legend: {
                    display: true
                },
                tooltips: {
                    enabled: false
                },
                scales: {
                    yAxes: [{
                        display: true,
                        gridLines: {
                            display: true
                        },
                        ticks: {
                            max: Math.max(...totals) + 10,
                            display: true,
                            beginAtZero: true
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: true
                        },
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    }

    function downloadChartAsPNGreturn() {
        // Get the chart canvas
        var canvas = document.getElementById("totalReturnedPerMonthChart");

        // Convert the canvas to a data URL
        var url = canvas.toDataURL("image/png");

        // Create a temporary link element
        var link = document.createElement('a');
        link.href = url;
        link.download = 'totalReturnedPerMonthchart.png'; // Set the filename for the downloaded image
        document.body.appendChild(link);

        // Trigger the download
        link.click();

        // Remove the link element
        document.body.removeChild(link);
    }
</script>
<script>
    // Fetch data from the backend
    fetch('ajax.php?action=fetch_mostBorrowedItems')
        .then(response => response.json())
        .then(data => {
            // Extract item names and counts from the data
            const items = data.map(item => item.i_brand);
            const counts = data.map(item => item.count);

            // Render pie chart
            renderPieChart(items, counts);
        })
        .catch(error => console.error('Error fetching most borrowed items data:', error));

        function renderPieChart(items, counts) {
    const ctx = document.getElementById('mostBorrowedItemsChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: items,
            datasets: [{
                data: counts,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(153, 102, 255, 0.5)',
                    'rgba(255, 159, 64, 0.5)'
                    // Add more colors as needed
                ],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: true,
                    position: 'right',
                }
            }
        },
        plugins: [{
            afterDatasetsDraw: function(chart) {
                const ctx = chart.ctx;
                chart.data.datasets.forEach(function(dataset, i) {
                    const meta = chart.getDatasetMeta(i);
                    if (!meta.hidden) {
                        meta.data.forEach(function(element, index) {
                            // Draw the text in black, with the specified font
                            ctx.fillStyle = 'rgb(0, 0, 0)';

                            const fontSize = 16;
                            const fontStyle = 'normal';
                            const fontFamily = 'Helvetica Neue';
                            ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);

                            // Concatenate label and count
                            const label = chart.data.labels[index];
                            const count = dataset.data[index];
                            const labelText = label + ': ' + count;

                            // Make sure alignment settings are correct
                            const padding = 5;
                            const position = element.tooltipPosition();
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';

                            // Add value inside the segment
                            ctx.fillText(labelText, position.x, position.y);
                        });
                    }
                });
            }
        }]
    });
}


function downloadChartAsPNGmost() {
        // Get the chart canvas
        var canvas = document.getElementById("mostBorrowedItemsChart");

        // Convert the canvas to a data URL
        var url = canvas.toDataURL("image/png");

        // Create a temporary link element
        var link = document.createElement('a');
        link.href = url;
        link.download = 'mostBorrowedItemsChart.png'; // Set the filename for the downloaded image
        document.body.appendChild(link);

        // Trigger the download
        link.click();

        // Remove the link element
        document.body.removeChild(link);
    }
</script>




<script>
    // Fetch data from the backend
    fetch('ajax.php?action=itemCounts')
        .then(response => response.json())
        .then(data => {
            // Extract item names and counts from the data
            const items = data.map(item => item.i_brand);
            const counts = data.map(item => item.count);

            // Render pie chart
            renderPieChart1(items, counts);
        })
        .catch(error => console.error('Error fetching most borrowed items data:', error));

        function renderPieChart1(items, counts) {
    const ctx = document.getElementById('itemCounts').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: items,
            datasets: [{
                data: counts,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(153, 102, 255, 0.5)',
                    'rgba(255, 159, 64, 0.5)'
                    // Add more colors as needed
                ],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: true,
                    position: 'right',
                }
            }
        },
        plugins: [{
            afterDatasetsDraw: function(chart) {
                const ctx = chart.ctx;
                chart.data.datasets.forEach(function(dataset, i) {
                    const meta = chart.getDatasetMeta(i);
                    if (!meta.hidden) {
                        meta.data.forEach(function(element, index) {
                            // Draw the text in black, with the specified font
                            ctx.fillStyle = 'rgb(0, 0, 0)';

                            const fontSize = 16;
                            const fontStyle = 'normal';
                            const fontFamily = 'Helvetica Neue';
                            ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);

                            // Concatenate label and count
                            const label = chart.data.labels[index];
                            const count = dataset.data[index];
                            const labelText = label + ': ' + count;

                            // Make sure alignment settings are correct
                            const padding = 5;
                            const position = element.tooltipPosition();
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';

                            // Add value inside the segment
                            ctx.fillText(labelText, position.x, position.y);
                        });
                    }
                });
            }
        }]
    });
}


function downloadChartAsPNGall() {
        // Get the chart canvas
        var canvas = document.getElementById("itemCounts");

        // Convert the canvas to a data URL
        var url = canvas.toDataURL("image/png");

        // Create a temporary link element
        var link = document.createElement('a');
        link.href = url;
        link.download = 'AllItemsChart.png'; // Set the filename for the downloaded image
        document.body.appendChild(link);

        // Trigger the download
        link.click();

        // Remove the link element
        document.body.removeChild(link);
    }
</script>




