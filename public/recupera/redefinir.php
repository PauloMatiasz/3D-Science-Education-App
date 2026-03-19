<?php
require_once __DIR__ . '/../../config/conexao.php';

$erro = "";
$sucesso = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"] ?? $_GET["email"] ?? "";
    $senha = trim($_POST["senha"] ?? "");
    $confirma = trim($_POST["confirma"] ?? "");

    // Validação básica
    if (empty($email)) {
        $erro = "E-mail não fornecido.";
    } elseif (empty($senha) || empty($confirma)) {
        $erro = "Ambos os campos de senha são obrigatórios.";
    } elseif ($senha !== $confirma) {
        $erro = "As senhas não coincidem.";
    } elseif (strlen($senha) < 6) {
        $erro = "A senha deve ter pelo menos 6 caracteres.";
    } else {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE usuarios SET senha = ?, codigo_recuperacao = NULL, codigo_expira = NULL WHERE email = ?");
        if (!$stmt) {
            $erro = "Erro na consulta: " . $conn->error;
        } else {
            $stmt->bind_param("ss", $senha_hash, $email);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $sucesso = "Senha alterada com sucesso!";
                } else {
                    $erro = "E-mail não encontrado ou código expirado.";
                }
            } else {
                $erro = "Erro ao alterar senha: " . $stmt->error;
            }

            $stmt->close();
        }
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        .container {
            display: flex;
            min-height: 88vh;
            justify-content: flex-start; /* Alinhamento à esquerda, consistente com páginas anteriores */
            align-items: center;
            padding: 20px 20px 20px 20px; /* Padding uniforme, aproximando da esquerda */
            gap: 10px;
            max-width: 100%;
            margin-left: 25%;
        }

        .logo {
            flex: 1;
            display: flex;
            justify-content: flex-start; /* Alinha a imagem à esquerda */
            align-items: center;
            min-width: 200px; /* Largura mínima para a logo */
        }

        .logo img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .card {
            flex: 1;
            background-color: #fff;
            padding: 40px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 420px;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            margin-left:10%;
        }

        .card h2 {
            color: #4CAF50;
            margin-bottom: 25px;
            font-size: 1.6rem;
        }

        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .input-group input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1rem;
            outline: none;
            transition: border 0.2s;
            box-sizing: border-box;
        }

        .input-group input:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.3);
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }

        button:hover {
            background-color: #45a049;
        }

        .notificacao-erro {
            background-color: #ffe6e6;
            color: #cc0000;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }

        .notificacao-sucesso {
            background-color: #e6ffe6;
            color: #006600;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }

        .link-login {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
            margin-top: 10px;
            display: inline-block;
        }

        .link-login:hover {
            text-decoration: underline;
        }

        footer {
            font-size: 0.85rem;
            color: #333;
            text-align: center;
            padding: 20px;
            background-color: #f2f2f2; /* Fundo consistente */
        }

        footer a {
            color: #4CAF50;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 900px) {
            .container {
                flex-direction: column;
                justify-content: flex-start;
                align-items: flex-start;
                padding: 20px 10px;
            }
            .card, .logo {
                max-width: 100%;
                width: 100%;
            }
            .logo {
                justify-content: flex-start;
                width: 100%;
            }
            .logo img {
                width: 60%;
                max-width: 300px;
            }
            .card {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="logo">
        <img src="../Login/SC.png" alt="Logo Sci3D">
        <div class="card">
        <?php if (!empty($sucesso)): ?>
            <h2>Redefinir Senha</h2>
            <div class="notificacao-sucesso"><?php echo $sucesso; ?></div>
            <a href="../Login/index.php" class="link-login">Fazer login agora</a>
        <?php else: ?>
            <h2>Redefinir Senha</h2>

            <?php if (!empty($erro)): ?>
                <div class="notificacao-erro"><?php echo $erro; ?></div>
            <?php endif; ?>

            <form action="" method="post">
                <input type="hidden" name="email" value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
                <div class="input-group">
                    <label for="senha">Nova Senha:</label>
                    <input type="password" id="senha" name="senha" required minlength="6">
                </div>
                <div class="input-group">
                    <label for="confirma">Confirmar Senha:</label>
                    <input type="password" id="confirma" name="confirma" required minlength="6">
                </div>
                <button type="submit">Salvar Nova Senha</button>
            </form>
        <?php endif; ?>
    </div>
      </div>

    
</div>

<footer>
    <p>&copy; 2025 Sci3D - Todos os direitos reservados. | Contato: 
        <a href="mailto:sci3dcorporation@gmail.com">sci3dcorporation@gmail.com</a>
    </p>
</footer>

</body>
</html>