<?php
// views/dashboards/list.php
require_once './views/layouts/side.php';
?>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper" style="margin-left: 250px;">
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <?php require_once './views/layouts/nav.php'; ?>
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="row">
                        <!-- Profit Card -->
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

                        <!-- Hidden Profit/Loss Table -->
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
                                <!-- Rows populated by JavaScript -->
                            </tbody>
                        </table>

                        <!-- New Client Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2 position-relative">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                    New (Client)
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
                                            <?php
                                            $createdAtList = [];
                                            foreach ($Reports as $report) {
                                                if (!empty($report['created_at'])) {
                                                    $createdAtList[] = $report['created_at'];
                                                }
                                            }
                                            $uniqueCreatedAt = array_unique($createdAtList);
                                            $uniqueCreatedAtCount = count($uniqueCreatedAt);
                                            ?>
                                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                <?php echo $uniqueCreatedAtCount; ?> Client
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
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

                        <!-- Expense Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2 position-relative">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                    Expenses
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
                                            <div class="h5 mb-0 font-weight-bold text-gray-800 total-expense">$<?php echo number_format($Max_Expense ?? 0, 2); ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cart Section -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-4" style="display:none">
                                <div class="card-body">
                                    <?php if (empty($Cart)): ?>
                                    <?php else: ?>
                                        <table class="table table-bordered" id="cartTable">
                                            <thead>
                                                <tr>
                                                    <th>Product Name</th>
                                                    <th>Unit Price</th>
                                                    <th>Quantity</th>
                                                    <th>Total Price</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($Cart as $item): ?>
                                                    <tr data-product-id="<?php echo $item['product_id']; ?>">
                                                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                                        <td>$<?php echo number_format($item['unit_price'], 2); ?></td>
                                                        <td>
                                                            <input type="number" class="form-control quantity-input" 
                                                                   value="<?php echo $item['quantity']; ?>" 
                                                                   min="0" style="width: 100px;">
                                                        </td>
                                                        <td class="item-total">$<?php echo number_format($item['total_price'], 2); ?></td>
                                                        <td>
                                                            <button class="btn btn-danger btn-sm remove-item">Remove</button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3" class="text-right font-weight-bold">Total:</td>
                                                    <td class="font-weight-bold" id="cart-total">$<?php echo number_format($Cart_Total, 2); ?></td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <button class="btn btn-primary" id="checkout-btn">Proceed to Checkout</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- Hidden Sales Report Table -->
                        <div class="card-body" style="display: none;">
                            <?php
                            $createdAtList = [];
                            $grandTotalPrice = 0;
                            foreach ($Reports as $report) {
                                if (!empty($report['created_at'])) {
                                    $createdAtList[] = $report['created_at'];
                                }
                                if (!empty($report['total_price'])) {
                                    $grandTotalPrice += $report['total_price'];
                                }
                            }
                            $uniqueCreatedAt = array_unique($createdAtList);
                            $uniqueCreatedAtCount = count($uniqueCreatedAt);
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
                                            <td class="font-weight-bold">$<?php echo number_format($stockStatus['totalFiltered'], 2); ?></td>
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

                        <!-- Earnings Overview Chart -->
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

                        <!-- Products (Stock Status) -->
                        <?php
                        $stockStatus = $Stock_Status ?? ['lowCount' => 0, 'mediumCount' => 0, 'highCount' => 0, 'totalFiltered' => 0, 'lowPercent' => 0, 'mediumPercent' => 0, 'highPercent' => 0];
                        ?>
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Products (Stock Status)</h6>
                                </div>
                                <div class="card-body" style="height: 368px;">
                                    <h4 class="small text-left">Low (0-9)<span class="float-right low-percent"><?php echo $stockStatus['lowPercent']; ?>%</span></h4>
                                    <div class="progress mb-4" style="height: 15px;">
                                        <div class="progress-bar low-progress" role="progressbar" style="width: <?php echo $stockStatus['lowPercent']; ?>%; height: 100%; background-color: rgb(226, 160, 160);"
                                            aria-valuenow="<?php echo $stockStatus['lowPercent']; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small text-left">Medium (10-49)<span class="float-right medium-percent"><?php echo $stockStatus['mediumPercent']; ?>%</span></h4>
                                    <div class="progress mb-4" style="height: 15px;">
                                        <div class="progress-bar medium-progress" role="progressbar" style="width: <?php echo $stockStatus['mediumPercent']; ?>%; height: 100%; background-color: rgb(253, 215, 144);"
                                            aria-valuenow="<?php echo $stockStatus['mediumPercent']; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small text-left">High (50+)<span class="float-right high-percent"><?php echo $stockStatus['highPercent']; ?>%</span></h4>
                                    <div class="progress mb-4" style="height: 15px;">
                                        <div class="progress-bar high-progress" role="progressbar" style="width: <?php echo $stockStatus['highPercent']; ?>%; height: 100%; background-color: rgb(154, 154, 255);"
                                            aria-valuenow="<?php echo $stockStatus['highPercent']; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small text-left">Total Items<span class="float-right total-items"><?php echo $stockStatus['totalFiltered']; ?> items</span></h4>
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

                        <!-- Footer -->
                        <footer class="sticky-footer bg-white">
                            <div class="container my-auto">
                                <div class="copyright text-center my-auto">
                                    <span>created by G5 team & PNC student 2025</span>
                                </div>
                            </div>
                        </footer>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll to Top Button -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Profit Filtering Script -->
    <script>
        const salesData = <?php echo json_encode($Profit_Loss ?? []); ?>;
        console.log('Sales Data:', salesData);

        const storedProfit = localStorage.getItem('totalProfit');
        const storedPeriod = localStorage.getItem('selectedPeriod');
        if (storedProfit && storedPeriod) {
            document.querySelector('.total-profit').textContent = `$${parseFloat(storedProfit).toFixed(2)}`;
        }

        function filterData(period) {
            const today = new Date();
            let startDate, endDate;

            if (period === 'today') {
                startDate = new Date(today.setHours(0, 0, 0, 0));
                endDate = new Date(today.setHours(23, 59, 59, 999));
            } else if (period === 'this_week') {
                startDate = new Date(today);
                startDate.setDate(today.getDate() - today.getDay() + 1);
                startDate.setHours(0, 0, 0, 0);
                endDate = new Date(startDate);
                endDate.setDate(startDate.getDate() + 6);
                endDate.setHours(23, 59, 59, 999);
            } else if (period === 'this_month') {
                startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                endDate.setHours(23, 59, 59, 999);
            }

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

            document.querySelector('.total-profit').textContent = `$${totalProfit.toFixed(2)}`;
            localStorage.clear();
            localStorage.setItem('totalProfit', totalProfit);
            localStorage.setItem('selectedPeriod', period);

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

        function filterExpense(period) {
            fetch(`/dashboard/get_inventory_data?period=${period}`)
                .then(response => response.json())
                .then(data => {
                    document.querySelector('.total-expense').textContent = `$${data.maxExpense}`;
                })
                .catch(error => console.error('Error fetching expenses:', error));
        }

        // Chart Rendering Script
        function getDataFromTable() {
            const table = document.getElementById('salesTable');
            const rows = table.querySelectorAll('tbody tr');
            const dataByDate = {};

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const totalPrice = parseFloat(cells[3].getAttribute('data-price'));
                const createdAt = cells[4].textContent;

                if (!dataByDate[createdAt]) {
                    dataByDate[createdAt] = 0;
                }
                dataByDate[createdAt] += totalPrice;
            });

            return {
                dates: Object.keys(dataByDate),
                totals: Object.values(dataByDate)
            };
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Chart initialization
            const { dates, totals } = getDataFromTable();
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
                            title: { display: true, text: 'Date' },
                            ticks: { display: false }
                        },
                        y: {
                            title: { display: true, text: 'Total Price ($)' },
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: { display: true }
                    }
                }
            });

            // Update Stock Status from localStorage or API
            const stockStatusData = JSON.parse(localStorage.getItem('stockStatusData'));
            if (stockStatusData) {
                updateStockStatus(stockStatusData);
            } else {
                fetch('/dashboard/get_stock_status', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ category_id: '' })
                })
                    .then(response => response.json())
                    .then(data => updateStockStatus(data))
                    .catch(error => console.error('Error fetching stock status:', error));
            }

            // Cart JavaScript
            $('.quantity-input').on('change', function() {
                const $row = $(this).closest('tr');
                const productId = $row.data('product-id');
                const quantity = parseInt($(this).val());

                if (isNaN(quantity) || quantity < 0) {
                    alert('Please enter a valid quantity');
                    $(this).val(0);
                    return;
                }

                $.ajax({
                    url: '/dashboard/update_cart',
                    method: 'POST',
                    data: { product_id: productId, quantity: quantity },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#cart-total').text('$' + response.cart_total);
                            if (quantity === 0) {
                                $row.remove();
                            }
                            if ($('#cartTable tbody tr').length === 0) {
                                $('#cartTable').replaceWith('<p>Your cart is empty.</p>');
                            }
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert('Error updating cart');
                    }
                });
            });

            $('.remove-item').on('click', function() {
                const $row = $(this).closest('tr');
                const productId = $row.data('product-id');

                $.ajax({
                    url: '/dashboard/update_cart',
                    method: 'POST',
                    data: { product_id: productId, quantity: 0 },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $row.remove();
                            $('#cart-total').text('$' + response.cart_total);
                            if ($('#cartTable tbody tr').length === 0) {
                                $('#cartTable').replaceWith('<p>Your cart is empty.</p>');
                            }
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert('Error removing item');
                    }
                });
            });

            $('#checkout-btn').on('click', function() {
                $.ajax({
                    url: '/dashboard/checkout',
                    method: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            window.location.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert('Error processing order');
                    }
                });
            });
        });

        function updateStockStatus(data) {
            document.querySelector('.low-percent').textContent = `${data.lowPercent}%`;
            document.querySelector('.medium-percent').textContent = `${data.mediumPercent}%`;
            document.querySelector('.high-percent').textContent = `${data.highPercent}%`;
            document.querySelector('.total-items').textContent = `${data.totalFiltered} items`;

            document.querySelector('.low-progress').style.width = `${data.lowPercent}%`;
            document.querySelector('.medium-progress').style.width = `${data.mediumPercent}%`;
            document.querySelector('.high-progress').style.width = `${data.highPercent}%`;
        }
    </script>
</body>