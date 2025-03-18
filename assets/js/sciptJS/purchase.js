function updateCart(price, quantityId, isAdding) {
    const quantityInput = document.getElementById(quantityId);
    let quantity = parseInt(quantityInput.value) || 0;

    if (isAdding) {
        quantityInput.value = quantity + 1;
    } else if (quantity > 0) {
        quantityInput.value = quantity - 1;
    }

    updateDetails(); // Update the cart total immediately
}

function updateDetails() {
    const detailsContainer = document.getElementById("details");
    detailsContainer.innerHTML = "";

    const products = Array.from(document.querySelectorAll(".card")).map(card => {
        return {
            name: card.getAttribute("data-name"),
            price: parseFloat(card.getAttribute("data-price")),
            quantityId: card.querySelector(".quantity").id
        };
    });

    let totalOrderPrice = 0;

    products.forEach((product) => {
        const quantity = parseInt(document.getElementById(product.quantityId).value) || 0;
        const totalPrice = (product.price * quantity).toFixed(2);
        totalOrderPrice += parseFloat(totalPrice);

        if (quantity > 0) {
            const row = document.createElement("tr");
            row.innerHTML = `<td>${product.name}</td><td>${quantity}</td><td>$${product.price.toFixed(2)}</td><td>$${totalPrice}</td>`;
            detailsContainer.appendChild(row);
        }
    });

    document.getElementById("totalPrice").innerText = "Cart Total: $" + totalOrderPrice.toFixed(2);
  
}

function generateQRCode(total) {
    const qr = new QRious({
        element: document.getElementById('qrCode'),
        value: `Total Amount: $${total.toFixed(2)}`,
        size: 100,
        background: 'white',
        foreground: 'black',
    });
}

function filterProducts() {
    const searchInput = document.getElementById("searchInput").value.toLowerCase();
    const productSelect = document.getElementById("productSelect").value.toLowerCase();
    const categorySelect = document.getElementById("categorySelect").value;
    const priceSelect = parseInt(document.getElementById("priceSelect").value) || Infinity;
    const cards = document.querySelectorAll(".card");

    cards.forEach((card) => {
        const productName = card.getAttribute("data-name").toLowerCase();
        const productCategory = card.getAttribute("data-category");
        const productPrice = parseInt(card.getAttribute("data-price"));

        const matchesSearch = !searchInput || productName.includes(searchInput);
        const matchesProduct = !productSelect || productName === productSelect;
        const matchesCategory = !categorySelect || categorySelect === "all" || productCategory === categorySelect;
        const matchesPrice = productPrice <= priceSelect;

        card.style.display = matchesSearch && matchesProduct && matchesCategory && matchesPrice ? "block" : "none";
    });
}

function saveToPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    doc.setFontSize(18);
    doc.text("Cooking Electrice Order", 10, 10);

    doc.setFontSize(12);
    doc.text(`Date: ${new Date().toLocaleDateString()}`, 10, 20);

    const table = document.querySelector(".order-table");
    const rows = table.querySelectorAll("tbody tr");
    const total = document.getElementById("totalPrice").innerText;

    doc.setFontSize(12);
    let y = 30;
    doc.text("Product", 10, y);
    doc.text("Qty", 70, y);
    doc.text("Price", 90, y);
    doc.text("Total", 120, y);
    y += 5;
    doc.line(10, y, 150, y);

    y += 5;
    rows.forEach(row => {
        const cells = row.querySelectorAll("td");
        doc.text(cells[0].innerText, 10, y);
        doc.text(cells[1].innerText, 70, y);
        doc.text(cells[2].innerText, 90, y);
        doc.text(cells[3].innerText, 120, y);
        y += 10;
    });

    y += 5;
    doc.line(10, y, 150, y);
    y += 10;
    doc.setFontSize(14);
    doc.text(total, 10, y);

    // Add the QR code
    const qrCodeCanvas = document.getElementById('qrCode');
    const qrCodeDataUrl = qrCodeCanvas.toDataURL('image/png');
    doc.addImage(qrCodeDataUrl, 'PNG', 10, y + 20, 50, 50); // Adjust size and position as needed

    // Save the PDF
    doc.save("order_details.pdf");
}

function processPurchase() {
    const total = document.getElementById("totalPrice").innerText;
    const rows = document.querySelectorAll(".order-table tbody tr");

    if (rows.length === 0) {
        alert("Please add items to your cart before purchasing!");
        return;
    }

    alert(`Purchase successful!\n${total}\nThank you for shopping with Cooking Electrice!`);
    document.querySelectorAll(".quantity").forEach(input => input.value = "0");
    updateDetails();
}

// Modal functionality
document.getElementById('addProductBtn').onclick = function() {
    document.getElementById('productModal').style.display = "block";
};

document.getElementById('closeModal').onclick = function() {
    document.getElementById('productModal').style.display = "none";
};

function previewImage(event) {
    const imagePreview = document.getElementById('imagePreview');
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function() {
        imagePreview.src = reader.result;
        imagePreview.style.display = "block"; // Show the image preview
    };

    if (file) {
        reader.readAsDataURL(file);
    } else {
        imagePreview.src = "";
        imagePreview.style.display = "none"; // Hide the image preview
    }
}

function addProduct() {
    const name = document.getElementById('productName').value;
    const image = document.getElementById('imagePreview').src; // Use the preview image
    const price = parseFloat(document.getElementById('productPrice').value);

    if (name && image && !isNaN(price) && price >= 0) {
        const productGrid = document.getElementById('productGrid');

        const newCard = document.createElement('div');
        newCard.className = 'card';
        newCard.setAttribute('data-name', name);
        newCard.setAttribute('data-category', 'custom'); // Change as needed
        newCard.setAttribute('data-price', price);

        newCard.innerHTML = `
            <div class="product-name">${name}</div>
            <img src="${image}" alt="${name}" />
            <div class="price">Price: $${price.toFixed(2)}</div>
            <input type="number" class="quantity" id="quantity${productGrid.children.length + 1}" value="0" min="0" />
            <button class="buy-button" onclick="updateCart(${price}, 'quantity${productGrid.children.length + 1}', true)">Add to Cart</button>
            <button class="subtract-button" onclick="updateCart(${price}, 'quantity${productGrid.children.length + 1}', false)">Remove</button>
        `;

        productGrid.appendChild(newCard);
        updateDetails(); // Update details to include the new product
        document.getElementById('productModal').style.display = "none"; // Close modal
    } else {
        alert("Please fill in all fields correctly.");
    }

}



window.onload = function() {
    updateDetails();
};

// search function 

