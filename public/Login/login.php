<?php
require_once __DIR__ . '/../../config/conexao.php';

session_start();

$msg = ""; // variável para mensagem

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario'] = $usuario['nome'];
            $_SESSION['user_id'] = $usuario['id']; // <- ID necessário para edição de perfil
            header("Location: ../home/home.php");
            exit;
        } else {
            $msg = "Senha incorreta.";
        }
    } else {
        $msg = "Usuário não encontrado.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Login - Sci3D</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background-color: #f4f4f9;
            text-align: center;
        }
        .msg {
            background-color: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
            padding: 15px;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }
        a {
            display: block;
            margin-top: 25px;
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php if (!empty($msg)): ?>
        <div class="msg">
            <?php echo htmlspecialchars($msg); ?>
        </div>
    <?php endif; ?>

    <a href="index.php">Voltar para Login/Cadastro</a>
</body>
</html>
