<?php
require_once './Databases/database.php';

// class UserModel
// {
//     private $pdo;
//     function __construct()
//     {
//         $this->pdo = new Database();
//     }
//     function getUsers()
//     {
//         $users = $this->pdo->query("SELECT * FROM users ORDER BY id DESC");
//         return $users->fetchAll();
//     }

//     function createUser($data)
//     {
//         $this->pdo->query("INSERT INTO users ( image ,name) VALUES ( :image, :name)", [
//             'image' => $data['image'],
//             'name' => $data['name'],
//         ]);
//     }

//     function getUser($id)
//     {
//         $stmt = $this->pdo->query("SELECT * FROM users WHERE id = :id", ['id' => $id]);
//         $user = $stmt->fetch();
//         return $user;
//     }
//     function updateUser($id, $data)
//     {
//         $this->pdo->query("UPDATE users SET image = :image, name = :name WHERE id = :id", [
//             'image' => $data['image'],
//             'name' => $data['name'],
//             'id' => $id
//         ]);
//     }
//     function deleteUser($id)
//     {
//         $this->pdo->query("DELETE FROM users WHERE id = :id", ['id' => $id]);
//     } -->

