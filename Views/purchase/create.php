<?php require_once './views/layouts/header.php'; ?>
<?php require_once './views/layouts/side.php'; ?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    #addMore, #submit {
        border: none;
        padding: 8px 20px;
        border-radius: 20px;
        background: rgb(37, 37, 255);
        color: #fff;
        font-weight: bold;
    }
    #addMore:hover {
        background-color: darkblue;
        text-decoration: none;
        color: #fff;
    }
    #submit {
        background: rgb(10, 212, 10);
        padding: 8px 25px;
        transition: all 0.2s ease-in-out;
    }
    #submit:hover {
        background-color: green;
    }
    .group-add-purchase {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    table, th, td {
        border: 1px solid #ddd;
    }
    th, td {
        padding: 8px 12px;
        text-align: left;
    }
    th {
        background-color: #f4f4f4;
    }
    .removeRow {
        color: red;
        border: none;
        background: none;
        cursor: pointer;
    }
</style>

<main class="main-content create-content position-relative max-height-vh-100 h-100">
    <h2 class="text-center head-add" style="padding-top: 20px;">Add Stock Products</h2>
    <div class="col-md-12 mt-3 mx-auto">
        <div class="card p-3" style="box-shadow: none; border: none;">
            <form action="/purchase/store" method="POST" enctype="multipart/form-data">
                <table id="productTableBody">
                    <thead>
                        <tr>
                            <th>Product Image</th>
                            <th>Select Category</th>
                            <th>Product Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="product-row">
                            <td>
                                <input type="file" class="form-control" name="image[]" accept="image/*">
                            </td>
                            <td>
                                <select name="category_id[]" class="form-control" required>
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= htmlspecialchars($category['id']); ?>">
                                            <?= htmlspecialchars($category['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="product_name[]" required>
                            </td>
                            <td>
                                <button type="button" class="removeRow">Remove</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="group-add-purchase mt-3">
                    <button type="button" id="addMore">Add More</button>
                    <button type="submit" id="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    let rowIndex = 1;
    document.getElementById('addMore').addEventListener('click', function() {
        const tableBody = document.querySelector('#productTableBody tbody');
        const newRow = document.createElement('tr');
        newRow.classList.add('product-row');
        newRow.innerHTML = `
            <td><input type="file" class="form-control" name="image[]" accept="image/*"></td>
            <td>
                <select name="category_id[]" class="form-control" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= htmlspecialchars($category['id']); ?>">
                            <?= htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td><input type="text" class="form-control" name="product_name[]" required></td>
            <td><button type="button" class="removeRow">Remove</button></td>
        `;
        tableBody.appendChild(newRow);
        newRow.querySelector('.removeRow').addEventListener('click', function() {
            newRow.remove();
        });
        rowIndex++;
    });
</script>

<?php require_once './views/layouts/footer.php'; ?>
