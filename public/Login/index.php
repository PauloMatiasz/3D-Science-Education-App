<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login e Cadastro - Sci3D</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="image-container">
            <img src="SC.png" alt="Logo Sci3D">
        </div>

        <!-- Formulário de Login -->
        <div class="form-container" id="login-form">
            <h2>Login</h2>
            <form action="login.php" method="POST">
                <div class="input-group">
                    <label for="login-email">E-mail</label>
                    <input type="email" id="login-email" name="email" placeholder="Digite seu e-mail" required>
                </div>
                <div class="input-group">
                    <label for="login-password">Senha</label>
                    <input type="password" id="login-password" name="senha" placeholder="Digite sua senha" required>
                </div>
                <button type="submit">Entrar</button>
            </form>
            <p>Não tem uma conta? <span onclick="toggleForm()">Cadastre-se</span></p>
            <p>Visite nosso conteúdo <span onclick="window.location.href='../visitmode/homevisit.php'">Visitante</span></p>
            <p>Esqueceu sua senha? <span onclick="window.location.href='../recupera/recuperar.php'">Recuperar Senha</span></p>
        </div>

        <!-- Formulário de Cadastro -->
        <div class="form-container" id="register-form" style="display: none;">
            <h2>Cadastrar</h2>
            <form action="cadastro.php" method="POST">
                <div class="input-group">
                    <label for="register-name">Nome</label>
                    <input type="text" id="register-name" name="nome" placeholder="Digite seu nome completo" required>
                </div>
                <div class="input-group">
                    <label for="register-email">E-mail</label>
                    <input type="email" id="register-email" name="email" placeholder="Digite seu e-mail" required>
                </div>
                <div class="input-group">
                    <label for="register-password">Senha</label>
                    <input type="password" id="register-password" name="senha" placeholder="Digite sua senha" required>
                </div>
                <button type="submit">Cadastrar</button>
            </form>
            <p>Já tem uma conta? <span onclick="toggleForm()">Faça login</span></p>
            <p>Visite nosso conteúdo: <span onclick="window.location.href='../visitmode/homevisit.php'">Visitante</span></p>
                        
        </div>
    </div>

    <footer>
        <p>&copy; 2025 Sci3D - Todos os direitos reservados. | Contato: 
            <a href="mailto:sci3dcorporation@gmail.com">sci3dcorporation@gmail.com</a>
        </p>
    </footer>
    <script>
        function toggleForm() {
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');

            if (loginForm.style.display === 'none') {
                loginForm.style.display = 'flex';
                registerForm.style.display = 'none';
            } else {
                loginForm.style.display = 'none';
                registerForm.style.display = 'flex';
            }
        }
    </script>
</body>
</html>
