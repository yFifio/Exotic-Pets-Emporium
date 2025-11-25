<?php
require_once __DIR__ . '/../Models/Usuario.php';

class AuthController {
    public function register() {
     
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $senha = $_POST['senha'];

      
        if (!$nome || !$email || !$senha) {
            header('Location: /register?error=invalid_data');
            exit();
        }

        $usuarioModel = new Usuario();

       
        if ($usuarioModel->findByEmail($email)) {
         
            header('Location: /register?error=email_exists');
            exit();
        }

      
        if ($usuarioModel->create($nome, $email, $senha)) {
            header('Location: /login');
        }
    }

    public function login() {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $senha = $_POST['senha']; 

        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->findByEmail($email);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['user_role'] = $usuario['role'];
            header('Location: /');
        } else {
            header('Location: /login?error=invalid_credentials');
        }
    }

    public function logout() {
        session_destroy();
        header('Location: /');
    }

    public function showLoginForm() {
        require_once __DIR__ . '/../Views/login.php';
    }

    public function showRegisterForm() {
        require_once __DIR__ . '/../Views/register.php';
    }
}