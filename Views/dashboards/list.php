<?php require_once './views/layouts/side.php' ?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
  <!-- Navbar -->
  <?php require_once './views/layouts/nav.php' ?>
  <!-- End Navbar -->
  <div class="container-fluid py-4"> 
    <div class="row d-flex justify-content-center align-items-center mb-4" style="width: 100%; margin: 0 auto;">

      <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
        <div class="cards card-money shadow-sm">
          <div class="card-header p-3 pt-2">
            <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
              <i class="material-icons opacity-10">weekend</i>
            </div>
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize">Today's Money</p>
              <h4 class="mb-0">$53</h4>
            </div>
          </div>
          <div class="card-footer p-3 mt-n2">
            <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+55% </span>than last week</p>
          </div>
        </div>
      </div>
  
      <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
        <div class="cards card-client shadow-sm">
          <div class="card-header p-3 pt-2">
            <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
              <i class="material-icons opacity-10">person</i>
            </div>
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize">New Clients</p>
              <h4 class="mb-0">100</h4>
            </div>
          </div>
          <div class="card-footer p-3 mt-n2">
            <p class="mb-0"><span class="text-danger text-sm font-weight-bolder">-2%</span> than yesterday</p>
          </div>
        </div>
      </div>
      
      <div class="col-xl-4 col-sm-6">
        <div class="cards card-sale shadow-sm">
          <div class="card-header p-3 pt-2">
            <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
              <i class="material-icons opacity-10">weekend</i>
            </div>
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize">Sales</p>
              <h4 class="mb-0">$103,430</h4>
            </div>
          </div>
          <div class="card-footer p-3 mt-n2">
            <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+5% </span>than yesterday</p>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Charts Section -->
    <div class="row" style="width: 100%; margin: 0 auto;">
      <div class="col-12 mb-3">
        <h4 class="font-weight-bold text-dark">Monthly Increase</h4>
      </div>

      <!-- Area Chart -->
      <div class="col-xl-8 col-lg-8">
        <div class="card shadow-sm border-0 rounded-lg mb-4">
          <!-- Card Header - Dropdown -->
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
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

      <!-- Projects Card -->
      <div class="col-xl-4 col-lg-4">
        <div class="card shadow-sm border-0 rounded-lg mb-4">
          <!-- Project Card Example -->
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Projects</h6>
          </div>

          <div class="card-body" style="height: 368px;">
            <h4 class="small text-left">Server Migration <span class="float-right">20%</span></h4>
            <div class="progress mb-4" style="height: 15px; border-radius: 10px; overflow: hidden;">
              <div class="progress-bar" role="progressbar" style="width: 20%; height: 100%; background-color: rgb(226, 160, 160);"
                aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <h4 class="small text-left">Sales Tracking <span class="float-right">40%</span></h4>
            <div class="progress mb-4" style="height: 15px; border-radius: 10px; overflow: hidden;">
              <div class="progress-bar" role="progressbar" style="width: 40%; height: 100%; background-color: rgb(253, 215, 144);"
                aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <h4 class="small text-left">Customer Database <span class="float-right">60%</span></h4>
            <div class="progress mb-4" style="height: 15px; border-radius: 10px; overflow: hidden;">
              <div class="progress-bar" role="progressbar" style="width: 60%; height: 100%; background-color: rgb(154, 154, 255);"
                aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <h4 class="small text-left">Payout Details <span class="float-right">80%</span></h4>
            <div class="progress mb-4" style="height: 15px; border-radius: 10px; overflow: hidden;">
              <div class="progress-bar" role="progressbar" style="width: 80%; height: 100%; background-color: lightblue;"
                aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <h4 class="small text-left">Account Setup <span class="float-right">Complete!</span></h4>
            <div class="progress" style="height: 15px; border-radius: 10px; overflow: hidden;">
              <div class="progress-bar" role="progressbar" style="width: 100%; height: 100%; background-color: rgb(165, 253, 165);"
                aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <footer class="sticky-footer bg-white mt-4">
      <div class="container my-auto">
        <div class="copyright text-center my-auto">
          <span>created by G5 team & PNC student 2025</span>
        </div>
      </div>
    </footer>
    <!-- End of Footer -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>
  </div>
</main>

<script src="../../views/assets/js/demo/chart-area-demo.js"></script>
