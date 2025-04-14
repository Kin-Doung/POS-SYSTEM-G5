<?php
require_once './views/layouts/header.php';
require_once './views/layouts/side.php';
?>
<style>
    /* Main content wrapper to account for sidebar */
    .main-content {
        margin-left: 250px;
        padding: 20px;
        background-color: #f8f9fa;
        height: auto;
    }

    .table-container {
        border-radius: 10px;
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-family: 'Segoe UI', Arial, sans-serif;
    }

    th,
    td {
        padding: 15px;
        text-align: left;
        border: none;
        /* Remove default borders */
        transition: background-color 0.3s ease;
    }

    th {
        background-color: #5cbacc;
        /* Simple solid color instead of gradient */
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 14px;
        letter-spacing: 1px;
        position: sticky;
        top: 0;
        z-index: 1;
    }

    th:first-child {
        border-top-left-radius: 10px;
    }

    th:last-child {
        border-top-right-radius: 10px;
    }

    td {
        vertical-align: middle;
        background-color: #fff;
        border-bottom: 1px solid #e5e7eb;
    }

    tr:last-child td {
        border-bottom: none;
    }

    tr:nth-child(even) td {
        background-color: #f9fafb;
        /* Subtle zebra striping */
    }

    tr:hover td {
        background-color: #eef2ff;
        /* Light hover effect */
        cursor: pointer;
    }

    img {
        max-width: 50px;
        height: auto;
    }

    .remove-btn {
        background: linear-gradient(45deg, #ff4d4d, #ff7878);
        /* Gradient button */
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 25px;
        /* Rounded pill shape */
        cursor: pointer;
        font-size: 13px;
        font-weight: 500;
        box-shadow: 0 2px 5px rgba(255, 77, 77, 0.3);
        transition: all 0.3s ease;
    }

    .remove-btn:hover {
        background: linear-gradient(45deg, #cc0000, #ff4d4d);
        transform: translateY(-2px);
        /* Lift effect */
        box-shadow: 0 4px 10px rgba(255, 77, 77, 0.4);
    }

    .delete-btn {
        background: linear-gradient(45deg, #ff4d4d, #ff7878);
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 25px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 500;
        box-shadow: 0 2px 5px rgba(255, 77, 77, 0.3);
        transition: all 0.3s ease;
        display: none;
        /* Hidden by default */
        margin-top: 10px;
    }

    .delete-btn:hover {
        background: linear-gradient(45deg, #cc0000, #ff4d4d);
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(255, 77, 77, 0.4);
    }

    input[type="checkbox"] {
        cursor: pointer;
        accent-color: #2575fc;
        width: 16px;
        height: 16px;
        border-radius: 4px;
    }

    .total-price {
        margin-top: 20px;
        padding: 15px;
        background-color: #f1f5f9;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
        text-align: right;
    }

    .total-price span {
        color: #16a34a;
        /* Green for the total value */
        font-size: 18px;
    }

    /* Filter and Search Styles */
    .filter-search-container {
        display: flex;
        flex-direction: column;
        /* Stack filter-date-wrapper and search vertically */
        gap: 15px;
        margin-bottom: 20px;
    }

    .filter-date-wrapper {
        display: flex;
        flex-wrap: nowrap;
        /* Prevent wrapping to ensure one line */
        align-items: center;
        gap: 15px;
        /* Space between filter buttons and date filters */
        overflow-x: auto;
        /* Allow horizontal scrolling if content overflows */
        white-space: nowrap;
        /* Prevent text wrapping */
    }

    .filter-buttons {
        display: flex;
        gap: 10px;
        /* Space between buttons */
    }

    .filter-buttons button {
        background: #5cbacc;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filter-buttons button:hover,
    .filter-buttons button.active {
        background: #4a0e8f;
        transform: translateY(-1px);
    }

    .date-filter {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .date-filter label {
        font-size: 14px;
        font-weight: 500;
        color: #1e293b;
        margin-right: 5px;
    }

    .date-filter input[type="date"] {
        padding: 8px 10px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.3s ease;
        width: 180px;
    }

    .date-filter input[type="date"]:focus {
        border-color: #6a11cb;
    }

    .search-container {
        width: 400px;
        /* Fixed width for consistency */
        background: none;
        margin-left: 665px;
        margin-top: -10px;
        margin-bottom: 10px;
    }

    .search-container input {
        padding: 8px 35px 8px 15px;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        font-size: 14px;
        width: 100%;
        outline: none;
        transition: border-color 0.3s ease;
    }

    .search-container input:focus {
        border-color: #6a11cb;
    }

    .search-container::after {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 16px;
        color: #6a11cb;
    }

    /* Custom Message Styles */
    .message {
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: #16a34a;
        /* Green background for success */
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.5s ease-in-out;
    }

    .message.show {
        opacity: 1;
    }

    /* Custom Confirmation Modal Styles */
    .confirm-modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        /* Semi-transparent overlay */
        z-index: 1000;
        justify-content: center;
        align-items: center;
    }

    .confirm-modal.show {
        display: flex;
        /* Show when active */
    }

    .confirm-modal-content {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        text-align: center;
        max-width: 400px;
        width: 90%;
    }

    .confirm-modal-content p {
        font-size: 16px;
        font-weight: 500;
        color: #1e293b;
        margin-bottom: 20px;
    }

    .confirm-modal-buttons {
        display: flex;
        justify-content: center;
        gap: 15px;
    }

    .confirm-modal-buttons button {
        padding: 8px 20px;
        border: none;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .confirm-modal-buttons #confirm-yes {
        background: #5cbacc;
        /* Match filter button color */
        color: white;
    }

    .confirm-modal-buttons #confirm-yes:hover {
        background: #4a9bb0;
        /* Slightly darker shade on hover */
        transform: translateY(-2px);
        box-shadow: 0 2px 5px rgba(92, 186, 204, 0.3);
    }

    .confirm-modal-buttons #confirm-no {
        background: linear-gradient(45deg, #ff4d4d, #ff7878);
        /* Match delete button gradient */
        color: white;
    }

    .confirm-modal-buttons #confirm-no:hover {
        background: linear-gradient(45deg, #cc0000, #ff4d4d);
        transform: translateY(-2px);
        box-shadow: 0 2px 5px rgba(255, 77, 77, 0.3);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
            /* Collapse sidebar on small screens */
        }

        .table-container {
            padding: 10px;
        }

        th,
        td {
            padding: 10px;
            font-size: 12px;
        }

        .remove-btn,
        .delete-btn {
            padding: 6px 10px;
            font-size: 12px;
        }

        .total-price {
            font-size: 14px;
            text-align: center;
        }

        .filter-date-wrapper {
            flex-wrap: nowrap;
            /* Keep nowrap for horizontal scrolling */
            gap: 10px;
        }

        .date-filter {
            flex-direction: row;
            /* Keep date inputs in a row */
            gap: 10px;
        }

        .search-container {
            width: 100%;
            /* Full width on small screens */
            margin-left: 0;
            /* Reset margin for small screens */
            margin-top: 10px;
        }

        .message {
            top: 10px;
            right: 10px;
            width: 80%;
            margin: 0 auto;
        }

        .confirm-modal-content {
            padding: 15px;
            width: 80%;
        }

        .confirm-modal-content p {
            font-size: 14px;
        }

        .confirm-modal-buttons button {
            padding: 6px 15px;
            font-size: 13px;
        }
    }
</style>

<div style="display: none;">
    <?php require_once './views/layouts/nav.php' ?>
</div>
<div class="main-content">



    <!-- Filter Buttons, Date Range, and Search Bar -->
    <div class="filter-search-container">
        <div class="filter-date-wrapper">
            <div class="filter-buttons">
                <button class="filter-btn active" data-filter="all">All</button>
                <button class="filter-btn" data-filter="today">Today</button>
                <button class="filter-btn" data-filter="this-week">This Week</button>
                <button class="filter-btn" data-filter="last-week">Last Week</button>
                <button class="filter-btn" data-filter="this-month">This Month</button>
                <button class="filter-btn" data-filter="last-month">Last Month</button>
            </div>
            <div class="date-filter">
                <div>
                    <label for="start-date">Choose date:</label>
                    <input type="date" id="start-date" value="2000-01-01">
                </div>
                <div>
                    <input type="date" id="end-date" value="2099-12-31">
                </div>
            </div>
        </div>
    </div>

    <div class="search-container">
        <input type="text" id="search-input" placeholder="Search by Product Name...">
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all" title="Select All"></th>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total Price</th>
                    <th>Date of Sale</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="purchase-table">
                <?php foreach ($reports as $report) : ?>
                    <tr data-date="<?= $report['created_at'] ? date('Y-m-d', strtotime($report['created_at'])) : 'N/A' ?>">
                        <td><input type="checkbox" class="select-item" data-id="<?= $report['id'] ?>"></td>
                        <td><img src="<?= $report['image'] ?>" alt="Product Image" width="50"></td>
                        <td><?= htmlspecialchars($report['product_name']) ?></td>
                        <td><?= $report['quantity'] ?></td>
                        <td><?= $report['price'] ?>$</td>
                        <td><?= $report['total_price'] ?>$</td>
                        <td><?= $report['created_at'] ? date('Y-m-d', strtotime($report['created_at'])) : 'N/A' ?></td>
                        <td><button class="remove-btn" data-id="<?= $report['id'] ?>">Remove</button></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <div class="pagination">
            <?php if ($currentPage > 1): ?>
                <button class="page-btn" data-page="<?= $currentPage - 1 ?>"><i class="fa-solid fa-less-than"></i></button>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <button class="page-btn <?= $i == $currentPage ? 'active' : '' ?>" data-page="<?= $i ?>"><?= $i ?></button>
            <?php endfor; ?>
            <?php if ($currentPage < $totalPages): ?>
                <button class="page-btn" data-page="<?= $currentPage + 1 ?>"><i class="fa-solid fa-greater-than"></i></button>
            <?php endif; ?>
        </div>
    </div>
    <style>
        .pagination {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .page-btn {
            padding: 5px 15px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;


        }

        .page-btn:hover {
            background-color: #0056b3;
        }

        .page-btn.active {
            background-color: #0056b3;
            font-weight: bold;
        }

        @media (max-width: 600px) {
            .page-btn {
                padding: 8px 12px;
            }
        }
    </style>



    <div class="message" id="delete-message">Your product is deleted</div>


    <!-- Delete Button (Hidden by Default) -->
    <button class="delete-btn" id="delete-selected">Delete Selected</button>
    <!-- Custom Message Element -->
    <div class="message" id="delete-message">Your product is deleted</div>

    <!-- Custom Confirmation Modal -->
    <div class="confirm-modal" id="confirm-modal">
        <div class="confirm-modal-content">
            <p>Are you sure you want to delete the selected items?</p>
            <div class="confirm-modal-buttons">
                <button id="confirm-yes">Yes</button>
                <button id="confirm-no">No</button>
            </div>
        </div>
    </div>

    <!-- Total Price Display -->
    <div class="total-price">
        Total Price: <span>$</span>
    </div>
</div>


<script>
    // Select All Checkbox and UI Elements
    const selectAll = document.getElementById('select-all');
    const deleteBtn = document.getElementById('delete-selected');
    const deleteMessage = document.getElementById('delete-message');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const startDateInput = document.getElementById('start-date');
    const endDateInput = document.getElementById('end-date');
    const searchInput = document.getElementById('search-input');
    const tableBody = document.getElementById('purchase-table');
    const totalPriceSpan = document.querySelector('.total-price span');
    const modal = document.getElementById('confirm-modal');
    const confirmYes = document.getElementById('confirm-yes');
    const confirmNo = document.getElementById('confirm-no');
    const paginationContainer = document.querySelector('.pagination');

    let currentPage = <?= $currentPage ?: 1 ?>;

    // Function to Show Custom Message
    function showMessage() {
        deleteMessage.classList.add('show');
        setTimeout(() => deleteMessage.classList.remove('show'), 3000);
    }

    // Show/Hide Delete Button Based on Checkbox Selection
    function updateDeleteButtonVisibility() {
        const checkboxes = document.querySelectorAll('.select-item');
        const anyChecked = [...checkboxes].some(checkbox => checkbox.checked);
        deleteBtn.style.display = anyChecked ? 'block' : 'none';
    }

    // Original Fetch and Update Table (Unchanged)
    function fetchAndUpdateTable(filter = 'all', startDate = null, endDate = null, search = '') {
        const payload = {
            filter: filter,
            search: search
        };
        if (filter === 'all' && startDate && endDate) {
            payload.start_date = startDate;
            payload.end_date = endDate;
        }
        console.log('Sending payload:', payload);

        fetch('/history/fetchFilteredHistories', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams(payload)
            })
            .then(response => {
                console.log('Fetch Status:', response.status);
                if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                return response.json();
            })
            .then(data => {
                console.log('Received data:', data);
                if (data.success) {
                    console.log('Reports count:', data.reports.length);
                    tableBody.innerHTML = data.reports.length > 0 ? data.reports.map(report => `
                    <tr data-date="${report.created_at}">
                        <td><input type="checkbox" class="select-item" data-id="${report.id}"></td>
                        <td><img src="${report.image}" alt="Product Image" width="50"></td>
                        <td>${report.product_name}</td>
                        <td>${report.quantity}</td>
                        <td>${report.price}$</td>
                        <td>${report.total_price}$</td>
                        <td>${report.created_at}</td>
                        <td><button class="remove-btn" data-id="${report.id}">Remove</button></td>
                    </tr>
                `).join('') : '<tr><td colspan="8">No=No records found for this filter</td></tr>';
                    totalPriceSpan.textContent = `$${data.total_price}`;
                    attachRemoveListeners();
                    attachCheckboxListeners();
                } else {
                    console.log('Server error:', data.error);
                    alert('Failed to fetch data: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                alert('Error fetching data: ' + error.message);
            });
    }

    // Handle Remove Button Clicks
    function attachRemoveListeners() {
        document.querySelectorAll('.remove-btn').forEach(button => {
            button.removeEventListener('click', handleRemoveClick);
            button.addEventListener('click', handleRemoveClick);
        });
    }

    function handleRemoveClick() {
        const id = this.getAttribute('data-id');
        const row = this.closest('tr');

        modal.classList.add('show');
        confirmYes.onclick = () => {
            fetch(`/history/destroy/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({})
                })
                .then(response => {
                    console.log('Delete Status:', response.status);
                    if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    console.log('Delete Data:', data);
                    if (data.success) {
                        row.remove();
                        showMessage();
                        modal.classList.remove('show');
                        fetchAndUpdateTable(); // Refresh table to update total price
                    } else {
                        alert('Failed to delete: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Delete Error:', error);
                    alert('Error deleting item: ' + error.message);
                });
        };
        confirmNo.onclick = () => modal.classList.remove('show');
    }

    // Handle Checkbox Listeners
    function attachCheckboxListeners() {
        const checkboxes = document.querySelectorAll('.select-item');
        selectAll.removeEventListener('change', handleSelectAllChange);
        selectAll.addEventListener('change', handleSelectAllChange);

        checkboxes.forEach(checkbox => {
            checkbox.removeEventListener('change', handleCheckboxChange);
            checkbox.addEventListener('change', handleCheckboxChange);
        });
    }

    function handleSelectAllChange() {
        const checkboxes = document.querySelectorAll('.select-item');
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateDeleteButtonVisibility();
    }

    function handleCheckboxChange() {
        const checkboxes = document.querySelectorAll('.select-item');
        selectAll.checked = [...checkboxes].every(cb => cb.checked);
        updateDeleteButtonVisibility();
    }

    // New Pagination Functions (Added)
    function fetchAndUpdateTableWithPagination(filter = 'all', startDate = null, endDate = null, search = '', page = currentPage) {
        const payload = {
            filter: filter,
            search: search,
            page: page
        };
        if (filter === 'all' && startDate && endDate) {
            payload.start_date = startDate;
            payload.end_date = endDate;
        }
        console.log('Sending payload with page:', payload);

        fetch('/history/fetchFilteredHistories', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams(payload)
            })
            .then(response => {
                console.log('Fetch Status:', response.status);
                if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                return response.json();
            })
            .then(data => {
                console.log('Received data:', data);
                if (data.success) {
                    console.log('Reports count:', data.reports.length);
                    tableBody.innerHTML = data.reports.length > 0 ? data.reports.map(report => `
                    <tr data-date="${report.created_at}">
                        <td><input type="checkbox" class="select-item" data-id="${report.id}"></td>
                        <td><img src="${report.image}" alt="Product Image" width="50"></td>
                        <td>${report.product_name}</td>
                        <td>${report.quantity}</td>
                        <td>${report.price}$</td>
                        <td>${report.total_price}$</td>
                        <td>${report.created_at}</td>
                        <td><button class="remove-btn" data-id="${report.id}">Remove</button></td>
                    </tr>
                `).join('') : '<tr><td colspan="8">No records found for this filter</td></tr>';
                    totalPriceSpan.textContent = `$${data.total_price}`;
                    currentPage = data.currentPage;
                    updatePagination(data.currentPage, data.totalPages);
                    attachRemoveListeners();
                    attachCheckboxListeners();
                } else {
                    console.log('Server error:', data.error);
                    alert('Failed to fetch data: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                alert('Error fetching data: ' + error.message);
            });
    }

    function updatePagination(currentPage, totalPages) {
        let paginationHTML = '';
        if (currentPage > 1) {
            paginationHTML += `<button class="page-btn" data-page="${currentPage - 1}"><i class="fa-solid fa-less-than"></i></button>`;
        }
        for (let i = 1; i <= totalPages; i++) {
            paginationHTML += `<button class="page-btn ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</button>`;
        }
        if (currentPage < totalPages) {
            paginationHTML += `<button class="page-btn" data-page="${currentPage + 1}"><i class="fa-solid fa-greater-than"></i></button>`;
        }
        paginationContainer.innerHTML = paginationHTML;
        attachPageListeners();
    }

    function attachPageListeners() {
        const pageButtons = document.querySelectorAll('.page-btn');
        console.log('Attaching listeners to', pageButtons.length, 'buttons');
        pageButtons.forEach(button => {
            button.removeEventListener('click', handlePageClick);
            button.addEventListener('click', handlePageClick);
        });
    }

    function handlePageClick() {
        const page = parseInt(this.getAttribute('data-page'));
        console.log('Page clicked:', page);
        if (!isNaN(page)) {
            fetchAndUpdateTableWithPagination(
                document.querySelector('.filter-btn.active')?.getAttribute('data-filter') || 'all',
                startDateInput.value,
                endDateInput.value,
                searchInput.value,
                page
            );
        }
    }

    // Filter Button Clicks
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            const filter = this.getAttribute('data-filter');
            console.log('Filter clicked:', filter);
            fetchAndUpdateTableWithPagination(filter, null, null, searchInput.value, 1);
        });
    });

    // Date Input Changes
    startDateInput.addEventListener('change', () => {
        fetchAndUpdateTableWithPagination('all', startDateInput.value, endDateInput.value, searchInput.value, 1);
    });

    endDateInput.addEventListener('change', () => {
        fetchAndUpdateTableWithPagination('all', startDateInput.value, endDateInput.value, searchInput.value, 1);
    });

    // Search Input
    searchInput.addEventListener('input', () => {
        fetchAndUpdateTableWithPagination('all', startDateInput.value, endDateInput.value, searchInput.value, 1);
    });

    // Initial Load with Pagination
    fetchAndUpdateTableWithPagination();
    attachPageListeners();
</script>

<?php
require_once './views/layouts/footer.php';
?>