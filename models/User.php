<?php

class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function findByEmail($email) {
        $user = $this->db->fetch(
            "SELECT * FROM usuarios WHERE email = ?",
            [$email]
        );
        return $user;
    }

    public function create($nome, $email, $senha, $role = 'user') {
        $hashedPassword = password_hash($senha, PASSWORD_BCRYPT);

        $result = $this->db->execute(
            "INSERT INTO usuarios (nome, email, senha, role) VALUES (?, ?, ?, ?)",
            [$nome, $email, $hashedPassword, $role]
        );

        return $result > 0;
    }

    public function verifyPassword($password, $hashedPassword) {
        return password_verify($password, $hashedPassword);
    }

    public function getById($id) {
        return $this->db->fetch(
            "SELECT id, nome, email, role FROM usuarios WHERE id = ?",
            [$id]
        );
    }
}
