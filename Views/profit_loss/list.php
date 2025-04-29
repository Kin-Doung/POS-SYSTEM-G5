<?php
// Define BASE_URL if not already defined to prevent undefined constant issue
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/your-project'); // Adjust to your project's base URL
}
require_once './views/layouts/header.php';
require_once './views/layouts/side.php';
?>

<!-- CSRF Token -->
<meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?? '' ?>">

<div style="margin-left:250px">
    <?php require_once './views/layouts/nav.php' ?>
</div>

<style>
    .total-values {
        display: flex;
        justify-content: flex-end;
        gap: 20px;
        margin-top: 20px;
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

    #profit-total span {
        color: #16a34a;
    }

    #loss-total span {
        color: #dc2626;
    }

    .profit {
        color: green;
        font-weight: bold;
    }

    .loss {
        color: red;
        font-weight: bold;
    }

    .pagination-controls {
        margin-top: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 15px;
        font-family: Arial, sans-serif;
    }

    .pagination-controls a,
    .pagination-controls button {
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .pagination-controls a {
        background-color: #007bff;
        color: white;
        text-decoration: none;
    }

    .pagination-controls a:hover {
        background-color: #0056b3;
    }

    .pagination-controls button {
        background-color: #ccc;
        color: #fff;
        cursor: not-allowed;
    }

    .pagination-controls span {
        font-weight: bold;
    }

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

    .remove-btn,
    .delete-single {
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

    .remove-btn:hover,
    .delete-single:hover {
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

    .delete-btn.show {
        display: inline-block;
    }

    input[type="checkbox"] {
        cursor: pointer;
        accent-color: #2575fc;
        width: 16px;
        height: 16px;
        border-radius: 4px;
    }

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

    .search-container {
        width: 100%;
        max-width: 350px;
    }

    #search-input {
        width: 100%;
        border: 1px solid #ccc;
        border-radius: 10px;
        font-size: 14px;
        height: 38px;
    }

    #search-input:focus {
        outline: none;
    }

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

    tbody tr td[colspan="7"] {
        text-align: center;
        padding: 40px;
        color: #6b7280;
        font-size: 16px;
        font-style: italic;
        background-color: #f8f9fa;
    }

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
        }

        .search-container {
            width: 100%;
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

<!-- Include Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="main-content">
    <div class="filter-search-container">
        <div class="filter-date-wrapper">
            <div class="filter-dropdown-container">
                <select class="filter-dropdown" id="filter-dropdown">
                    <option value="all" data-translate-key="Filter_All">Filter all</option>
                    <option value="today" data-translate-key="Today">Today</option>
                    <option value="this-week" data-translate-key="This_Week">This Week</option>
                    <option value="last-week" data-translate-key="Last_Week">Last Week</option>
                    <option value="this-month" data-translate-key="This_Month">This Month</option>
                    <option value="last-month" data-translate-key="Last_Month">Last Month</option>
                </select>
            </div>
            <div class="date-filter">
                <label for="date-range" data-translate-key="Date_Range">Date Range:</label>
                <input type="text" id="date-range" placeholder="Select date range..." data-translate-key="Select_Date_Range">
            </div>
            <div class="search-container">
                <input type="text" id="search-input" placeholder="search..." data-translate-key="Search_Placeholder">
            </div>
        </div>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all" title="Select All" data-translate-key="Select_All" aria-label="Select all items"></th>
                    <th data-translate-key="Image">Image</th>
                    <th data-translate-key="Product_Name">Product Name</th>
                    <th data-translate-key="Profit_Loss">Profit Loss</th>
                    <th data-translate-key="Result_Type">Result Type</th>
                    <th data-translate-key="Date_of_Sale">Date of Sale</th>
                    <th data-translate-key="Action">Action</th>
                </tr>
            </thead>
            <tbody id="purchase-table">
                <?php
                if (!empty($Profit_Loss) && is_array($Profit_Loss)) {
                    error_log("list.php: Profit_Loss array: " . json_encode($Profit_Loss));
                    foreach ($Profit_Loss as $profit_loss) {
                        if (!isset($profit_loss['ID']) || !is_numeric($profit_loss['ID'])) {
                            error_log("list.php: Invalid or missing ID in record: " . json_encode($profit_loss));
                            continue;
                        }
                ?>
                        <tr data-date="<?= isset($profit_loss['Sale_Date']) ? htmlspecialchars($profit_loss['Sale_Date']) : '' ?>">
                            <td><input type="checkbox" class="select-item" data-id="<?= isset($profit_loss['ID']) ? htmlspecialchars($profit_loss['ID']) : '' ?>" aria-label="Select item"></td>
                            <td>
                                <?php if (isset($profit_loss['image']) && !empty($profit_loss['image']) && file_exists($profit_loss['image'])) : ?>
                                    <img src="<?= htmlspecialchars($profit_loss['image']) ?>" alt="Product Image" width="50" data-translate-key="Image">
                                <?php else : ?>
                                    <img src="/assets/images/default-image.jpg" alt="No Image" width="50" data-translate-key="No_Image">
                                <?php endif; ?>
                            </td>
                            <td data-translate-key="Product_Name"><?= isset($profit_loss['Product_Name']) ? htmlspecialchars($profit_loss['Product_Name']) : 'N/A' ?></td>
                            <td>
                                <?php
                                $profit_loss_value = isset($profit_loss['Profit_Loss']) ? floatval($profit_loss['Profit_Loss']) : 0;
                                $result_type = isset($profit_loss['Result_Type']) ? $profit_loss['Result_Type'] : 'N/A';
                                $class = ($result_type === 'Profit') ? 'profit' : (($result_type === 'Loss') ? 'loss' : '');
                                ?>
                                <span class="<?= $class ?>"><?= number_format($profit_loss_value, 2) ?></span>
                                <span class="numeric-value" style="display: none;"><?= $profit_loss_value ?></span>
                            </td>
                            <td><span class="<?= $class ?>" data-translate-key="<?= htmlspecialchars($result_type) ?>"><?= htmlspecialchars($result_type) ?></span></td>
                            <td>
                                <?= isset($profit_loss['Sale_Date']) && strtotime($profit_loss['Sale_Date']) !== false ? date('Y-m-d', strtotime($profit_loss['Sale_Date'])) : 'N/A' ?>
                            </td>
                            <td>
                                <button class="delete-single" data-id="<?= isset($profit_loss['ID']) ? htmlspecialchars($profit_loss['ID']) : '' ?>"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                    <?php
                    }
                } else {
                    error_log("list.php: Profit_Loss is empty or not an array");
                    ?>
                    <tr>
                        <td colspan="7" data-translate-key="No_Profit_Loss_Data_Found">No profit/loss data found.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="pagination-controls">
            <?php if ($currentPage > 1) : ?>
                <a href="?page=<?= $currentPage - 1 ?>"><i class="fa-solid fa-less-than"></i></a>
            <?php else : ?>
                <button disabled><i class="fa-solid fa-less-than"></i></button>
            <?php endif; ?>
            <span data-translate-key="Page_Of">Page <?= $currentPage ?> of <?= $totalPages ?></span>
            <?php if ($currentPage < $totalPages) : ?>
                <a href="?page=<?= $currentPage + 1 ?>"><i class="fa-solid fa-greater-than"></i></a>
            <?php else : ?>
                <button disabled><i class="fa-solid fa-greater-than"></i></button>
            <?php endif; ?>
        </div>
    </div>

    <div class="total-values" style="display: none;">
        <div class="total-price" id="profit-total" data-translate-key="Total_Profit">Total Profit: <span>0.00</span></div>
        <div class="total-price" id="loss-total" data-translate-key="Total_Loss">Total Loss: <span>0.00</span></div>
    </div>

    <button class="delete-btn" id="delete-selected" data-translate-key="Delete_Selected">Delete Selected</button>

    <div class="message" id="delete-message"></div>

    <div class="confirm-modal" id="confirm-modal" role="dialog" aria-labelledby="confirm-modal-title">
        <div class="confirm-modal-content">
            <p id="confirm-modal-title" data-translate-key="Confirm_Delete_Items">Are you sure you want to delete?</p>
            <div class="confirm-modal-buttons">
                <button id="confirm-yes" data-translate-key="Yes">Yes</button>
                <button id="confirm-no" data-translate-key="No">No</button>
            </div>
        </div>
    </div>
</div>

<!-- Include Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    // Initialize Flatpickr for date range
    flatpickr("#date-range", {
        mode: "range",
        dateFormat: "Y-m-d",
        defaultDate: ["<?= date('Y-m-d', strtotime('-1 month')) ?>", "<?= date('Y-m-d') ?>"],
        minDate: "2025-01-01",
        onChange: function(selectedDates) {
            if (selectedDates.length === 2) {
                const startDate = selectedDates[0].toISOString().split('T')[0];
                const endDate = selectedDates[1].toISOString().split('T')[0];
                filterTable(document.getElementById('filter-dropdown').value, startDate, endDate);
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const baseUrl = '<?= BASE_URL ?>';
        const currentLang = localStorage.getItem('selectedLanguage') || 'en';
        console.log('Base URL:', baseUrl);
        console.log('Current Language:', currentLang);

        const selectAllCheckbox = document.getElementById('select-all');
        const selectItemCheckboxes = document.querySelectorAll('.select-item');
        const deleteButton = document.getElementById('delete-selected');
        const confirmModal = document.getElementById('confirm-modal');
        const confirmYes = document.getElementById('confirm-yes');
        const confirmNo = document.getElementById('confirm-no');
        const deleteMessage = document.getElementById('delete-message');
        const profitTotalSpan = document.querySelector('#profit-total span');
        const lossTotalSpan = document.querySelector('#loss-total span');
        const filterDropdown = document.getElementById('filter-dropdown');
        const filterDropdownContainer = document.querySelector('.filter-dropdown-container');
        const searchInput = document.getElementById('search-input');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        console.log('CSRF Token:', csrfToken || 'Not found');

        // Apply translations on page load
        applyTranslations(currentLang);

        if (!csrfToken) {
            deleteMessage.textContent = getTranslation('CSRF_Token_Missing', currentLang);
            deleteMessage.classList.add('show');
            setTimeout(() => deleteMessage.classList.remove('show'), 5000);
        }

        // Add interactivity to dropdown
        filterDropdown.addEventListener('focus', () => {
            filterDropdownContainer.classList.add('active');
        });
        filterDropdown.addEventListener('blur', () => {
            filterDropdownContainer.classList.remove('active');
        });

        function normalizeDate(dateStr) {
            const date = new Date(dateStr);
            if (isNaN(date.getTime())) {
                console.error('Invalid date:', dateStr);
                return null;
            }
            return date.toISOString().split('T')[0];
        }

        function updateDeleteButton() {
            const checkedBoxes = document.querySelectorAll('.select-item:checked');
            deleteButton.classList.toggle('show', checkedBoxes.length > 0);
        }

        function calculateTotals() {
            let profitTotal = 0;
            let lossTotal = 0;
            document.querySelectorAll('#purchase-table tr:not([style*="display: none"])').forEach(row => {
                const numericValue = parseFloat(row.querySelector('.numeric-value')?.textContent) || 0;
                const resultType = row.querySelector('td:nth-child(5) span')?.textContent.trim();
                // Compare with translated values to support language switching
                if (resultType === getTranslation('Profit', currentLang)) {
                    profitTotal += numericValue;
                } else if (resultType === getTranslation('Loss', currentLang)) {
                    lossTotal += numericValue;
                }
            });
            // Update the UI with formatted totals
            profitTotalSpan.textContent = profitTotal.toFixed(2);
            lossTotalSpan.textContent = lossTotal.toFixed(2);
            // Re-apply translations to ensure labels are correct
            applyTranslationsToElement(document.querySelector('#profit-total'), currentLang);
            applyTranslationsToElement(document.querySelector('#loss-total'), currentLang);
        }

        function filterTable(filterType, customStartDate = null, customEndDate = null) {
            const today = new Date();
            let startDate, endDate;
            if (customStartDate && customEndDate) {
                startDate = customStartDate;
                endDate = customEndDate;
            } else {
                switch (filterType) {
                    case 'today':
                        startDate = endDate = today.toISOString().split('T')[0];
                        break;
                    case 'this-week':
                        startDate = new Date(today);
                        startDate.setDate(today.getDate() - today.getDay());
                        endDate = new Date(today);
                        endDate.setDate(startDate.getDate() + 6);
                        startDate = startDate.toISOString().split('T')[0];
                        endDate = endDate.toISOString().split('T')[0];
                        break;
                    case 'last-week':
                        startDate = new Date(today);
                        startDate.setDate(today.getDate() - today.getDay() - 7);
                        endDate = new Date(today);
                        endDate.setDate(today.getDate() - today.getDay() - 1);
                        startDate = startDate.toISOString().split('T')[0];
                        endDate = endDate.toISOString().split('T')[0];
                        break;
                    case 'this-month':
                        startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                        endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                        startDate = startDate.toISOString().split('T')[0];
                        endDate = endDate.toISOString().split('T')[0];
                        break;
                    case 'last-month':
                        startDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                        endDate = new Date(today.getFullYear(), today.getMonth(), 0);
                        startDate = startDate.toISOString().split('T')[0];
                        endDate = endDate.toISOString().split('T')[0];
                        break;
                    case 'all':
                    default:
                        startDate = '2000-01-01';
                        endDate = '2099-12-31';
                        break;
                }
            }
            document.querySelectorAll('#purchase-table tr').forEach(row => {
                const saleDateRaw = row.getAttribute('data-date');
                const saleDate = normalizeDate(saleDateRaw);
                if (saleDate && saleDate >= startDate && saleDate <= endDate) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
            calculateTotals();
        }

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            document.querySelectorAll('#purchase-table tr').forEach(row => {
                const productName = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase();
                row.style.display = productName?.includes(query) ? '' : 'none';
            });
            calculateTotals();
        });

        deleteButton.addEventListener('click', function(e) {
            e.preventDefault();
            const checkedBoxes = document.querySelectorAll('.select-item:checked');
            if (checkedBoxes.length === 0) {
                deleteMessage.textContent = getTranslation('Please_Select_At_Least_One_Item', currentLang);
                deleteMessage.classList.add('show');
                setTimeout(() => deleteMessage.classList.remove('show'), 3000);
                return;
            }
            confirmModal.classList.add('show');
            applyTranslationsToElement(confirmModal, currentLang);
            confirmYes.onclick = function() {
                const idsToDelete = Array.from(checkedBoxes)
                    .map(cb => cb.dataset.id)
                    .filter(id => id && !isNaN(id))
                    .map(Number);
                console.log('Bulk delete IDs:', idsToDelete);
                if (idsToDelete.length === 0) {
                    deleteMessage.textContent = getTranslation('No_Valid_Items_Selected', currentLang);
                    deleteMessage.classList.add('show');
                    setTimeout(() => deleteMessage.classList.remove('show'), 3000);
                    confirmModal.classList.remove('show');
                    return;
                }
                document.body.classList.add('loading');
                fetch(`${baseUrl}/profit_loss/destroy_multiple`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-Token': csrfToken
                        },
                        body: JSON.stringify({
                            ids: idsToDelete
                        })
                    })
                    .then(response => {
                        console.log('Bulk delete response status:', response.status);
                        if (!response.ok) throw new Error(`HTTP error: ${response.status}`);
                        return response.json();
                    })
                    .then(data => {
                        document.body.classList.remove('loading');
                        console.log('Bulk delete response:', data);
                        if (data.success) {
                            checkedBoxes.forEach(checkbox => {
                                checkbox.closest('tr').remove();
                            });
                            deleteMessage.textContent = idsToDelete.length > 1 ?
                                getTranslation('Records_Deleted_Successfully', currentLang) :
                                getTranslation('Record_Deleted_Successfully', currentLang);
                            deleteMessage.classList.add('show');
                            setTimeout(() => deleteMessage.classList.remove('show'), 3000);
                            calculateTotals();
                        } else {
                            deleteMessage.textContent = getTranslation('Deletion_Failed', currentLang) + `: ${data.message || 'Unknown error'}`;
                            deleteMessage.classList.add('show');
                            setTimeout(() => deleteMessage.classList.remove('show'), 3000);
                        }
                    })
                    .catch(error => {
                        document.body.classList.remove('loading');
                        console.error('Bulk delete error:', error);
                        deleteMessage.textContent = getTranslation('Error_Network', currentLang);
                        deleteMessage.classList.add('show');
                        setTimeout(() => deleteMessage.classList.remove('show'), 3000);
                    });
                confirmModal.classList.remove('show');
                updateDeleteButton();
            };
        });

        document.querySelectorAll('.delete-single').forEach(button => {
            button.addEventListener('click', function() {
                const id = Number(this.dataset.id);
                console.log('Single delete ID:', id);
                if (!id || isNaN(id)) {
                    deleteMessage.textContent = getTranslation('Invalid_Item_Selected', currentLang);
                    deleteMessage.classList.add('show');
                    setTimeout(() => deleteMessage.classList.remove('show'), 3000);
                    return;
                }
                confirmModal.classList.add('show');
                applyTranslationsToElement(confirmModal, currentLang);
                confirmYes.onclick = function() {
                    document.body.classList.add('loading');
                    fetch(`${baseUrl}/profit_loss/destroy/${id}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-Token': csrfToken
                            }
                        })
                        .then(response => {
                            console.log('Single delete response status:', response.status);
                            if (!response.ok) throw new Error(`HTTP error: ${response.status}`);
                            return response.json();
                        })
                        .then(data => {
                            document.body.classList.remove('loading');
                            console.log('Single delete response:', data);
                            if (data.success) {
                                button.closest('tr').remove();
                                deleteMessage.textContent = getTranslation('Record_Deleted_Successfully', currentLang);
                                deleteMessage.classList.add('show');
                                setTimeout(() => deleteMessage.classList.remove('show'), 3000);
                                calculateTotals();
                            } else {
                                deleteMessage.textContent = getTranslation('Deletion_Failed', currentLang) + `: ${data.message || 'Unknown error'}`;
                                deleteMessage.classList.add('show');
                                setTimeout(() => deleteMessage.classList.remove('show'), 3000);
                            }
                        })
                        .catch(error => {
                            document.body.classList.remove('loading');
                            console.error('Single delete error:', error);
                            deleteMessage.textContent = getTranslation('Error_Network', currentLang);
                            deleteMessage.classList.add('show');
                            setTimeout(() => deleteMessage.classList.remove('show'), 3000);
                        });
                    confirmModal.classList.remove('show');
                    updateDeleteButton();
                };
            });
        });

        confirmNo.addEventListener('click', function() {
            confirmModal.classList.remove('show');
        });

        selectAllCheckbox.addEventListener('change', function() {
            selectItemCheckboxes.forEach(checkbox => {
                if (checkbox.closest('tr').style.display !== 'none') {
                    checkbox.checked = this.checked;
                }
            });
            updateDeleteButton();
        });

        selectItemCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateDeleteButton);
        });

        filterDropdown.addEventListener('change', function() {
            filterTable(this.value);
        });

        // Initial setup
        updateDeleteButton();
        filterTable('all');
    });
</script>

<?php
require_once './views/layouts/footer.php';
?>