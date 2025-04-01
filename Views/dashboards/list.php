<?php require_once './views/layouts/side.php' ?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg bg-white ">
  <!-- Navbar -->
  <?php require_once './views/layouts/nav.php' ?>
  <!-- End Navbar -->
  <div class="container-fluid py-4"> 
    <div class="row d-flex justify-content-center align-item-center mb-3">
      <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="cards card-money">
          <div class="card-header p-3 pt-2">
            <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
              <i class="material-icons opacity-10">weekend</i>
            </div>
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize">Today's Money</p>
              <h4 class="mb-0">$53</h4>
            </div>
          </div>
          <!-- <hr class="dark horizontal my-0"> -->
          <div class="card-footer p-3 mt-n2">
            <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+55% </span>than last week</p>
          </div>
        </div>
      </div>
  
      <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="cards card-client">
          <div class="card-header p-3 pt-2">
            <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
              <i class="material-icons opacity-10">person</i>
            </div>
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize">New Clients</p>
              <h4 class="mb-0">100</h4>
            </div>
          </div>
          <!-- <hr class="dark horizontal my-0"> -->
          <div class="card-footer p-3 mt-n2">
            <p class="mb-0"><span class="text-danger text-sm font-weight-bolder">-2%</span> than yesterday</p>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6">
        <div class="cards card-sale">
          <div class="card-header p-3 pt-2">
            <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
              <i class="material-icons opacity-10">weekend</i>
            </div>
            <div class="text-end pt-1">
              <p class="text-sm mb-0 text-capitalize">Sales</p>
              <h4 class="mb-0">$103,430</h4>
            </div>
          </div>
          <!-- <hr class="dark horizontal my-0"> -->
          <div class="card-footer p-3 mt-n2 d-flex justify-content-center align-item-center">
            <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+5% </span>than yesterday</p>
          </div>
        </div>
      </div>
    </div>
    

    <div class="col-xl-12 grid-margin stretch-card flex-column">
  
    <div class="row h-100">
      <div class="col-md-12 stretch-card">
        <div class="card-chart" style="width: 75%; background: transparent; box-shadow: none; border: none;">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start flex-wrap">
              <div>
                <p class="mb-3 ms-5">Monthly Increase</p>
            
              </div>
              <div id="income-chart-legend" class="d-flex flex-wrap mt-1 mt-md-0"></div>
            </div>
            <canvas id="income-chart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  </div>
</main>
