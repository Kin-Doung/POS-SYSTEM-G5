document.addEventListener("DOMContentLoaded", function() {
    const categorySelect = document.getElementById("categorySelect");
    const productItems = document.querySelectorAll(".product-item");
    const searchInput = document.getElementById("searchInput");

    // Function to filter products by category
    categorySelect.addEventListener("change", function() {
        const selectedCategoryId = this.value;

        productItems.forEach(item => {
            const productCategoryId = item.getAttribute("data-category-id");

            // Show all products if no category is selected, otherwise filter by selected category
            if (selectedCategoryId === "" || productCategoryId === selectedCategoryId) {
                item.style.display = "";
            } else {
                item.style.display = "none";
            }
        });
    });

    // Function for search functionality

});

document.addEventListener("DOMContentLoaded", function() {
    const showDetailsButton = document.getElementById("show-details-btn");
    const purchaseModal = document.getElementById("purchase-modal");
    const productDetailsList = document.getElementById("product-details-list");
    const overallTotal = document.getElementById("overall-total");
    const confirmPurchaseButton = document.getElementById("confirm-purchase");
    const closeModalButton = document.getElementById("close-modal");

    // Handle Show Details Button Click
    showDetailsButton.addEventListener("click", function() {
        // Clear previous content in the modal
        productDetailsList.innerHTML = '';
        let totalAmount = 0;

        // Gather all product details from the table rows
        const allRows = document.querySelectorAll("tbody tr");
        allRows.forEach(row => {
            const productName = row.querySelector("td:nth-child(2)").textContent.trim();
            const priceText = row.querySelector(".price").textContent.trim();
            const price = priceText === 'Free' ? 0 : parseFloat(priceText.replace("$", ""));
            const quantityInput = row.querySelector(".quantity");
            const quantity = parseInt(quantityInput.value);

            // Calculate total price for each product
            const totalPrice = price * quantity;
            totalAmount += totalPrice;

            // Create a table row for each product in the modal
            const productDetailRow = document.createElement("tr");
            productDetailRow.innerHTML = `
                <td>${productName}</td>
                <td>${quantity}</td>
                <td>$${price.toFixed(2)}</td>
                <td>$${totalPrice.toFixed(2)}</td>
            `;
            productDetailsList.appendChild(productDetailRow);
        });

        // Update overall total amount
        overallTotal.innerHTML = `<strong>Total Amount: $${totalAmount.toFixed(2)}</strong>`;

        // Show the modal
        purchaseModal.style.display = "block";
    });

    // Close the modal without confirming purchase
    closeModalButton.addEventListener("click", function() {
        purchaseModal.style.display = "none";
    });

    // Confirm the purchase and reset quantity for all rows to 0
    confirmPurchaseButton.addEventListener("click", function() {
        // Loop through all table rows and reset quantity to 0
        const allRows = document.querySelectorAll("tbody tr");
        allRows.forEach(row => {
            const quantityInput = row.querySelector(".quantity");
            quantityInput.value = 0; // Reset quantity to 0 for each item
        });

        // Close the modal after confirming the purchase
        purchaseModal.style.display = "none";
    });
});


let cart = [];

function addToCart(productId, name, price) {
    let existingProduct = cart.find(item => item.id === productId);

    if (existingProduct) {
        existingProduct.quantity += 1;
    } else {
        cart.push({
            id: productId,
            name: name,
            price: parseFloat(price),
            quantity: 1
        });
    }

    document.getElementById("cartSection").style.display = "block"; // Show cart
    updateCartTable();
}

function updateCartTable() {
    let cartTableBody = document.querySelector("#cartTable tbody");
    cartTableBody.innerHTML = "";

    let grandTotal = 0;

    cart.forEach((item, index) => {
        let totalPrice = item.price * item.quantity;
        grandTotal += totalPrice;

        let row = `
            <tr>
                <td>${item.name}</td>
                <td><input type="number" value="${item.quantity}" min="1" data-index="${index}" class="cart-quantity" /></td>
                <td>${totalPrice.toFixed(2)} $</td>
            </tr>
        `;
        cartTableBody.innerHTML += row;
    });

    document.getElementById("grandTotal").textContent = grandTotal.toFixed(2);

    document.querySelectorAll(".cart-quantity").forEach(input => {
        input.addEventListener("change", updateQuantity);
    });
}

function updateQuantity(event) {
    let index = event.target.getAttribute("data-index");
    let newQuantity = parseInt(event.target.value);
    let productId = cart[index].id;

    if (newQuantity > 0) {
        cart[index].quantity = newQuantity;
        updateCartTable();

        // Send the updated quantity to the server via AJAX
        updateQuantityInDatabase(productId, newQuantity);
    }
}

function removeFromCart(index) {
    cart.splice(index, 1);
    updateCartTable();

    if (cart.length === 0) {
        document.getElementById("cartSection").style.display = "none"; // Hide cart when empty
    }
}

function updateQuantityInDatabase(productId, newQuantity) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "update_quantity.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Handle response (optional)
            console.log(xhr.responseText);
        }
    };

    // Send the data
    xhr.send("product_id=" + productId + "&quantity=" + newQuantity);
}

function replaceCartInDatabase() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "replace_cart.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Handle response (optional)
            alert(xhr.responseText); // Show success message or error
        }
    };

    // Prepare cart data for replacement
    let cartData = cart.map(item => ({
        product_id: item.id,
        quantity: item.quantity
    }));

    // Send cart data to backend
    xhr.send("cart=" + JSON.stringify(cartData));
}

document.querySelectorAll(".buy").forEach(button => {
    button.addEventListener("click", function() {
        let productCard = this.closest(".card");
        let productId = productCard.querySelector("input[name='product_id']").value;
        let productName = productCard.querySelector(".card-title").textContent;
        let productPrice = productCard.querySelector(".card-text.price").textContent;

        addToCart(productId, productName, productPrice);
    });
});



    // Handle the price edit modal
    document.querySelectorAll('.edit-price-btn').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            document.querySelector('#editPriceForm input[name="product_id"]').value = productId;
            new bootstrap.Modal(document.getElementById('editPriceModal')).show();
        });
    });

    // Handle form submission for updating price
    document.getElementById('editPriceForm').addEventListener('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        fetch('/products/updatePrice', { // Use the correct route
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.message === 'Price updated successfully') {
                    // Optionally, update the price on the page dynamically
                    const productId = formData.get('product_id');
                    const newPrice = formData.get('price');
                    const priceElement = document.querySelector(`.product-item[data-product-id="${productId}"] .price`);
                    priceElement.textContent = `${newPrice} $`; // Update the price on the page
                }
            })
        .catch(error => console.error('Error:', error));
    });


