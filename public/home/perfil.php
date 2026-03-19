<?php
session_start(); // Iniciar sessão para acessar $_SESSION

// Conexão com o banco de dados usando mysqli
require_once __DIR__ . '/../../config/conexao.php';

// Verifica conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    die("Usuário não está logado.");
}

$userId = $_SESSION['user_id'];

// Buscar dados atuais do usuário
$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("Usuário não encontrado.");
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (strlen($nome) < 3) {
        $errors[] = "O nome deve ter pelo menos 3 caracteres.";
    }

    $photoData = null;
    $photoType = null;

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if ($_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            if (in_array($_FILES['photo']['type'], $allowedTypes)) {
                $photoData = file_get_contents($_FILES['photo']['tmp_name']);
                $photoType = $_FILES['photo']['type'];
            } else {
                $errors[] = "Formato de foto inválido. Use JPG, PNG ou GIF.";
            }
        } else {
            $errors[] = "Erro no upload da foto.";
        }
    }

    if (empty($errors)) {
        if ($photoData !== null) {
            // Atualiza com foto
            $sql = "UPDATE usuarios SET nome = ?, description = ?, photo = ?, photo_type = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssbi", $nome, $description, $photoData, $photoType, $userId);
            $stmt->send_long_data(2, $photoData); // Para LONGBLOB
        } else {
            // Atualiza sem foto
            $sql = "UPDATE usuarios SET nome = ?, description = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $nome, $description, $userId);
        }
        if ($stmt->execute()) {
            $success = true;
            // Recarregar dados atualizados
            $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
        } else {
            $errors[] = "Erro ao atualizar o perfil.";
        }
        $stmt->close();
    }
}

// Função para exibir a imagem do banco em base64
function getImageSrc($user) {
    if ($user['photo']) {
        $base64 = base64_encode($user['photo']);
        return "data:{$user['photo_type']};base64,{$base64}";
    } else {
        return "https://placehold.co/120x120/cccccc/white?text=Foto+de+Perfil";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Editar Perfil</title>
    <style>
        .menu {
            background-color: #ffffff;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            max-width: 900px;
            margin: 20px auto;
            font-family: Arial, sans-serif;
        }
        .menu a.voltar {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 16px;
            border: 2px solid #4CAF50;
            transition: background-color 0.3s, color 0.3s;
        }
        .menu a.voltar:hover {
            background-color: #45a049;
            color: white;
        }

        .content-wrapper {
            background-color: rgb(247, 247, 247);
            padding: 40px 20px;
            border-radius: 5px;
            max-width: 900px;
            margin: 0 auto 40px auto;
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        form {
            background: #fff;
            padding: 20px;
            border-radius: 6px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 12px 14px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1em;
            resize: vertical;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        input[type="text"]:focus, textarea:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 6px rgba(76, 175, 80, 0.3);
            outline: none;
        }

        /* Estilo do input file */
        input[type="file"] {
            display: none;
        }
        .file-label {
            display: inline-block;
            padding: 10px 16px;
            background-color: #4CAF50;
            color: #fff;
            border-radius: 6px;
            font-size: 0.95em;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.2s;
            text-align: center;
        }
        .file-label:hover {
            background-color: #45a049;
            transform: translateY(-2px);
        }
        .file-name {
            display: block;
            margin-top: 8px;
            font-size: 0.9em;
            color: #555;
            font-family: Arial, sans-serif;
        }

        button {
            margin-top: 20px;
            background: #4CAF50;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 1em;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.2s;
        }
        button:hover {
            background: #45a049;
            transform: translateY(-2px);
        }

        .profile-photo {
            display: block;
            margin: 0 auto 15px auto;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #4CAF50;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
        }
        .messages {
            margin-bottom: 15px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        .error {
            color: #b00020;
            background: #fdd;
            padding: 10px;
            border-radius: 4px;
            font-family: Arial, sans-serif;
        }
        .success {
            color: #155724;
            background: #d4edda;
            padding: 10px;
            border-radius: 4px;
            font-family: Arial, sans-serif;
        }
        @media (max-width: 480px) {
            form {
                padding: 15px;
            }
            .profile-photo {
                width: 100px;
                height: 100px;
            }
        }
    </style>
</head>
<body>

<div class="menu">
    <a href="home.php" class="voltar" title="Voltar para Home">&larr; Voltar</a>
</div>

<div class="content-wrapper">
    <h1>Editar Perfil</h1>

    <div class="messages">
        <?php if ($errors): ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $e): ?>
                        <li><?php echo htmlspecialchars($e); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php elseif ($success): ?>
            <div class="success">Perfil atualizado com sucesso!</div>
        <?php endif; ?>
    </div>

    <form method="post" enctype="multipart/form-data">
        <img src="<?php echo getImageSrc($user); ?>" alt="Foto de perfil" class="profile-photo" />

        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($user['nome']); ?>" required minlength="3" />

        <label for="photo">Foto de perfil (JPG, PNG, GIF):</label>
        <label for="photo" class="file-label">Escolher arquivo</label>
        <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/gif" />
        <span class="file-name" id="file-name">Nenhum arquivo selecionado</span>

        <label for="description">Descrição:</label>
        <textarea id="description" name="description" rows="4"><?php echo htmlspecialchars($user['description']); ?></textarea>

        <button type="submit">Salvar</button>
    </form>
</div>

<script>
  const inputFile = document.getElementById("photo");
  const fileName = document.getElementById("file-name");

  inputFile.addEventListener("change", function() {
    fileName.textContent = this.files.length > 0 ? this.files[0].name : "Nenhum arquivo selecionado";
  });
</script>

</body>
</html>

<?php
$conn->close();
?>
