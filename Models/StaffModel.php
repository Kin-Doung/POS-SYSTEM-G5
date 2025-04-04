<?php
require_once './Databases/database.php';
class UserModel{
    private $pdo;
    function __construct(){
        $this->pdo = new Database();
    }
    function getUsers(){
        $user = $this->pdo->query("SELECT * FROM users");
        return $user->fetchAll();
    }
    function createUser($data){
        $stmt = $this->pdo->query("INSERT INTO users (name, image) VALUES (:name, :image)", [
            'name' => $data['name'],
            'image' => $data['image']
        ]); 
    }
    function getUser($id){
        $stmt = $this->pdo->query("SELECT * FROM users WHERE id = :id",['id'=> $id ]);
        $user = $stmt->fetch();
        return $user;
    }
    function updateUser($id, $data){
        $stmt = $this->pdo->query("UPDATE users SET name = :name, image= :image WHERE id = :id", [
            'name'=> $data['name'],
            'image' => $data['image'],
            'id' => $id
        ]);
    }
    function deleteUser($id){
        $stmt = $this->pdo->query("DELETE FROM users WHERE id = :id", ['id'=> $id]);
    }
}
