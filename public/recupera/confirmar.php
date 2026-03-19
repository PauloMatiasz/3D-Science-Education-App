<?php
include "../Login/conexao.php";

$erro = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["codigo"]) && isset($_POST["email"])) {
    $email = $_POST["email"];
    $codigo = $_POST["codigo"];

    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ? AND codigo_recuperacao = ? AND codigo_expira > NOW()");
    if (!$stmt) { echo "Erro na consulta: " . $conn->error; exit; }
    $stmt->bind_param("ss", $email, $codigo);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res && $res->num_rows > 0) {
        header("Location: redefinir.php?email=" . urlencode($email));
        exit;
    } else {
        $erro = "Código inválido ou expirado.";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Confirmar Código</title>
<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #f2f2f2;
}

.container {
    display: flex;
    margin-left: 25%;
    min-height: 86vh;
    justify-content: flex-start; /* Mantém alinhamento à esquerda */
    align-items: center;
    padding: 20px 20px 20px 20px; /* Reduzido o padding esquerdo para 20px, aproximando mais da borda esquerda */
    gap: 10px;
    max-width: 100%; /* Garante que não ultrapasse a largura da tela */
}

.logo {
    flex: 1;
    display: flex;
    justify-content: flex-start; /* Alinha a imagem mais à esquerda dentro do seu container */
    align-items: center;
    min-width: 200px; /* Largura mínima para a logo não colapsar */
}

.logo img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
}

.card {
    flex: 1;
    background-color: #fff;
    padding: 30px 25px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    text-align: center;
    max-width: 400px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    margin-left: 10%; 
}

.card h2 {
    color: #4CAF50;
    margin-bottom: 20px;
    font-size: 1.6rem;
}

.input-group {
    margin-bottom: 10px; /* reduziu o espaço entre os campos */
    text-align: left;
}

.input-group label {
    display: block;
    margin-bottom: 4px; /* label mais próxima do input */
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
    margin-bottom: 10px; /* reduziu espaço acima do primeiro input */
    font-size: 0.95rem;
}

footer {
    font-size: 0.85rem;
    color: #333;
    text-align: center;
    padding: 20px;
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
        justify-content: flex-start; /* Mantém alinhamento à esquerda no mobile */
        align-items: flex-start; /* Alinha itens à esquerda no mobile */
        padding: 20px 20px 20px 20px; /* Padding uniforme no mobile, aproximando da esquerda */
    }
    .card, .logo {
        max-width: 100%;
        margin-left: 0; /* Remove margem extra no mobile */
    }
    .logo {
        justify-content: flex-start; /* Alinha logo à esquerda no mobile */
        width: 100%;
    }
    .logo img {
        width: 60%;
        max-width: 300px; /* Limita a largura da imagem no mobile */
    }
}
</style>
</head>
<body>

<div class="container">
    <div class="logo">
        <img src="../Login/SC.png" alt="Logo Sci3D">
        <div class="card">
        <h2>Confirmar Código</h2>

        <?php if (!empty($erro)): ?>
            <div class="notificacao-erro"><?php echo $erro; ?></div>
        <?php endif; ?>

        <form action="confirmar.php" method="post">
            <div class="input-group">
                <label for="codigo">Digite o código enviado ao seu e-mail:</label>
                <input type="text" id="codigo" name="codigo" required>
            </div>
            <input type="hidden" name="email" value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
            <button type="submit">Confirmar</button>
        </form>
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