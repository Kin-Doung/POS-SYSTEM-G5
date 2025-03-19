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

  const products = Array.from(document.querySelectorAll(".card")).map(
    (card) => {
      return {
        name: card.getAttribute("data-name"),
        price: parseFloat(card.getAttribute("data-price")),
        quantityId: card.querySelector(".quantity").id,
      };
    }
  );

  let totalOrderPrice = 0;

  products.forEach((product) => {
    const quantity =
      parseInt(document.getElementById(product.quantityId).value) || 0;
    const totalPrice = (product.price * quantity).toFixed(2);
    totalOrderPrice += parseFloat(totalPrice);

    if (quantity > 0) {
      const row = document.createElement("tr");
      row.innerHTML = `<td>${
        product.name
      }</td><td>${quantity}</td><td>$${product.price.toFixed(
        2
      )}</td><td>$${totalPrice}</td>`;
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
  const searchInput = document
    .getElementById("searchInput")
    .value.toLowerCase();
  const categorySelect = document.getElementById("categorySelect").value;
  const priceSelect = document.getElementById("priceSelect").value;

  const products = document.querySelectorAll(".product"); // All products on the page

  products.forEach((product) => {
    const productName = product.getAttribute("data-product-name").toLowerCase();
    const productCategory = product.getAttribute("data-category");
    const productPrice = parseFloat(product.getAttribute("data-price"));

    // Check category match
    const matchesCategory =
      categorySelect === "all" || categorySelect === productCategory;

    // Check search match
    const matchesSearch = productName.includes(searchInput);

    // Check price match
    const matchesPrice =
      priceSelect === "" ||
      (priceSelect === "0" && productPrice <= 10) ||
      (priceSelect === "15" && productPrice <= 15) ||
      (priceSelect === "20" && productPrice <= 20) ||
      (priceSelect === "25" && productPrice <= 25) ||
      (priceSelect === "30" && productPrice <= 30);

    // Show product if all conditions are met
    if (matchesCategory && matchesSearch && matchesPrice) {
      product.style.display = ""; // Show product
    } else {
      product.style.display = "none"; // Hide product
    }
  });
}

function saveToPDF() {
  // Hide the buttons temporarily before saving to PDF
  const buttons = document.querySelector('.buys');
  buttons.style.display = 'none';  // Hide the buttons

  // Select the content to be saved (everything except the buttons)
  const content = document.querySelector('.detail-section');

  // PDF options for better formatting
  const options = {
      margin: 10,
      filename: 'order_summary.pdf',
      image: { type: 'jpeg', quality: 0.98 },
      html2canvas: { scale: 2 },
      jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
  };

  // Convert the selected content to PDF and save it
  html2pdf().from(content).set(options).save();

  // Re-show the buttons after PDF is saved
  buttons.style.display = 'block';  // Show the buttons again
}

function processPurchase() {
  const total = document.getElementById("totalPrice").innerText;
  const rows = document.querySelectorAll(".order-table tbody tr");

  if (rows.length === 0) {
    alert("Please add items to your cart before purchasing!");
    return;
  }

  alert(
    `Purchase successful!\n${total}\nThank you for shopping with Cooking Electrice!`
  );
  document
    .querySelectorAll(".quantity")
    .forEach((input) => (input.value = "0"));
  updateDetails();
}

// Modal functionality
document.getElementById("addProductBtn").onclick = function () {
  document.getElementById("productModal").style.display = "block";
};

document.getElementById("closeModal").onclick = function () {
  document.getElementById("productModal").style.display = "none";
};

function previewImage(event) {
  const imagePreview = document.getElementById("imagePreview");
  const file = event.target.files[0];
  const reader = new FileReader();

  reader.onload = function () {
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
  const name = document.getElementById("productName").value;
  const image = document.getElementById("imagePreview").src; // Use the preview image
  const price = parseFloat(document.getElementById("productPrice").value);

  if (name && image && !isNaN(price) && price >= 0) {
    const productGrid = document.getElementById("productGrid");

    const newCard = document.createElement("div");
    newCard.className = "card";
    newCard.setAttribute("data-name", name);
    newCard.setAttribute("data-category", "custom"); // Change as needed
    newCard.setAttribute("data-price", price);

    newCard.innerHTML = `
            <div class="product-name">${name}</div>
            <img src="${image}" alt="${name}" />
            <div class="price">Price: $${price.toFixed(2)}</div>
            <input type="number" class="quantity" id="quantity${
              productGrid.children.length + 1
            }" value="0" min="0" />
            <button class="buy-button" onclick="updateCart(${price}, 'quantity${
      productGrid.children.length + 1
    }', true)">Add to Cart</button>
            <button class="subtract-button" onclick="updateCart(${price}, 'quantity${
      productGrid.children.length + 1
    }', false)">Remove</button>
        `;

    productGrid.appendChild(newCard);
    updateDetails(); // Update details to include the new product
    document.getElementById("productModal").style.display = "none"; // Close modal
  } else {
    alert("Please fill in all fields correctly.");
  }
}
// JavaScript to toggle the visibility of the detail section
document
  .getElementById("showDetailsButton")
  .addEventListener("click", function () {
    const detailSection = document.querySelector(".detail-section");
    detailSection.style.display =
      detailSection.style.display === "none" ||
      detailSection.style.display === ""
        ? "block"
        : "none";
  });

window.onload = function () {
  updateDetails();
};

// search function
