<?php require_once './views/layouts/side.php' ?>

<body id="page-top">
  <!-- Page Wrapper -->
  <div id="wrapper" style="margin-left: 250px;">
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">
        <?php require_once './views/layouts/nav.php' ?>

        <!-- Remove Nav bar that code with html
          using import navbar instead -->
        <!-- Begin Page Content -->
        <div class="container-fluid">


          <!-- Content Row -->
          <div class="row">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Today's (Money)</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">$40,000</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        New (Client)</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">$215,000</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Incomming
                      </div>
                      <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                          <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">50%</div>
                        </div>
                        <div class="col">
                          <div class="progress progress-sm mr-2">
                            <div class="progress-bar bg-info" role="progressbar"
                              style="width: 50%" aria-valuenow="50" aria-valuemin="0"
                              aria-valuemax="100"></div>
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

            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                        Today's (Expense)</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Content Row -->

          <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-8 col-lg-7">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div
                  class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
                  <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                      data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                      aria-labelledby="dropdownMenuLink">
                      <div class="dropdown-header">Dropdown Header:</div>
                      <a class="dropdown-item" href="#">Action</a>
                      <a class="dropdown-item" href="#">Another action</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                  </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div class="chart-area">
                    <canvas id="myAreaChart"></canvas>
                  </div>
                </div>
              </div>
            </div>


           

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