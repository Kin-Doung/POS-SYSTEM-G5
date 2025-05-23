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
        margin-top: -30px;
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
        background: none;
        color: red;
        border: none;
        padding: 8px 15px;
        border-radius: 25px;
        cursor: pointer;
        font-size: 20px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .remove-btn:hover {
        background: none;
        transform: translateY(-2px);
    }

    .edit-btn {
        background: linear-gradient(45deg, #4a90e2, #63b8ff);
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 25px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 500;
        box-shadow: 0 2px 5px rgba(74, 144, 226, 0.3);
        transition: all 0.3s ease;
        margin-right: 5px;
    }

    .edit-btn:hover {
        background: linear-gradient(45deg, #357abd, #4a90e2);
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(74, 144, 226, 0.4);
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
        margin-top: 25px;
    }

    .filter-date-wrapper {
        display: flex;
        flex-wrap: nowrap;
        align-items: center;
        gap: 135px;
        overflow-x: auto;
        white-space: nowrap;
        margin-bottom: -30px;
    }

    .filter-dropdown-container {
        position: relative;
    }

    .filter-dropdown {
        color: #000;
        border: 1px solid #ccc;
        padding: 8px 30px 8px 15px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        outline: none;
        width: 150px;
        appearance: none;

    }

    .filter-dropdown-container::after {
        content: '\25BC';
        position: absolute;
        right: 15px;
        top: 10px;
        color: #000;
        font-size: 12px;
        pointer-events: none;
        transition: transform 0.3s ease;
    }

    .filter-dropdown-container.active::after {
        transform: rotate(180deg);
    }

    .filter-dropdown:focus {
        background: #fff;
        color: black;
        border: 1px solid #add8e6;
    }

    .date-filter {
        display: flex;
        gap: 5px;
        align-items: center;
        justify-content: center;
    }

    .date-filter label {
        font-size: 14px;
        font-weight: 500;
        color: #1e293b;
        margin-right: 5px;
        margin-top: 5px;
    }

    .date-filter input[type="text"] {
        background: #f8f9fa;
        color: #1e293b;
        border: 1px solid #ccc;
        border-radius: 10px;
        padding: 8px 15px;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.3s ease;
        outline: none;
        width: 250px;
        cursor: pointer;
    }

    .date-filter input[type="text"]:focus {
        border: 1px solid #add8e6;
    }

    .search-containerr {
        width: 100%;
        max-width: 250px;
    }

    #search-input {
        width: 100%;
        border: 1px solid #ccc;
        border-radius: 10px;
        font-size: 14px;
        height: 38px;
        margin-left: 55px;
    }

    #search-input:focus {
        outline: none;
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

    .confirm-modal-content form div {
        margin-bottom: 15px;
        text-align: left;
    }

    .confirm-modal-content label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #1e293b;
        margin-bottom: 5px;
    }

    .confirm-modal-content input {
        width: 100%;
        padding: 8px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
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

    .confirm-modal-buttons #confirm-yes,
    .confirm-modal-buttons #save-edit {
        background: #5cbacc;
        color: white;
    }

    .confirm-modal-buttons #confirm-yes:hover,
    .confirm-modal-buttons #save-edit:hover {
        background: #4a9bb0;
        transform: translateY(-2px);
        box-shadow: 0 2px 5px rgba(92, 186, 204, 0.3);
    }

    .confirm-modal-buttons #confirm-no,
    .confirm-modal-buttons #cancel-edit {
        background: linear-gradient(45deg, #ff4d4d, #ff7878);
        color: white;
    }

    .confirm-modal-buttons #confirm-no:hover,
    .confirm-modal-buttons #cancel-edit:hover {
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
        content: '';
        border: 4px solid #f3f3f3;
        border-top: 4px solid #5cbacc;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        animation: spin 1s linear infinite;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    @keyframes spin {
        0% {
            transform: translate(-50%, -50%) rotate(0deg);
        }

        100% {
            transform: translate(-50%, -50%) rotate(360deg);
        }
    }

    /* Empty Table State */
    tbody tr td[colspan="8"] {
        text-align: center;
        padding: 40px;
        color: #6b7280;
        font-size: 16px;
        font-style: italic;
        background-color: #f8f9fa;
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
        .edit-btn,
        .delete-btn {
            padding: 6px 10px;
            font-size: 12px;
        }

        .total-price {
            font-size: 14px;
            text-align: center;
        }

        .filter-date-wrapper {
            flex-wrap: wrap;
            gap: 10px;
        }

        .filter-dropdown {
            width: 120px;
        }

        .date-filter input[type="text"] {
            width: 200px;
            border-radius: none;
        }

        .search-containerr {
            width: 100%;
        }

        .search-containerr input {
            height: 20px;
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

    .flatpickr-monthDropdown-months {
        font-size: 15px !important;
        padding: 5px !important;
        font-weight: 500 !important;
        text-transform: capitalize !important;
    }
</style>
<?php
require_once './views/layouts/header.php';
require_once './views/layouts/side.php';
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div style="margin-left: 250px;">
    <?php require_once './views/layouts/nav.php' ?>
</div>
<div class="main-content">
    <!-- Filter Dropdown, Date Range, and Search Bar -->
    <div class="filter-search-container">
        <div class="filter-date-wrapper">
            <div class="filter-dropdown-container">
                <select class="filter-dropdown" id="filter-dropdown">
                    <option value="all" selected data-translate-key="Filter_All">Filter all</option>
                    <option value="today" data-translate-key="Today">Today</option>
                    <option value="this-week" data-translate-key="This Week">This Week</option>
                    <option value="last-week" data-translate-key="Last_Week">Last Week</option>
                    <option value="this-month" data-translate-key="This Month">This Month</option>
                    <option value="last-month" data-translate-key="Last_Month">Last Month</option>
                </select>
            </div>
            <div class="date-filter">
                <label for="date-range" data-translate-key="Date_Range">Date Range:</label>
                <input type="text" id="date-range" data-translate-key="Select_Date_Range" placeholder="Select date range...">
            </div>
            <div class="search-containerr">
                <input type="text" id="search-input" data-translate-key="Search_Placeholder" placeholder="search...">
            </div>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all" title="Select All" aria-label="Select All" data-translate-key="Select_All"></th>
                    <th data-translate-key="Image">Image</th>
                    <th data-translate-key="Product Name">Product Name</th>
                    <th data-translate-key="Quantity">Quantity</th>
                    <th data-translate-key="Price">Price</th>
                    <th data-translate-key="Total Price">Total Price</th>
                    <th data-translate-key="Date_of_Sale">Date of Sale</th>
                    <th data-translate-key="Action">Action</th>
                </tr>
            </thead>
            <tbody id="purchase-table">
                <?php foreach ($reports as $report) : ?>
                    <tr data-date="<?= $report['created_at'] ? date('Y-m-d', strtotime($report['created_at'])) : 'N/A' ?>">
                        <td><input type="checkbox" class="select-item" R data-id="<?= $report['id'] ?>" title="Select Item" aria-label="Select Item"></td>
                        <td><img src="<?= $report['image'] ?>" alt="Product Image" width="50" loading="lazy"></td>
                        <td><?= htmlspecialchars($report['product_name']) ?></td>
                        <td><?= $report['quantity'] ?></td>
                        <td><?= number_format($report['price'], 2) ?>$</td>
                        <td><?= number_format($report['total_price'], 2) ?>$</td>
                        <td><?= $report['created_at'] ? date('Y-m-d', strtotime($report['created_at'])) : 'N/A' ?></td>
                        <td>
                            <button style="display: none;" class="edit-btn" data-id="<?= $report['id'] ?>" data-translate-key="Edit" title="Edit" aria-label="Edit">Edit</button>
                            <button class="remove-btn" data-id="<?= $report['id'] ?>" data-translate-key="Delete" title="Delete" aria-label="Delete"><i class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <div class="pagination">
            <?php if ($currentPage > 1): ?>
                <button class="page-btn" data-page="<?= $currentPage - 1 ?>" data-translate-key="Previous_Page" title="Previous Page" aria-label="Previous Page"><i class="fa-solid fa-less-than"></i></button>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <button class="page-btn <?= $i == $currentPage ? 'active' : '' ?>" data-page="<?= $i ?>" title="Page <?= $i ?>" aria-label="Page <?= $i ?>"><?= $i ?></button>
            <?php endfor; ?>
            <?php if ($currentPage < $totalPages): ?>
                <button class="page-btn" data-page="<?= $currentPage + 1 ?>" data-translate-key="Next_Page" title="Next Page" aria-label="Next Page"><i class="fa-solid fa-greater-than"></i></button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Delete Button (Hidden by Default) -->
    <button class="delete-btn" id="delete-selected" data-translate-key="Delete_Selected" title="Delete Selected" aria-label="Delete Selected">Delete Selected</button>

    <!-- Custom Message Element -->
    <div class="message" id="delete-message" data-translate-key="Item_Deleted_Successfully">Item deleted</div>

    <!-- Custom Confirmation Modal for Deletion -->
    <div class="confirm-modal" id="confirm-modal">
        <div class="confirm-modal-content">
            <p data-translate-key="Confirm_Delete_Items">Are you sure you want to delete the selected items?</p>
            <div class="confirm-modal-buttons">
                <button id="confirm-yes" data-translate-key="Yes" title="Yes" aria-label="Yes">Yes</button>
                <button id="confirm-no" data-translate-key="No" title="No" aria-label="No">No</button>
            </div>
        </div>
    </div>

    <!-- Custom Edit Modal -->
    <div class="confirm-modal" id="edit-modal">
        <div class="confirm-modal-content">
            <p data-translate-key="Edit Product">Edit Product</p>
            <form id="edit-form">
                <input type="hidden" name="id" id="edit-id">
                <div>
                    <label data-translate-key="Product Name">Product Name:</label>
                    <input type="text" name="product_name" id="edit-product-name" required>
                </div>
                <div>
                    <label data-translate-key="Quantity">Quantity:</label>
                    <input type="number" name="quantity" id="edit-quantity" min="1" required>
                </div>
                <div>
                    <label data-translate-key="Price">Price:</label>
                    <input type="number" name="price" id="edit-price" step="0.01" min="0" required>
                </div>
                <div>
                    <label data-translate-key="Date_of_Sale">Date:</label>
                    <input type="date" name="created_at" id="edit-created-at" required>
                </div>
                <div>
                    <label data-translate-key="Image">Image:</label>
                    <input type="file" name="image" id="edit-image" accept="image/*">
                </div>
                <div class="confirm-modal-buttons">
                    <button type="submit" id="save-edit" data-translate-key="Save" title="Save" aria-label="Save">Save</button>
                    <button type="button" id="cancel-edit" data-translate-key="Cancel" title="Cancel" aria-label="Cancel">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Total Price Display -->
    <div class="total-price">
        <span data-translate-key="Total Price">Total Price:</span> <span>$</span>
    </div>
</div>

<!-- Include Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    // Function to get translated text
    function getTranslation(key) {
        const lang = localStorage.getItem('selectedLanguage') || 'en';
        return translations[lang][key] || key;
    }

    // Initialize Flatpickr for date range
    flatpickr("#date-range", {
        mode: "range",
        dateFormat: "Y-m-d",
        defaultDate: ["<?= date('Y-m-d', strtotime('-1 month')) ?>", "<?= date('Y-m-d') ?>"],
        minDate: "2025-01-01",
        locale: {
            rangeSeparator: ' to ',
            firstDayOfWeek: 1
        },
        onReady: function(selectedDates, dateStr, instance) {
            instance.input.placeholder = getTranslation('Select_Date_Range');
        },
        onChange: function(selectedDates) {
            if (selectedDates.length === 2) {
                const startDate = selectedDates[0].toISOString().split('T')[0];
                const endDate = selectedDates[1].toISOString().split('T')[0];
                fetchTableData({
                    filter: document.getElementById('filter-dropdown').value,
                    startDate,
                    endDate,
                    search: searchInput.value,
                    page: 1
                });
            }
        }
    });

    // UI Elements
    const selectAll = document.getElementById('select-all');
    const deleteBtn = document.getElementById('delete-selected');
    const deleteMessage = document.getElementById('delete-message');
    const filterDropdown = document.getElementById('filter-dropdown');
    const filterDropdownContainer = document.querySelector('.filter-dropdown-container');
    const searchInput = document.getElementById('search-input');
    const tableBody = document.getElementById('purchase-table');
    const totalPriceSpan = document.querySelector('.total-price span:last-child');
    const modal = document.getElementById('confirm-modal');
    const confirmYes = document.getElementById('confirm-yes');
    const confirmNo = document.getElementById('confirm-no');
    const editModal = document.getElementById('edit-modal');
    const editForm = document.getElementById('edit-form');
    const cancelEdit = document.getElementById('cancel-edit');
    const paginationContainer = document.querySelector('.pagination');

    // Base URL for AJAX requests
    const baseUrl = '<?= defined('BASE_URL') ? BASE_URL : '' ?>';
    const csrfToken = '<?= isset($_SESSION["csrf_token"]) ? $_SESSION["csrf_token"] : "" ?>';
    let currentPage = <?= $currentPage ?: 1 ?>;

    // Apply translations on page load
    const savedLang = localStorage.getItem('selectedLanguage') || 'en';
    applyTranslations(savedLang);

    // Update tooltips and aria-labels dynamically
    document.querySelectorAll('[title][data-translate-key], [aria-label][data-translate-key]').forEach(element => {
        const key = element.getAttribute('data-translate-key');
        element.setAttribute('title', getTranslation(key));
        element.setAttribute('aria-label', getTranslation(key));
    });

    // Add interactivity to dropdown
    filterDropdown.addEventListener('focus', () => {
        filterDropdownContainer.classList.add('active');
    });
    filterDropdown.addEventListener('blur', () => {
        filterDropdownContainer.classList.remove('active');
    });

    // Show Message with Dynamic Text
    function showMessage(messageKey, dynamicText = '') {
        deleteMessage.textContent = dynamicText ? `${dynamicText} ${getTranslation(messageKey)}` : getTranslation(messageKey);
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
    function fetchTableData({
        filter = 'all',
        startDate = null,
        endDate = null,
        search = '',
        page = currentPage
    } = {}) {
        tableBody.classList.add('loading');
        const payload = {
            filter,
            search,
            page
        };
        if (filter === 'all' && startDate && endDate) {
            payload.start_date = startDate;
            payload.end_date = endDate;
        }

        return fetch(`${baseUrl}/history/fetchFilteredHistories`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-Token': csrfToken
                },
                body: new URLSearchParams(payload)
            })
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                return response.json();
            })
            .then(data => {
                tableBody.classList.remove('loading');
                if (data.success) {
                    tableBody.innerHTML = data.reports.length > 0 ? data.reports.map(report => `
                    <tr data-date="${report.created_at}">
                        <td><input type="checkbox" class="select-item" data-id="${report.id}" title="${getTranslation('Select')}"></td>
                        <td><img src="${report.image}" alt="Product Image" width="50" loading="lazy"></td>
                        <td>${report.product_name}</td>
                        <td>${report.quantity}</td>
                        <td>${parseFloat(report.price).toFixed(2)}$</td>
                        <td>${parseFloat(report.total_price).toFixed(2)}$</td>
                        <td>${new Date(report.created_at).toISOString().split('T')[0]}</td>
                        <td>
                            <button style="display: none;" class="edit-btn" data-id="${report.id}" data-translate-key="Edit" title="${getTranslation('Edit')}" aria-label="${getTranslation('Edit')}">Edit</button>
                            <button class="remove-btn" data-id="${report.id}" data-translate-key="Delete" title="${getTranslation('Delete')}" aria-label="${getTranslation('Delete')}"><i class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                `).join('') : `<tr><td colspan="8" data-translate-key="No_Records_Found">${getTranslation('No_Records_Found')}</td></tr>`;
                    totalPriceSpan.textContent = `$${parseFloat(data.total_price).toFixed(2)}`;
                    currentPage = data.currentPage;
                    updatePagination(data.currentPage, data.totalPages);
                    attachRemoveListeners();
                    attachEditListeners();
                    attachCheckboxListeners();
                } else {
                    showMessage('Failed_To_Fetch_Data', data.error || '');
                }
            })
            .catch(error => {
                tableBody.classList.remove('loading');
                showMessage('Error_Fetching_Data', error.message);
            });
    }

    // Update Pagination
    function updatePagination(currentPage, totalPages) {
        let paginationHTML = '';
        if (currentPage > 1) {
            paginationHTML += `<button class="page-btn" data-page="${currentPage - 1}" data-translate-key="Previous_Page" title="${getTranslation('Previous_Page')}" aria-label="${getTranslation('Previous_Page')}"><i class="fa-solid fa-less-than"></i></button>`;
        }
        for (let i = 1; i <= totalPages; i++) {
            paginationHTML += `<button class="page-btn ${i === currentPage ? 'active' : ''}" data-page="${i}" title="${getTranslation('Page')} ${i}" aria-label="${getTranslation('Page')} ${i}">${i}</button>`;
        }
        if (currentPage < totalPages) {
            paginationHTML += `<button class="page-btn" data-page="${currentPage + 1}" data-translate-key="Next_Page" title="${getTranslation('Next_Page')}" aria-label="${getTranslation('Next_Page')}"><i class="fa-solid fa-greater-than"></i></button>`;
        }
        paginationContainer.innerHTML = paginationHTML;
        // Update pagination button attributes for translation
        document.querySelectorAll('.page-btn[data-translate-key]').forEach(button => {
            const key = button.getAttribute('data-translate-key');
            button.setAttribute('title', getTranslation(key));
            button.setAttribute('aria-label', getTranslation(key));
        });
        attachPageListeners();
    }

    // Attach Page Listeners
    function attachPageListeners() {
        const pageButtons = document.querySelectorAll('.page-btn');
        pageButtons.forEach(button => {
            button.removeEventListener('click', handlePageClick);
            button.addEventListener('click', handlePageClick);
        });
    }

    function handlePageClick() {
        const page = parseInt(this.getAttribute('data-page'));
        if (!isNaN(page)) {
            fetchTableData({
                filter: filterDropdown.value,
                startDate: document.getElementById('date-range').value.split(' to ')[0],
                endDate: document.getElementById('date-range').value.split(' to ')[1],
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
        modal.querySelector('p').textContent = getTranslation('Confirm_Delete_Single_Item');
        modal.classList.add('show');
        confirmYes.onclick = () => {
            tableBody.classList.add('loading');
            fetch(`${baseUrl}/history/destroy`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': csrfToken
                    },
                    body: JSON.stringify({
                        ids: [id]
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    tableBody.classList.remove('loading');
                    if (data.success) {
                        row.remove();
                        showMessage('Product_Deleted_Successfully');
                        modal.classList.remove('show');
                        fetchTableData({
                            page: currentPage
                        });
                    } else {
                        showMessage('Failed_To_Delete', data.error || '');
                    }
                })
                .catch(error => {
                    tableBody.classList.remove('loading');
                    showMessage('Error_Deleting_Item', error.message);
                });
        };
        confirmNo.onclick = () => modal.classList.remove('show');
    }

    // Handle Edit
    function attachEditListeners() {
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.removeEventListener('click', handleEditClick);
            button.addEventListener('click', handleEditClick);
        });
    }

    function handleEditClick() {
        const id = this.getAttribute('data-id');
        fetch(`${baseUrl}/history/edit/${id}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-Token': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('edit-id').value = data.report.id;
                    document.getElementById('edit-product-name').value = data.report.product_name;
                    document.getElementById('edit-quantity').value = data.report.quantity;
                    document.getElementById('edit-price').value = parseFloat(data.report.price).toFixed(2);
                    document.getElementById('edit-created-at').value = data.report.created_at.split(' ')[0];
                    document.getElementById('edit-image').value = '';
                    editModal.classList.add('show');
                } else {
                    showMessage('Failed_To_Fetch_Record', data.error || '');
                }
            })
            .catch(error => showMessage('Error_Fetching_Data', error.message));
    }

    editForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('edit-id').value;
        const formData = new FormData(this);
        fetch(`${baseUrl}/history/update/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-Token': csrfToken
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('Product_Updated_Successfully');
                    editModal.classList.remove('show');
                    fetchTableData({
                        page: currentPage
                    });
                } else {
                    showMessage('Failed_To_Update', data.error || '');
                }
            })
            .catch(error => showMessage('Failed_To_Update', error.message));
    });

    cancelEdit.addEventListener('click', () => {
        editModal.classList.remove('show');
    });

    // Handle Bulk Delete
    deleteBtn.addEventListener('click', () => {
        const checkboxes = document.querySelectorAll('.select-item:checked');
        const ids = [...checkboxes].map(cb => cb.getAttribute('data-id'));
        if (ids.length === 0) {
            showMessage('Please_Select_At_Least_One_Item');
            return;
        }
        modal.querySelector('p').textContent = getTranslation('Confirm_Delete_Items').replace('{count}', ids.length);
        modal.classList.add('show');
        confirmYes.onclick = () => {
            tableBody.classList.add('loading');
            fetch(`${baseUrl}/history/destroy`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': csrfToken
                    },
                    body: JSON.stringify({
                        ids
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    tableBody.classList.remove('loading');
                    if (data.success) {
                        checkboxes.forEach(cb => cb.closest('tr').remove());
                        showMessage('Items_Deleted_Successfully', `${ids.length}`);
                        modal.classList.remove('show');
                        fetchTableData({
                            page: currentPage
                        });
                    } else {
                        showMessage('Failed_To_Delete', data.error || '');
                    }
                })
                .catch(error => {
                    tableBody.classList.remove('loading');
                    showMessage('Error_Deleting_Item', error.message);
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

    // Filter Dropdown Change
    filterDropdown.addEventListener('change', function() {
        fetchTableData({
            filter: this.value,
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
                filter: filterDropdown.value,
                startDate: document.getElementById('date-range').value.split(' to ')[0],
                endDate: document.getElementById('date-range').value.split(' to ')[1],
                search: searchInput.value,
                page: 1
            });
        }, 300);
    });

    // Initial Load
    fetchTableData().then(() => {
        attachEditListeners();
        attachPageListeners();
    });
</script>

<?php require_once 'views/layouts/footer.php'; ?>