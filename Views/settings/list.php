<div class="container">
    <h2>Account Setting</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <label>Username</label>
        <input type="text" name="username" value="<?php echo $admin['username']; ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?php echo $admin['email']; ?>" required>

        <label>Password</label>
        <input type="password" name="password" value="<?php echo $admin['password']; ?>" required>

        <label>Store Name</label>
        <input type="text" name="store_name" value="<?php echo $admin['store_name']; ?>">

        <label>Store Logo</label>
        <input type="file" name="store_logo">

        <?php if ($admin['store_logo']) { ?>
            <img src="<?php echo $admin['store_logo']; ?>" width="100">
        <?php } ?>

        <label>Language</label>
        <select name="language">
            <option value="en" <?php if ($admin['language'] == 'en') echo 'selected'; ?>>English</option>
            <option value="km" <?php if ($admin['language'] == 'km') echo 'selected'; ?>>Khmer</option>
        </select>

        <button type="submit">Save</button>
    </form>
</div>