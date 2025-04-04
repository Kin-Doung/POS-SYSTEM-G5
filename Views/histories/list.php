<?php
require_once './views/layouts/header.php';
require_once './views/layouts/side.php';
?>

<style>
    /* Main content wrapper to account for sidebar */
    .main-content {
        margin-left: 250px; /* Adjust this based on your sidebar width */
        padding: 20px;
        background-color: #f8f9fa;
        height: 100vh;
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

    th, td {
        padding: 15px;
        text-align: left;
        border: none; /* Remove default borders */
        transition: background-color 0.3s ease;
    }

    th {
        background-color:#5cbacc; /* Simple solid color instead of gradient */
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
        display: none; /* Hidden by default */
        margin-top: 10px;
    }

    .delete-btn:hover {
        background: linear-gradient(45deg, #cc0000, #ff4d4d);
        transform: translateY(-2px);
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
        flex-direction: column; /* Stack filter-date-wrapper and search vertically */
        gap: 15px;
        margin-bottom: 20px;
    }

    .filter-date-wrapper {
        display: flex;
        flex-wrap: nowrap; /* Prevent wrapping to ensure one line */
        align-items: center;
        gap: 15px; /* Space between filter buttons and date filters */
        overflow-x: auto; /* Allow horizontal scrolling if content overflows */
        white-space: nowrap; /* Prevent text wrapping */
    }

    .filter-buttons {
        display: flex;
        gap: 10px; /* Space between buttons */
    }

    .filter-buttons button {
        background:#5cbacc;
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
        gap: 25px;
        align-items: center; /* Align date inputs vertically centered */
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
        width: 420px; /* Fixed width for consistency */
        background: none;
        margin-left: 660px;
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
        background-color: #16a34a; /* Green background for success */
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
        display: none; /* Hidden by default */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent overlay */
        z-index: 1000;
        justify-content: center;
        align-items: center;
    }

    .confirm-modal.show {
        display: flex; /* Show when active */
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
        background: #5cbacc; /* Match filter button color */
        color: white;
    }

    .confirm-modal-buttons #confirm-yes:hover {
        background: #4a9bb0; /* Slightly darker shade on hover */
        transform: translateY(-2px);
        box-shadow: 0 2px 5px rgba(92, 186, 204, 0.3);
    }

    .confirm-modal-buttons #confirm-no {
        background: linear-gradient(45deg, #ff4d4d, #ff7878); /* Match delete button gradient */
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
            margin-left: 0; /* Collapse sidebar on small screens */
        }
        .table-container {
            padding: 10px;
        }
        th, td {
            padding: 10px;
            font-size: 12px;
        }
        .remove-btn, .delete-btn {
            padding: 6px 10px;
            font-size: 12px;
        }
        .total-price {
            font-size: 14px;
            text-align: center;
        }
        .filter-date-wrapper {
            flex-wrap: nowrap; /* Keep nowrap for horizontal scrolling */
            gap: 10px;
        }
        .date-filter {
            flex-direction: row; /* Keep date inputs in a row */
            gap: 10px;
        }
        .search-container {
            width: 100%; /* Full width on small screens */
            margin-left: 0; /* Reset margin for small screens */
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

    <!-- Total Price Display (Static) -->
    <div class="total-price">
        Total Price: <span>$5,999.47</span>
    </div>
</div>

<script>
    // Select All Checkbox
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.select-item');
    const deleteBtn = document.getElementById('delete-selected');
    const deleteMessage = document.getElementById('delete-message');

    // Function to Show Custom Message
    function showMessage() {
        deleteMessage.classList.add('show');
        setTimeout(() => {
            deleteMessage.classList.remove('show');
        }, 3000); // Hide after 3 seconds
    }

    // Show/Hide Delete Button Based on Checkbox Selection
    function updateDeleteButtonVisibility() {
        const anyChecked = [...checkboxes].some(checkbox => checkbox.checked);
        deleteBtn.style.display = anyChecked ? 'block' : 'none';
    }

    selectAll.addEventListener('change', function () {
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateDeleteButtonVisibility();
    });

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            selectAll.checked = [...checkboxes].every(cb => cb.checked);
            updateDeleteButtonVisibility();
        });
    });

    // Delete Selected Rows with Custom Modal
    deleteBtn.addEventListener('click', function () {
        const modal = document.getElementById('confirm-modal');
        const confirmYes = document.getElementById('confirm-yes');
        const confirmNo = document.getElementById('confirm-no');

        // Show the modal
        modal.classList.add('show');

        // Handle "Yes" button click
        confirmYes.addEventListener('click', function handleYes() {
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const row = checkbox.closest('tr');
                    row.remove();
                }
            });
            showMessage(); // Show custom message
            updateDeleteButtonVisibility(); // Hide button if no checkboxes are selected
            selectAll.checked = false; // Uncheck "Select All"
            modal.classList.remove('show'); // Hide the modal
            confirmYes.removeEventListener('click', handleYes); // Clean up event listener
        });

        // Handle "No" button click
        confirmNo.addEventListener('click', function handleNo() {
            modal.classList.remove('show'); // Hide the modal
            confirmNo.removeEventListener('click', handleNo); // Clean up event listener
        });
    });

    // Remove Button (Static Behavior)
    document.querySelectorAll('.remove-btn').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            if (confirm(`Are you sure you want to remove item with ID: ${id}?`)) {
                this.closest('tr').remove();
                console.log(`Removed item with ID: ${id}`);
                showMessage(); // Show custom message
                updateDeleteButtonVisibility(); // Update button visibility after removal
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
            const productName = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            const searchTerm = searchInput.value.toLowerCase();
            const matchesSearch = productName.includes(searchTerm);
            row.style.display = (inRange && matchesSearch) ? '' : 'none';
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
                    start.setDate(today.getDate() - today.getDate());
                    end = new Date(start);
                    end.setDate(start.getDate() + 6);
                    break;
                case 'last-week':
                    start = new Date(today);
                    start.setDate(today.getDate() - today.getDate() - 7);
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
        filterRows();
    });

    // Initial filter (set to "All" by default)
    updateDateInputs(new Date('2000-01-01'), new Date('2099-12-31'));
</script>

<?php
require_once './views/layouts/footer.php';
?>