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
    }
</style>

<div class="main-content">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all" title="Select All"></th>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="checkbox" class="select-item"></td>
                    <td><img src="https://via.placeholder.com/50" alt="Product 1"></td>
                    <td>Sample Product 1</td>
                    <td>10</td>
                    <td>$25.00</td>
                    <td><button class="remove-btn" data-id="1">Remove</button></td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="select-item"></td>
                    <td><img src="https://via.placeholder.com/50" alt="Product 2"></td>
                    <td>Sample Product 2</td>
                    <td>5</td>
                    <td>$15.00</td>
                    <td><button class="remove-btn" data-id="2">Remove</button></td>
                </tr>
            </tbody>
        </table>
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

    // Individual Checkbox Toggle for Select All
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            selectAll.checked = [...checkboxes].every(cb => cb.checked);
        });
    });

    // Remove Button with Confirmation
    document.querySelectorAll('.remove-btn').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            if (confirm(`Are you sure you want to remove item with ID: ${id}?`)) {
                this.closest('tr').remove();
                console.log(`Removed item with ID: ${id}`);
            }
        });
    });
</script>

<?php
// Assuming footer is included elsewhere or needed
// require_once './views/layouts/footer.php';
?>