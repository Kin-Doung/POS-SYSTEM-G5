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
        transition: background-color 0.3s ease;
    }

    th {
        background-color: #5cbacc;
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
    }

    tr:hover td {
        background-color: #eef2ff;
        cursor: pointer;
    }

    img {
        max-width: 50px;
        height: auto;
    }

    .remove-btn {
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
    }

    .remove-btn:hover {
        background: linear-gradient(45deg, #cc0000, #ff4d4d);
        transform: translateY(-2px);
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
        font-size: 18px;
    }

    /* Filter and Search Styles */
    .filter-search-container {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-bottom: 20px;
    }

    .filter-date-wrapper {
        display: flex;
        flex-wrap: nowrap;
        align-items: center;
        gap: 15px;
        overflow-x: auto;
        white-space: nowrap;
    }

    .filter-buttons {
        display: flex;
        gap: 10px;
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
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        justify-content: center;
        align-items: center;
    }

    .confirm-modal.show {
        display: flex;
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
        color: white;
    }

    .confirm-modal-buttons #confirm-yes:hover {
        background: #4a9bb0;
        transform: translateY(-2px);
        box-shadow: 0 2px 5px rgba(92, 186, 204, 0.3);
    }

    .confirm-modal-buttons #confirm-no {
        background: linear-gradient(45deg, #ff4d4d, #ff7878);
        color: white;
    }

    .confirm-modal-buttons #confirm-no:hover {
        background: linear-gradient(45deg, #cc0000, #ff4d4d);
        transform: translateY(-2px);
        box-shadow: 0 2px 5px rgba(255, 77, 77, 0.3);
    }

    /* Pagination Styles */
    .pagination {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-top: 20px;
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

    /* Loading State */
    .loading {
        position: relative;
        opacity: 0.7;
        pointer-events: none;
    }

    .loading::after {
        content: 'Loading...';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 16px;
        font-weight: 500;
        color: #1e293b;
    }

    /* Empty Table State */
    tbody tr td[colspan="8"] {
        text-align: center;
        padding: 20px;
        color: #6b7280;
        font-style: italic;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
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
            gap: 10px;
        }

        .date-filter {
            flex-direction: row;
            gap: 10px;
        }

        .search-container {
            width: 100%;
            margin-left: 0;
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

        .page-btn {
            padding: 8px 12px;
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
                    <input type="date" id="start-date" value="<?= date('Y-m-d', strtotime('-1 month')) ?>">
                </div>
                <div>
                    <input type="date" id="end-date" value="<?= date('Y-m-d') ?>">
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
                        <td><img src="<?= $report['image'] ?>" alt="Product Image" width="50" loading="lazy"></td>
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

    <!-- Delete Button (Hidden by Default) -->
    <button class="delete-btn" id="delete-selected">Delete Selected</button>

    <!-- Custom Message Element -->
    <div class="message" id="delete-message">Item deleted</div>

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
    // UI Elements
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

    // Base URL for AJAX requests (adjust if app is in a subdirectory)
    const baseUrl = window.location.origin;
    // CSRF Token (Adjust based on your setup)
    const csrfToken = '<?= isset($_SESSION["csrf_token"]) ? $_SESSION["csrf_token"] : "" ?>';
    let currentPage = <?= $currentPage ?: 1 ?>;

    // Show Message with Dynamic Text
    function showMessage(text) {
        deleteMessage.textContent = text;
        deleteMessage.classList.add('show');
        setTimeout(() => deleteMessage.classList.remove('show'), 3000);
    }

    // Toggle Delete Button Visibility
    function updateDeleteButtonVisibility() {
        const checkboxes = document.querySelectorAll('.select-item');
        const anyChecked = [...checkboxes].some(checkbox => checkbox.checked);
        deleteBtn.style.display = anyChecked ? 'block' : 'none';
    }

    // Fetch Table Data
    function fetchTableData({ filter = 'all', startDate = null, endDate = null, search = '', page = currentPage } = {}) {
        tableBody.classList.add('loading');
        const payload = { filter, search, page };
        if (filter === 'all' && startDate && endDate) {
            payload.start_date = startDate;
            payload.end_date = endDate;
        }
        console.log('Fetching with payload:', payload);

        return fetch(`${baseUrl}/history/fetchFilteredHistories`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-Token': csrfToken
            },
            body: new URLSearchParams(payload)
        })
        .then(response => {
            console.log('Fetch Status:', response.status);
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            tableBody.classList.remove('loading');
            console.log('Received data:', data);
            if (data.success) {
                tableBody.innerHTML = data.reports.length > 0 ? data.reports.map(report => `
                    <tr data-date="${report.created_at}">
                        <td><input type="checkbox" class="select-item" data-id="${report.id}"></td>
                        <td><img src="${report.image}" alt="Product Image" width="50" loading="lazy"></td>
                        <td>${report.product_name}</td>
                        <td>${report.quantity}</td>
                        <td>${report.price}$</td>
                        <td>${report.total_price}$</td>
                        <td>${new Date(report.created_at).toISOString().split('T')[0]}</td>
                        <td><button class="remove-btn" data-id="${report.id}">Remove</button></td>
                    </tr>
                `).join('') : '<tr><td colspan="8">No records found</td></tr>';
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
            tableBody.classList.remove('loading');
            console.error('Fetch Error:', error);
            alert('Error fetching data: ' + error.message);
        });
    }

    // Update Pagination
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

    // Attach Page Listeners
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
            fetchTableData({
                filter: document.querySelector('.filter-btn.active')?.getAttribute('data-filter') || 'all',
                startDate: startDateInput.value,
                endDate: endDateInput.value,
                search: searchInput.value,
                page
            });
        }
    }

    // Handle Single Delete
    function attachRemoveListeners() {
        document.querySelectorAll('.remove-btn').forEach(button => {
            button.removeEventListener('click', handleRemoveClick);
            button.addEventListener('click', handleRemoveClick);
        });
    }

    function handleRemoveClick() {
        const id = this.getAttribute('data-id');
        const row = this.closest('tr');
        modal.querySelector('p').textContent = 'Are you sure you want to delete this item?';
        modal.classList.add('show');
        confirmYes.onclick = () => {
            tableBody.classList.add('loading');
            console.log('Sending DELETE request for ID:', id);
            fetch(`${baseUrl}/history/destroy`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': csrfToken
                },
                body: JSON.stringify({ ids: [id] })
            })
            .then(response => {
                console.log('Delete Status:', response.status);
                if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                return response.json();
            })
            .then(data => {
                tableBody.classList.remove('loading');
                console.log('Delete Data:', data);
                if (data.success) {
                    row.remove();
                    showMessage('Product deleted successfully');
                    modal.classList.remove('show');
                    fetchTableData({ page: currentPage });
                } else {
                    alert('Failed to delete: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                tableBody.classList.remove('loading');
                console.error('Delete Error:', error);
                alert('Error deleting item: ' + error.message);
            });
        };
        confirmNo.onclick = () => modal.classList.remove('show');
    }

    // Handle Bulk Delete
    deleteBtn.addEventListener('click', () => {
        const checkboxes = document.querySelectorAll('.select-item:checked');
        const ids = [...checkboxes].map(cb => cb.getAttribute('data-id'));
        if (ids.length === 0) {
            alert('Please select at least one item to delete.');
            return;
        }
        modal.querySelector('p').textContent = `Are you sure you want to delete ${ids.length} item${ids.length > 1 ? 's' : ''}?`;
        modal.classList.add('show');
        confirmYes.onclick = () => {
            tableBody.classList.add('loading');
            console.log('Sending bulk DELETE request for IDs:', ids);
            fetch(`${baseUrl}/history/destroy`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': csrfToken
                },
                body: JSON.stringify({ ids })
            })
            .then(response => {
                console.log('Bulk Delete Status:', response.status);
                if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                return response.json();
            })
            .then(data => {
                tableBody.classList.remove('loading');
                console.log('Bulk Delete Data:', data);
                if (data.success) {
                    checkboxes.forEach(cb => cb.closest('tr').remove());
                    showMessage(`${ids.length} item${ids.length > 1 ? 's' : ''} deleted successfully`);
                    modal.classList.remove('show');
                    fetchTableData({ page: currentPage });
                } else {
                    alert('Failed to delete: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                tableBody.classList.remove('loading');
                console.error('Bulk Delete Error:', error);
                alert('Error deleting items: ' + error.message);
            });
        };
        confirmNo.onclick = () => modal.classList.remove('show');
    });

    // Checkbox Listeners
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

    // Filter Button Clicks
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            fetchTableData({
                filter: this.getAttribute('data-filter'),
                search: searchInput.value,
                page: 1
            });
        });
    });

    // Date Input Changes
    startDateInput.addEventListener('change', () => {
        fetchTableData({
            filter: 'all',
            startDate: startDateInput.value,
            endDate: endDateInput.value,
            search: searchInput.value,
            page: 1
        });
    });

    endDateInput.addEventListener('change', () => {
        fetchTableData({
            filter: 'all',
            startDate: startDateInput.value,
            endDate: endDateInput.value,
            search: searchInput.value,
            page: 1
        });
    });

    // Search Input (Debounced)
    let searchTimeout;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            fetchTableData({
                filter: 'all',
                startDate: startDateInput.value,
                endDate: endDateInput.value,
                search: searchInput.value,
                page: 1
            });
        }, 300);
    });

    // Initial Load
    fetchTableData();
    attachPageListeners();
</script>

<?php
require_once './views/layouts/footer.php';
?>