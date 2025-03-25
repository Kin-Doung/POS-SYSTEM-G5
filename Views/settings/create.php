<div class="container">
    <form action="/settings/store" method="POST" enctype="multipart/form-data">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="text" name="store_name" placeholder="Store Name" required>
        <input type="file" name="store_logo" accept="image/*">
        <select name="language" required>
            <option value="en">English</option>
            <option value="km">Khmer</option>
        </select>
        <button type="submit">Create Admin</button>
    </form>
</div>

