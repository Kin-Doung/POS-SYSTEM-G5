

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 400px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2,
        h3 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            font-weight: bold;
            margin-top: 10px;
        }

        input[type="text"],
        input[type="email"],
        input[type="file"],
        select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            background: #4CAF50;
            color: white;
            padding: 10px;
            margin-top: 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #45a049;
        }

        .profile-img {
            display: block;
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            margin: 10px auto;
        }

        .alert {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Settings</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert">
                <?= $_SESSION['success'];
                unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <form action="setting.php" method="POST" enctype="multipart/form-data">
            <label>Full Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']); ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>

            <label>Profile Picture:</label>
            <input type="file" name="profilePic">
            <input type="hidden" name="existingPic" value="<?= $user['profile_pic']; ?>">
            <?php if (!empty($user['profile_pic'])): ?>
                <img src="<?= $user['profile_pic']; ?>" class="profile-img">
            <?php endif; ?>

            <button type="submit">Save Changes</button>
        </form>

        <h3>Change Language</h3>
        <select id="languageSelect">
            <option value="en">English</option>
            <option value="kh">Khmer</option>
        </select>
    </div>

    <script>
        document.getElementById("languageSelect").addEventListener("change", function() {
            let selectedLang = this.value;
            document.cookie = "language=" + selectedLang;
            alert("Language changed to " + selectedLang);
            location.reload();
        });
    </script>

</body>

</html>