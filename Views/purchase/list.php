<?php require_once './views/layouts/header.php'; ?>
<?php require_once './views/layouts/side.php'; ?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <nav class="navbar">
        <div class="search-container" style="background-color: #fff;">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search...">
        </div>
        <div class="icons">
            <select id="lang" style="margin-right: 10px; padding: 5px;">
                <option value="en">English</option>
                <option value="km">Khmer</option>
            </select>
            <i class="fas fa-globe icon-btn"></i>
            <div class="icon-btn" id="notification-icon">
                <i class="fas fa-bell"></i>
                <span class="notification-badge" id="notification-count">8</span>
            </div>
        </div>
        <div class="profile">
            <img src="../../views/assets/images/image.png" alt="User">
            <div class="profile-info">
                <span id="profile-name" data-translate>Eng Ly</span>
                <span class="store-name" id="store-name" data-translate>Owner Store</span>
            </div>
        </div>
    </nav>

    <div class="container table-inventory">
        <div class="orders">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 style="font-weight: bold;" class="purchase-head" data-translate>Purchasing Orders</h2>
                <div>
                    <a href="/purchase/create" class="btn-new-product">
                        <i class="bi-plus-lg"></i> <span data-translate>+ Add Products</span>
                    </a>
                </div>
            </div>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="message"><?= $_SESSION['message']; ?></div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <table class="table" border="1">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th data-translate>Image</th>
                        <th data-translate>Product Name</th>
                        <th data-translate>Category Name</th>
                        <th data-translate>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($purchases)): ?>
                        <?php foreach ($purchases as $purchase): ?>
                            <tr>
                                <td><input type="checkbox" class="delete-checkbox" value="<?= $purchase['id']; ?>"></td>
                                <td>
                                    <?php if (!empty($purchase['image_path'])): ?>
                                        <img src="<?= htmlspecialchars($purchase['image_path']); ?>" width="50">
                                    <?php else: ?>
                                        No Image
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($purchase['product_name']); ?></td>
                                <td><?= htmlspecialchars($purchase['category_name']); ?></td>
                                <td>
                                    <a href="/purchase/edit/<?= $purchase['id']; ?>" style="text-decoration: none; color: #007bff;">Edit</a> |
                                    <a href="/purchase/delete/<?= $purchase['id']; ?>" style="text-decoration: none; color: #dc3545;" class="delete-btn" data-id="<?= $purchase['id']; ?>" data-name="<?= htmlspecialchars($purchase['product_name']); ?>">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" data-translate>No purchases found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Delete selected button -->
            <div>
                <button id="deleteSelectedBtn" class="btn btn-danger" style="display:none;">Delete Selected</button>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Battambang&display=swap');
    .khmer-font { font-family: "Battambang", sans-serif; }

    /* Center all content in the table cell */
    td {
        padding: 6px 10px; /* Ensure there's space */
        text-align: center; /* Center content horizontally */
        vertical-align: middle; /* Center content vertically */
    }

    /* For images to be centered and have a consistent size */
    td img {
        max-width: 50px;
        height: auto;
        display: block;
        margin: 0 auto; /* Center the image */
    }

    /* For checkboxes to be centered */
    td input[type="checkbox"] {
        margin: 0 auto;
        display: block; /* Center the checkbox */
    }

    /* Center the content inside profile section */
    .profile {
        display: flex;
        align-items: center;
        justify-content: flex-end; /* Align items to the right */
    }

    /* Center the text in the profile info section */
    .profile-info {
        margin-left: 10px;
    }
</style>


<script>
    // Translation functionality
    const translatableElements = document.querySelectorAll('[data-translate]');
    const originalTexts = {};
    translatableElements.forEach(el => originalTexts[el.textContent.trim()] = el);

    async function translateText(text, targetLang) {
        if (targetLang === 'en') return text;
        const url = `https://api.mymemory.translated.net/get?q=${encodeURIComponent(text)}&langpair=en|${targetLang}`;
        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error(`API status ${response.status}`);
            const data = await response.json();
            let translated = data.responseData.translatedText || text;
            if (targetLang === 'km') translated = translated.replace(/\s+/g, '');
            return translated;
        } catch (error) {
            console.error('Translation failed:', error);
            return text;
        }
    }

    async function updateLanguage(lang) {
        for (const text in originalTexts) {
            const translated = await translateText(text, lang);
            originalTexts[text].textContent = translated;
            if (lang === 'km') originalTexts[text].classList.add('khmer-font');
            else originalTexts[text].classList.remove('khmer-font');
        }
        document.documentElement.lang = lang;
    }

    window.addEventListener('load', async () => {
        const savedLang = localStorage.getItem('language') || 'en';
        document.getElementById('lang').value = savedLang;
        await updateLanguage(savedLang);
    });

    document.getElementById('lang').addEventListener('change', async (e) => {
        const lang = e.target.value;
        localStorage.setItem('language', lang);
        await updateLanguage(lang);
    });

    // Delete confirmation for individual delete button
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.dataset.id;
            const name = this.dataset.name;
            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete "${name}"!`,
                icon: 'warning',
                position: 'top',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/purchase/delete/${id}`;
                }
            });
        });
    });

    // Select all checkboxes functionality
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.delete-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        toggleDeleteButton();
    });

    // Toggle delete button visibility
    document.querySelectorAll('.delete-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', toggleDeleteButton);
    });

    function toggleDeleteButton() {
        const selectedCheckboxes = document.querySelectorAll('.delete-checkbox:checked');
        const deleteButton = document.getElementById('deleteSelectedBtn');
        if (selectedCheckboxes.length > 0) {
            deleteButton.style.display = 'inline-block';
        } else {
            deleteButton.style.display = 'none';
        }
    }

    // Handle delete selected action
    document.getElementById('deleteSelectedBtn').addEventListener('click', async () => {
        const selectedCheckboxes = document.querySelectorAll('.delete-checkbox:checked');
        const idsToDelete = [];
        selectedCheckboxes.forEach(checkbox => {
            idsToDelete.push(checkbox.value);
        });

        if (idsToDelete.length > 0) {
            const confirmed = await Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete ${idsToDelete.length} product(s)!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete them!',
                cancelButtonText: 'No, cancel'
            });

            if (confirmed.isConfirmed) {
                const formData = new FormData();
                formData.append('ids', JSON.stringify(idsToDelete));

                // Send delete request to server
                const response = await fetch('/purchase/delete-selected', {
                    method: 'POST',
                    body: formData
                });

                if (response.ok) {
                    // Reload the page to reflect changes
                    location.reload();
                } else {
                    Swal.fire('Error', 'Failed to delete selected products.', 'error');
                }
            }
        }
    });
</script>

<?php require_once './views/layouts/footer.php'; ?>