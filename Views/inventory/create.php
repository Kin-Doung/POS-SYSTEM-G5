<?php require_once './views/layouts/header.php'; ?>
<?php require_once './views/layouts/side.php'; ?>

<div class="container-fluid add-invnetory">
    <div class="row">
        <div class="d-flex justify-content-center align-items-center">
            <div class="col-md-12 col-lg-11 col-xl-10 p-4 rounded shadow-sm add-card-inventory">
                <h2 class="mb-4 text-center">Add Inventory Item</h2>
                <form id="inventoryForm" action="/inventory/store" method="POST" enctype="multipart/form-data">
                    <!-- Display added items here -->
                    <div id="addedItems" class="d-flex flex-wrap gap-3 mb-3">
                        <!-- Dynamically added items will appear here -->
                    </div>

                    <div class="d-flex flex-wrap gap-3">
                        <!-- Image Upload -->
                        <div class="flex-grow-1">
                            <label for="image" class="form-label">Image:</label>
                            <input type="file" name="image" class="form-control add-item add-image" id="image" accept="image/*">
                        </div>
                        <!-- Product Name -->
                        <div class="flex-grow-1">
                            <label for="product_name" class="form-label">Product Name:</label>
                            <input type="text" name="product_name" class="form-control add-item" id="product_name">
                        </div>
                        <!-- Quantity -->
                        <div class="flex-grow-1">
                            <label for="quantity" class="form-label">Quantity:</label>
                            <input type="number" name="quantity" class="form-control add-item" id="quantity">
                        </div>
                        <!-- Amount -->
                        <div class="flex-grow-1">
                            <label for="amount" class="form-label">Amount:</label>
                            <input type="number" name="amount" class="form-control add-item" id="amount">
                        </div>
                        <!-- Expiration Date -->
                        <div class="flex-grow-1">
                            <label for="expiration_date" class="form-label">Expiration Date:</label>
                            <input type="date" name="expiration_date" class="form-control add-item" id="expiration_date">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-2">
                        <button type="button" id="addMoreButton" class="btn btn-secondary">Add More</button>
                        <button type="submit" class="btn btn-primary add-create">Add Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>





<script>

document.addEventListener('DOMContentLoaded', function () {
    const addMoreButton = document.getElementById('addMoreButton');
    const inventoryForm = document.getElementById('inventoryForm');
    const addedItemsContainer = document.getElementById('addedItems');

    // Load saved items from localStorage if they exist
    loadSavedItems();

    // Handle "Add More" button click
    addMoreButton.addEventListener('click', function () {
        const productName = document.getElementById('product_name').value;
        const quantity = document.getElementById('quantity').value;
        const amount = document.getElementById('amount').value;
        const expirationDate = document.getElementById('expiration_date').value;
        const image = document.getElementById('image').files[0];

        if (productName && quantity && amount && expirationDate) {
            const newItem = {
                product_name: productName,
                quantity: quantity,
                amount: amount,
                expiration_date: expirationDate,
                image: image ? image.name : 'No image'
            };

            // Get existing items from localStorage
            const items = JSON.parse(localStorage.getItem('inventoryItems')) || [];

            // Add the new item
            items.push(newItem);

            // Save the updated items to localStorage
            localStorage.setItem('inventoryItems', JSON.stringify(items));

            // Display the new item
            displayItem(newItem);

            // Reset the form for the next entry
            inventoryForm.reset();
        } else {
            alert("Please fill in all fields before adding more.");
        }
    });

    // Display an item in the flex container
    function displayItem(item) {
        const itemDiv = document.createElement('div');
        itemDiv.classList.add('d-flex', 'align-items-center', 'gap-3', 'added-item');

        itemDiv.innerHTML = `
            <div class="d-flex ">
                <p><strong>Product:</strong> ${item.product_name}</p>
                <p><strong>Quantity:</strong> ${item.quantity}</p>
                <p><strong>Amount:</strong> ${item.amount}</p>
                <p><strong>Expiration Date:</strong> ${item.expiration_date}</p>
                <p><strong>Image:</strong> ${item.image}</p>
            </div>
            <button class="btn btn-danger remove-item">Remove</button>
        `;

        // Append to the container
        addedItemsContainer.appendChild(itemDiv);

        // Add remove functionality
        const removeButton = itemDiv.querySelector('.remove-item');
        removeButton.addEventListener('click', function () {
            removeItem(item);
            itemDiv.remove();
        });
    }

    // Remove an item from localStorage
    function removeItem(itemToRemove) {
        const items = JSON.parse(localStorage.getItem('inventoryItems')) || [];
        const updatedItems = items.filter(item => item.product_name !== itemToRemove.product_name);
        localStorage.setItem('inventoryItems', JSON.stringify(updatedItems));
    }

    // Load and display items saved in localStorage
    function loadSavedItems() {
        const items = JSON.parse(localStorage.getItem('inventoryItems')) || [];
        items.forEach(item => displayItem(item));
    }
});




</script>
