<?php

class AuthController {
    private $user;

    public function __construct() {
        $this->user = new User();
    }

    public function login() {
        $input = json_decode(file_get_contents('php://input'), true);

        // Validar dados
        if (!Validator::validate($input, [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ])) {
            Response::error('Validação falhou', 400, Validator::getErrors());
        }

        $email = $input['email'];
        $password = $input['password'];

        // Buscar usuário
        $userData = $this->user->findByEmail($email);

        if (!$userData) {
            Response::error('Email ou senha incorretos', 401);
        }

        // Verificar senha
        if (!$this->user->verifyPassword($password, $userData['senha'])) {
            Response::error('Email ou senha incorretos', 401);
        }

        // Gerar token JWT
        $payload = [
            'id' => $userData['id'],
            'email' => $userData['email'],
            'nome' => $userData['nome'],
            'role' => $userData['role']
        ];

        $token = JWT::encode($payload);

        // Retornar resposta
        Response::success([
            'token' => $token,
            'user' => [
                'id' => $userData['id'],
                'nome' => $userData['nome'],
                'email' => $userData['email'],
                'role' => $userData['role']
            ]
        ], 'Login realizado com sucesso', 201);
    }

    public function logout() {
        // Validar autenticação
        $user = AuthMiddleware::handle();

        Response::success(null, 'Logout realizado com sucesso', 200);
    }
}
