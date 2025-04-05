<?php
require_once './views/layouts/header.php';
require_once './views/layouts/side.php';
?>

<style>
    /* Profit/Loss Styles */

    /* Main content wrapper to account for sidebar */
    .main-content {
        margin-left: 250px;
        /* Adjust this based on your sidebar width */
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
        /* Custom checkbox color */
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
        gap: 25px;
        align-items: center;
        /* Align date inputs vertically centered */
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
        width: 420px;
        /* Fixed width for consistency */
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

    .profit {
        color: green;
        font-weight: bold;
        /* Optional */
    }

    .loss {
        color: red;
        font-weight: bold;
        /* Optional */
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
                    <th>Profit Loss</th>
                    <th>Result Type</th>
                    <th>Date of Sale</th>
                </tr>
            </thead>
            <tbody id="purchase-table">
                <?php if (!empty($Profit_Loss) && is_array($Profit_Loss)) : ?>
                    <?php foreach ($Profit_Loss as $profit_loss) : ?>
                        <tr data-date="<?= isset($profit_loss['created_at']) ? $profit_loss['created_at'] : '' ?>">
                            <td><input type="checkbox" class="select-item" data-id="<?= isset($profit_loss['id']) ? $profit_loss['id'] : '' ?>"></td>
                            <td>
                                <?php if (isset($profit_loss['image']) && !empty($profit_loss['image'])) : ?>
                                    <img src="<?= $profit_loss['image'] ?>" alt="Product Image" width="50">
                                <?php else : ?>
                                    <img src="path/to/default-image.jpg" alt="No Image" width="50">
                                <?php endif; ?>
                            </td>
                            <td><?= isset($profit_loss['Product_Name']) ? $profit_loss['Product_Name'] : 'N/A' ?></td>
                            <td>
                                <?php
                                $profit_loss_value = isset($profit_loss['Profit_Loss']) ? $profit_loss['Profit_Loss'] : 'N/A';
                                $result_type_class = ($profit_loss_value == 'Profit') ? 'profit' : (($profit_loss_value == 'Loss') ? 'loss' : '');
                                ?>
                                <span class="<?= $result_type_class ?>">
                                    <?= $profit_loss_value ?>
                                </span>
                            </td>
                            <td>
                                <?php
                                $result_type_value = isset($profit_loss['Result_Type']) ? $profit_loss['Result_Type'] : 'N/A';
                                $result_type_class = ($result_type_value === 'Profit') ? 'profit' : (($result_type_value === 'Loss') ? 'loss' : '');
                                ?>
                                <span class="<?= $result_type_class ?>">
                                    <?= $result_type_value ?>
                                </span>
                            </td>
                            <td><?= isset($profit_loss['Sale_Date']) ? $profit_loss['Sale_Date'] : 'N/A' ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">No profit/loss data found.</td>
                    </tr>
                <?php endif; ?>
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

    <!-- Total Price Display -->
    <div class="total-price">
        Total Price: <span>$</span>
    </div>
</div>



<?php
require_once './views/layouts/footer.php';
?>