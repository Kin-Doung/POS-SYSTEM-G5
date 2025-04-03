<?php
require_once './views/layouts/header.php';
require_once './views/layouts/side.php';
?>

<style>
    /* Main content wrapper to account for sidebar */
    .main-content {
        margin-left: 250px; /* Adjust this based on your sidebar width */
        padding: 20px;
        background-color: #f8f9fa; /* Light background for contrast */
    }

    .table-container {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-family: 'Segoe UI', Arial, sans-serif;
    }

    th, td {
        padding: 15px;
        text-align: left;
        border: none; /* Remove default borders */
        transition: background-color 0.3s ease;
    }

    th {
        background-color: #6a11cb; /* Simple solid color instead of gradient */
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
        background-color: #f9fafb; /* Subtle zebra striping */
    }

    tr:hover td {
        background-color: #eef2ff; /* Light hover effect */
        cursor: pointer;
    }

    img {
        max-width: 50px;
        height: auto;
    }

    .remove-btn {
        background: linear-gradient(45deg, #ff4d4d, #ff7878); /* Gradient button */
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 25px; /* Rounded pill shape */
        cursor: pointer;
        font-size: 13px;
        font-weight: 500;
        box-shadow: 0 2px 5px rgba(255, 77, 77, 0.3);
        transition: all 0.3s ease;
    }

    .remove-btn:hover {
        background: linear-gradient(45deg, #cc0000, #ff4d4d);
        transform: translateY(-2px); /* Lift effect */
        box-shadow: 0 4px 10px rgba(255, 77, 77, 0.4);
    }

    input[type="checkbox"] {
        cursor: pointer;
        accent-color: #2575fc; /* Custom checkbox color */
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
        color: #16a34a; /* Green for the total value */
        font-size: 18px;
    }

    /* Filter and Search Styles */
    .filter-search-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        gap: 15px;
    }

    .filter-buttons button {
        background: #6a11cb;
        color: white;
        border: none;
        padding: 8px 15px;
        margin-right: 10px;
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
        gap: 15px;
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
    }

    .date-filter input[type="date"]:focus {
        border-color: #6a11cb;
    }

    .search-container {
        position: relative;
    }

    .search-container input {
        padding: 8px 35px 8px 15px;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        font-size: 14px;
        width: 250px;
        outline: none;
        transition: border-color 0.3s ease;
    }

    .search-container input:focus {
        border-color: #6a11cb;
    }

    .search-container::after {
        content: '\1F50D'; /* Magnifying glass Unicode */
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 16px;
        color: #6a11cb;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .main-content {
            margin-left: 0; /* Collapse sidebar on small screens */
        }
        .table-container {
            padding: 10px;
        }
        th, td {
            padding: 10px;
            font-size: 12px;
        }
        .remove-btn {
            padding: 6px 10px;
            font-size: 12px;
        }
        .total-price {
            font-size: 14px;
            text-align: center;
        }
        .filter-search-container {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        .filter-buttons {
            margin-bottom: 10px;
        }
        .date-filter {
            flex-direction: column;
            gap: 10px;
        }
        .search-container input {
            width: 100%;
        }
    }
</style>

<div class="main-content">
    <!-- Filter Buttons, Date Range, and Search Bar -->
    <div class="filter-search-container">
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
                <label for="start-date">Start Date:</label>
                <input type="date" id="start-date" value="2025-02-02">
            </div>
            <div>
                <label for="end-date">End Date:</label>
                <input type="date" id="end-date" value="2025-02-12">
            </div>
        </div>
        <div class="search-container">
            <input type="text" id="search-input" placeholder="Search by Product Name...">
        </div>
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
                    <th>Date of Sale</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="purchase-table">
                <tr data-date="2025-02-05">
                    <td><input type="checkbox" class="select-item"></td>
                    <td><img src="../../views/assets/images/Cake mixer.png" alt="Samsung Galaxy S23"></td>
                    <td>Samsung Galaxy S23</td>
                    <td>5</td>
                    <td>$799.99</td>
                    <td>2025-02-05</td>
                    <td><button class="remove-btn" data-id="1">Remove</button></td>
                </tr>
                <tr data-date="2025-02-08">
                    <td><input type="checkbox" class="select-item"></td>
                    <td><img src="../../views/assets/images/Electric cooking pot.png" alt="Levi's 501 Jeans"></td>
                    <td>Levi's 501 Jeans</td>
                    <td>10</td>
                    <td>$59.99</td>
                    <td>2025-02-08</td>
                    <td><button class="remove-btn" data-id="2">Remove</button></td>
                </tr>
                <tr data-date="2025-04-30">
                    <td><input type="checkbox" class="select-item"></td>
                    <td><img src="../../views/assets/images/Cocktail machine.png" alt="Organic Apples"></td>
                    <td>Organic Apples</td>
                    <td>20</td>
                    <td>$2.49</td>
                    <td>2025-04-30</td>
                    <td><button class="remove-btn" data-id="3">Remove</button></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Total Price Display (Static) -->
    <div class="total-price">
        Total Price: <span>$5,999.47</span>
    </div>
</div>

<script>
    // Select All Checkbox
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.select-item');

    selectAll.addEventListener('change', function () {
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            selectAll.checked = [...checkboxes].every(cb => cb.checked);
        });
    });

    // Remove Button (Static Behavior)
    document.querySelectorAll('.remove-btn').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            if (confirm(`Are you sure you want to remove item with ID: ${id}?`)) {
                this.closest('tr').remove();
                console.log(`Removed item with ID: ${id}`);
            }
        });
    });

    // Filter Logic
    const filterButtons = document.querySelectorAll('.filter-btn');
    const startDateInput = document.getElementById('start-date');
    const endDateInput = document.getElementById('end-date');
    const rows = document.querySelectorAll('#purchase-table tr');
    const today = new Date('2025-04-02'); // Static date for demo (your system's current date)

    function updateDateInputs(start, end) {
        startDateInput.value = start.toISOString().split('T')[0];
        endDateInput.value = end.toISOString().split('T')[0];
        filterRows();
    }

    function filterRows() {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);
        endDate.setHours(23, 59, 59, 999); // Include full end day

        rows.forEach(row => {
            const rowDate = new Date(row.getAttribute('data-date'));
            const inRange = rowDate >= startDate && rowDate <= endDate;
            row.style.display = inRange ? '' : 'none';
        });
    }

    filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            const filter = this.getAttribute('data-filter');
            let start, end;

            switch (filter) {
                case 'all':
                    start = new Date('2000-01-01'); // Arbitrary past
                    end = new Date('2099-12-31');   // Arbitrary future
                    break;
                case 'today':
                    start = new Date(today);
                    end = new Date(today);
                    break;
                case 'this-week':
                    start = new Date(today);
                    start.setDate(today.getDate() - today.getDay());
                    end = new Date(start);
                    end.setDate(start.getDate() + 6);
                    break;
                case 'last-week':
                    start = new Date(today);
                    start.setDate(today.getDate() - today.getDay() - 7);
                    end = new Date(start);
                    end.setDate(start.getDate() + 6);
                    break;
                case 'this-month':
                    start = new Date(today.getFullYear(), today.getMonth(), 1);
                    end = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                    break;
                case 'last-month':
                    start = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                    end = new Date(today.getFullYear(), today.getMonth(), 0);
                    break;
            }

            updateDateInputs(start, end);
        });
    });

    startDateInput.addEventListener('change', filterRows);
    endDateInput.addEventListener('change', filterRows);

    // Search Logic
    const searchInput = document.getElementById('search-input');
    searchInput.addEventListener('input', function () {
        const searchTerm = this.value.toLowerCase();
        rows.forEach(row => {
            const productName = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            const matchesSearch = productName.includes(searchTerm);
            const rowDate = new Date(row.getAttribute('data-date'));
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);
            endDate.setHours(23, 59, 59, 999);
            const inRange = rowDate >= startDate && rowDate <= endDate;
            row.style.display = (matchesSearch && inRange) ? '' : 'none';
        });
    });

    // Initial filter (set to Feb 2 - Feb 12 as default)
    filterRows();
</script>

<?php
require_once './views/layouts/footer.php';
?>