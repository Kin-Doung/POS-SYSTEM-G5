  <?php 
        require_once './views/layouts/header.php';
        require_once './views/layouts/side.php';
  ?>


  <body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper" style="margin-left: 250px;">
      <!-- Content Wrapper -->
      <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">


  <!-- style of the navbar-----------------------------------------------------------------------------------  -->
  <?php require_once './views/layouts/nav.php' ?>
  <!-- end of the navbar style-------------------------------------------------------------------------- -->



          <!-- Begin Page Content -->
          <div class="container-fluid">

            <div class="row">
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2 position-relative">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="d-flex justify-content-between align-items-center">
                          <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Profit
                          </div>
                          <div class="dropdown">
                            <i class="fa-solid fa-caret-down text-primary" data-toggle="dropdown" style="cursor:pointer;"></i>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                              <button class="dropdown-item active" onclick="filterData('today')">Today</button>
                              <button class="dropdown-item" onclick="filterData('this_week')">This Week</button>
                              <button class="dropdown-item" onclick="filterData('this_month')">This Month</button>
                            </div>
                          </div>
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 total-profit">$<?php echo number_format($Today_Profit, 2); ?></div>
                      </div>
                      <div class="col-auto">
                      <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Dynamic Table of Records (optional, still hidden) -->
              <table class="table" id="profit-loss-table" style="display: none;">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Sale Date</th>
                    <th>Profit/Loss</th>
                    <th>Result Type</th>
                  </tr>
                </thead>
                <tbody id="profit-loss-body">
                  <!-- Rows will be populated by JavaScript -->
                </tbody>
              </table>

              <script>
                // Pass all sales data from PHP to JavaScript
                const salesData = <?php echo json_encode($Profit_Loss ?? []); ?>;
                console.log('Sales Data:', salesData);

                // Check if there's a stored value in localStorage and update the card
                const storedProfit = localStorage.getItem('totalProfit');
                const storedPeriod = localStorage.getItem('selectedPeriod');
                if (storedProfit && storedPeriod) {
                  document.querySelector('.total-profit').textContent = `$${parseFloat(storedProfit).toFixed(2)}`;
                }

                function filterData(period) {
                  // Define date boundaries
                  const today = new Date();
                  let startDate, endDate;

                  if (period === 'today') {
                    startDate = new Date(today.setHours(0, 0, 0, 0));
                    endDate = new Date(today.setHours(23, 59, 59, 999));
                  } else if (period === 'this_week') {
                    startDate = new Date(today);
                    startDate.setDate(today.getDate() - today.getDay() + 1); // Monday
                    startDate.setHours(0, 0, 0, 0);
                    endDate = new Date(startDate);
                    endDate.setDate(startDate.getDate() + 6); // Sunday
                    endDate.setHours(23, 59, 59, 999);
                  } else if (period === 'this_month') {
                    startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                    endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                    endDate.setHours(23, 59, 59, 999);
                  }

                  // Filter data and calculate total profit
                  let totalProfit = 0;
                  const filteredData = salesData.filter(record => {
                    const saleDate = new Date(record.Sale_Date);
                    return saleDate >= startDate && saleDate <= endDate;
                  });
                  filteredData.forEach(record => {
                    if (record.Result_Type === 'Profit') {
                      totalProfit += parseFloat(record.Profit_Loss) || 0;
                    }
                  });

                  // Update the card
                  document.querySelector('.total-profit').textContent = `$${totalProfit.toFixed(2)}`;

                  // Clear previous localStorage and store new values
                  localStorage.clear(); // Clear all previous entries
                  localStorage.setItem('totalProfit', totalProfit);
                  localStorage.setItem('selectedPeriod', period);
                  console.log(`Stored: ${period} - $${totalProfit}`);

                  // Update the table (optional, since it's hidden)
                  const tbody = document.getElementById('profit-loss-body');
                  tbody.innerHTML = '';
                  if (filteredData.length > 0) {
                    filteredData.forEach(record => {
                      const row = document.createElement('tr');
                      row.innerHTML = `
                      <td>${record.id || 'N/A'}</td>
                      <td>${record.Product_Name || 'N/A'}</td>
                      <td>${record.Sale_Date || 'N/A'}</td>
                      <td>${record.Profit_Loss || 'N/A'}</td>
                      <td>${record.Result_Type || 'N/A'}</td>
                  `;
                      tbody.appendChild(row);
                    });
                  } else {
                    tbody.innerHTML = '<tr><td colspan="5">No data available for this period.</td></tr>';
                  }
                }
              </script>

              <!-- New Client Card -->
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2 position-relative">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="d-flex justify-content-between align-items-center">
                          <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            New (Client)</div>
                          <div class="dropdown">
                            <i class="fa-solid fa-caret-down text-primary" data-toggle="dropdown" style="cursor:pointer;"></i>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                              <button class="dropdown-item active" onclick="filterData('today')">Today</button>
                              <button class="dropdown-item" onclick="filterData('this_week')">This Week</button>
                              <button class="dropdown-item" onclick="filterData('this_month')">This Month</button>
                            </div>
                          </div>
                        </div>

                        <?php
                        // Assume $Reports is your array of sales reports
                        $createdAtList = array();

                        // Loop through each report to collect 'created_at' values
                        foreach ($Reports as $report) {
                          if (!empty($report['created_at'])) { // make sure it's not empty
                            $createdAtList[] = $report['created_at'];
                          }
                        }

                        // Remove duplicates
                        $uniqueCreatedAt = array_unique($createdAtList);

                        // Count unique created_at values
                        $uniqueCreatedAtCount = count($uniqueCreatedAt);
                        ?>
                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                          <?php echo $uniqueCreatedAtCount; ?> Client
                        </div>
                      </div>
                      <div class=" col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="card-body" style="display: none;">
                <div class="table-responsive">
                  <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($Reports as $report): ?>
                        <tr>
                          <td><?php echo $report['id']; ?></td>
                          <td><?php echo $report['product_name']; ?></td>
                          <td><?php echo $report['quantity']; ?></td>
                          <td>$<?php echo number_format($report['total_price'], 2); ?></td>
                          <td><?php echo $report['created_at']; ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="5" class="text-right font-weight-bold">
                          Unique Created Dates: <?php echo $uniqueCreatedAtCount; ?>
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>

              <!-- Incoming Card -->
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2 position-relative">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="d-flex justify-content-between align-items-center">
                          <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Incoming</div>
                          <div class="dropdown">
                            <i class="fa-solid fa-caret-down text-primary" data-toggle="dropdown" style="cursor:pointer;"></i>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                              <button class="dropdown-item active" onclick="filterData('today')">Today</button>
                              <button class="dropdown-item" onclick="filterData('this_week')">This Week</button>
                              <button class="dropdown-item" onclick="filterData('this_month')">This Month</button>
                            </div>
                          </div>
                        </div>
                        <div class="row justify-content-center align-items-center text-center">
                          <div class="col-auto">
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                              $<?php echo number_format($Total_Reports_Sales, 2); ?>
                            </div>
                          </div>
                        </div>

                      </div>
                      <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body" style="display: none;">
                <?php
                // Assume $Reports is your array of sales reports
                $createdAtList = array();

                // Loop through each report to collect 'created_at' values
                foreach ($Reports as $report) {
                  if (!empty($report['created_at'])) { // make sure it's not empty
                    $createdAtList[] = $report['created_at'];
                  }
                }

                // Remove duplicates
                $uniqueCreatedAt = array_unique($createdAtList);

                // Count unique created_at values
                $uniqueCreatedAtCount = count($uniqueCreatedAt);
                ?>
                <div class="table-responsive">
                  <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($Reports as $report): ?>
                        <tr>
                          <td><?php echo $report['id']; ?></td>
                          <td><?php echo $report['product_name']; ?></td>
                          <td><?php echo $report['quantity']; ?></td>
                          <td>$<?php echo number_format($report['total_price'], 2); ?></td>
                          <td><?php echo $report['created_at']; ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="3" class="text-right font-weight-bold">All-Time Total Sales:</td>
                        <td class="font-weight-bold">$<?php echo number_format($Total_Reports_Sales, 2); ?></td>
                        <td></td>
                      </tr>
                      <tr>
                        <td colspan="5" class="text-right font-weight-bold">
                          Unique Created Dates: <?php echo $uniqueCreatedAtCount; ?>
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>


              <!-- Expense Card -->
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2 position-relative">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="d-flex justify-content-between align-items-center">
                          <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Expenes <!-- Updated label -->
                          </div>
                          <div class="dropdown">
                            <i class="fa-solid fa-caret-down text-warning" data-toggle="dropdown" style="cursor:pointer;"></i>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                              <button class="dropdown-item active" onclick="filterExpense('today')">Today</button>
                              <button class="dropdown-item" onclick="filterExpense('this_week')">This Week</button>
                              <button class="dropdown-item" onclick="filterExpense('this_month')">This Month</button>
                            </div>
                          </div>
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 total-expense">$<?php echo number_format($Total_Inventory_Value ?? 0, 2); ?></div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card shadow mb-4" style="display: none;">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Inventory List</h6>
                </div>
                <div class="card-body" style="display: none;">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total Price</th> <!-- Per-item total -->
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($Inventory_Items as $item): ?>
                        <tr>
                          <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                          <td><?php echo $item['quantity']; ?></td>
                          <td>$<?php echo number_format($item['amount'], 2); ?></td>
                          <td>$<?php echo number_format($item['quantity'] * $item['amount'], 2); ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                      <?php
                      $grandTotalPrice = array_reduce($Inventory_Items, function ($sum, $item) {
                        return $sum + ($item['quantity'] * $item['amount']);
                      }, 0);
                      ?>
                      <tr>
                        <td colspan="3" class="text-right font-weight-bold">Grand Total Price:</td>
                        <td class="font-weight-bold">$<?php echo number_format($grandTotalPrice, 2); ?></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>

            </div>
          </div>



          <!-- Content Row -->

          <div class="row">

            <!-- Area Chart -->
            <!-- Include Chart.js (add this in your HTML head or before the script) -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



            <!-- Sales Report Table (First Snippet) -->
            <div class="card-body" style="display: none;">
              <?php
              // Assume $Reports is your array of sales reports
              $createdAtList = array();
              $grandTotalPrice = 0;
              $totalsByDate = array();

              foreach ($Reports as $report) {
                if (!empty($report['created_at'])) {
                  $createdAtList[] = $report['created_at'];
                  $date = $report['created_at'];
                  if (!isset($totalsByDate[$date])) {
                    $totalsByDate[$date] = 0;
                  }
                  $totalsByDate[$date] += $report['total_price'];
                }
                if (!empty($report['total_price'])) {
                  $grandTotalPrice += $report['total_price'];
                }
              }


              // Calculate percentages (avoid division by zero)
              $lowPercent = $totalFiltered > 0 ? ($lowCount / $totalFiltered) * 100 : 0;
              $mediumPercent = $totalFiltered > 0 ? ($mediumCount / $totalFiltered) * 100 : 0;
              $highPercent = $totalFiltered > 0 ? ($highCount / $totalFiltered) * 100 : 0;

              // Add filtering based on selected category for Inventory Quantities
              $selected_category = isset($_POST['category_id']) ? $_POST['category_id'] : '';
              $filtered_tracking = $selected_category ?
                array_filter($tracking, function ($item) use ($selected_category) {
                  return $item['category_id'] == $selected_category;
                }) : $tracking;


              ?>
              <div class="table-responsive">
                <table class="table table-bordered" id="salesTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Product Name</th>
                      <th>Quantity</th>
                      <th>Total Price</th>
                      <th>Created At</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($Reports as $report): ?>
                      <tr>
                        <td><?php echo $report['id']; ?></td>
                        <td><?php echo $report['product_name']; ?></td>
                        <td><?php echo $report['quantity']; ?></td>
                        <td data-price="<?php echo $report['total_price']; ?>">$<?php echo number_format($report['total_price'], 2); ?></td>
                        <td><?php echo $report['created_at']; ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="3" class="text-right font-weight-bold">Grand Total Price:</td>
                      <td class="font-weight-bold">$<?php echo number_format($grandTotalPrice, 2); ?></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td colspan="5" class="text-right font-weight-bold">
                        Unique Created Dates: <?php echo $uniqueCreatedAtCount; ?>
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>

            <!-- Earnings Overview Chart (Second Snippet) -->
            <div class="col-xl-8 col-lg-7">
              <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
                  <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                      <div class="dropdown-header">Dropdown Header:</div>
                      <a class="dropdown-item" href="#">Action</a>
                      <a class="dropdown-item" href="#">Another action</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="chart-area" style="width: 80%; margin: 0 auto;">
                    <canvas id="myAreaChart"></canvas>
                  </div>
                </div>
              </div>
            </div>

            <!-- Include Chart.js -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <!-- JavaScript to Retrieve Data from Table and Render Chart -->
            <script>
              // Function to extract data from the table
              function getDataFromTable() {
                const table = document.getElementById('salesTable');
                const rows = table.querySelectorAll('tbody tr');
                const dataByDate = {};

                // Loop through table rows
                rows.forEach(row => {
                  const cells = row.querySelectorAll('td');
                  const totalPrice = parseFloat(cells[3].getAttribute('data-price')); // Use data-price attribute
                  const createdAt = cells[4].textContent;

                  // Aggregate totals by date
                  if (!dataByDate[createdAt]) {
                    dataByDate[createdAt] = 0;
                  }
                  dataByDate[createdAt] += totalPrice;
                });

                // Convert to arrays for Chart.js
                const dates = Object.keys(dataByDate);
                const totals = Object.values(dataByDate);

                return {
                  dates,
                  totals
                };
              }

              // Render the chart
              document.addEventListener('DOMContentLoaded', function() {
                const {
                  dates,
                  totals
                } = getDataFromTable();
                const ctx = document.getElementById('myAreaChart').getContext('2d');

                new Chart(ctx, {
                  type: 'line',
                  data: {
                    labels: dates,
                    datasets: [{
                      label: 'Total Sales',
                      data: totals,
                      fill: true,
                      backgroundColor: 'rgba(78, 115, 223, 0.2)',
                      borderColor: 'rgba(78, 115, 223, 1)',
                      tension: 0.4
                    }]
                  },
                  options: {
                    scales: {
                      x: {
                        title: {
                          display: true,
                          text: 'Date'
                        },
                        ticks: {
                          display: false
                        } // Hide the date labels under the chart
                      },
                      y: {
                        title: {
                          display: true,
                          text: 'Total Price ($)'
                        },
                        beginAtZero: true
                      }
                    },
                    plugins: {
                      legend: {
                        display: true
                      }
                    }
                  }
                });
              });
            </script>

            <?php
            // Sample data (replace with your actual $tracking array)
            $tracking = [
              ['category_id' => 1, 'image' => 'fan.jpg', 'product_name' => 'Shaeleigh English', 'quantity' => 74],
              ['category_id' => 2, 'image' => 'bag.jpg', 'product_name' => 'Laura Chambers', 'quantity' => 42],
              ['category_id' => 3, 'image' => 'tools.jpg', 'product_name' => 'Vincent Santiago', 'quantity' => 19],
              ['category_id' => 4, 'image' => 'fan2.jpg', 'product_name' => 'Lillian Sykes', 'quantity' => 57],
            ];

            // Initialize counters for statuses
            $lowCount = 0;
            $mediumCount = 0;
            $highCount = 0;
            $totalFiltered = 0;

            // Filter products and count statuses
            foreach ($tracking as $item) {
              $quantity = $item['quantity'];

              // Only process items with quantity >= 0 (including 0)
              if ($quantity < 0) {
                continue;
              }

              // Increment total filtered products
              $totalFiltered++;

              // Determine status
              $status = '';
              if ($quantity >= 50) {
                $status = 'High';
                $highCount++;
              } elseif ($quantity >= 10) {
                $status = 'Medium';
                $mediumCount++;
              } else { // This now explicitly includes quantity = 0
                $status = 'Low';
                $lowCount++;
              }
            }

            // Calculate percentages (avoid division by zero)
            $lowPercent = $totalFiltered > 0 ? ($lowCount / $totalFiltered) * 100 : 0;
            $mediumPercent = $totalFiltered > 0 ? ($mediumCount / $totalFiltered) * 100 : 0;
            $highPercent = $totalFiltered > 0 ? ($highCount / $totalFiltered) * 100 : 0;
            ?>


            <div class="col-xl-4 col-lg-5">
              <div class="card shadow mb-4">
                <!-- Project Card Example -->
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Products (Stock Status)</h6>
                </div>
                <div class="card-body" style="height: 368px;">
                  <h4 class="small text-left">Low (0-9)<span class="float-right"><?php echo round($lowPercent); ?>%</span></h4>
                  <div class="progress mb-4" style="height: 15px;">
                    <div class="progress-bar" role="progressbar" style="width: <?php echo $lowPercent; ?>%; height: 100%; background-color: rgb(226, 160, 160);"
                      aria-valuenow="<?php echo $lowPercent; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <h4 class="small text-left">Medium (10-49)<span class="float-right"><?php echo round($mediumPercent); ?>%</span></h4>
                  <div class="progress mb-4" style="height: 15px;">
                    <div class="progress-bar" role="progressbar" style="width: <?php echo $mediumPercent; ?>%; height: 100%; background-color: rgb(253, 215, 144);"
                      aria-valuenow="<?php echo $mediumPercent; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <h4 class="small text-left">High (50+)<span class="float-right"><?php echo round($highPercent); ?>%</span></h4>
                  <div class="progress mb-4" style="height: 15px;">
                    <div class="progress-bar" role="progressbar" style="width: <?php echo $highPercent; ?>%; height: 100%; background-color: rgb(154, 154, 255);"
                      aria-valuenow="<?php echo $highPercent; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <h4 class="small text-left">Total Items<span class="float-right"><?php echo $totalFiltered; ?> items</span></h4>
                  <div class="progress mb-4" style="height: 15px;">
                    <div class="progress-bar" role="progressbar" style="width: 100%; height: 100%; background-color: lightblue;"
                      aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <h4 class="small text-left">Processing Complete<span class="float-right">Done!</span></h4>
                  <div class="progress" style="height: 15px;">
                    <div class="progress-bar" role="progressbar" style="width: 100%; height: 100%; background-color: rgb(165, 253, 165);"
                      aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Dashboards procress done -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
              <div class="container my-auto">
                <div class="copyright text-center my-auto">
                  <span>created by G5 team & PNC student 2025</span>
                </div>
              </div>
            </footer>
            <!-- End of Footer -->

          </div>
          <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
          <i class="fas fa-angle-up"></i>
        </a>

        <script src="../../views/assets/js/demo/chart-area-demo.js"></script>
      </div>
  </div>
  </div>
  </body>
  </div>

  <!-- jQuery + Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

