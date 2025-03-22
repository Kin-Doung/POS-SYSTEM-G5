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

  document.getElementById("totalPrice").innerText =
    "Cart Total: $" + totalOrderPrice.toFixed(2);
  generateQRCode(totalOrderPrice); // Generate QR code for the total
}



function saveToPDF() {
  const content = document.querySelector(".detail-section");

  if (!content) {
      console.error("Error: .detail-section not found!");
      return;
  }

  console.log("Saving PDF for:", content); // Debugging

  html2pdf().from(content).save();
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

function processRestock() {
  let products = [];
  
  // Collect products and quantities from the DOM
  document.querySelectorAll(".product-grid .card").forEach(card => {
    let quantity = parseInt(card.querySelector(".quantity").value);
    
    if (quantity > 0) {
      products.push({
        id: card.getAttribute("data-id"),
        quantity: quantity
      });
    }
  });

  // If no products are selected, show an alert
  if (products.length === 0) {
    alert("No products selected for restock.");
    return;
  }

  // Perform the fetch request to the backend
  fetch('/purchase/restock', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ products: products })
  })
  .then(response => {
    // Check if the response is okay (status 200-299)
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    return response.json();
  })
  .then(data => {
    // Handle success or failure based on the response
    if (data.success) {
      alert("Restock successful!");
      location.reload();
    } else {
      alert("Restock failed: " + (data.message || 'Unknown error'));
    }
  })
  .catch(error => {
    // Log the error to the console and show an alert
    console.error('Error:', error);
    alert("An error occurred while processing the restock. Please try again.");
  });
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
function processRestock(purchaseId) {
  // Prompt the user for the restock quantity
  const quantity = prompt("Enter the quantity to restock:");

  // Check if the quantity is valid
  if (quantity && !isNaN(quantity) && quantity > 0) {
      // Create the request data
      const xhr = new XMLHttpRequest();
      xhr.open("POST", "/restock", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

      xhr.onreadystatechange = function () {
          if (xhr.readyState === 4 && xhr.status === 200) {
              alert("Stock updated successfully!");
              location.reload(); // Reload the page to reflect the new quantity
          } else if (xhr.readyState === 4) {
              alert("Error: " + xhr.statusText);
          }
      };

      // Send the request with purchase ID and quantity
      xhr.send("id=" + purchaseId + "&quantity=" + quantity);
  } else {
      alert("Please enter a valid quantity.");
  }
}




function updateCartTotal() {
  let total = 0;
  document.querySelectorAll("#details tr").forEach(row => {
      let totalText = row.querySelector(".total")?.innerText.replace("$", "") || "0";
      total += parseFloat(totalText);
  });

  console.log(`Updated Cart Total: $${total.toFixed(2)}`);
}

document.querySelectorAll(".restock-btn").forEach(button => {
  button.addEventListener("click", function () {
      let productId = this.getAttribute("data-id");
      processRestock(productId);
  });
});




