<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Página de Feedback</title>
  <link rel="stylesheet" href="contatos.css">
</head>
<body>
  <main class="container">
    <section class="titulo">
      <h1>Queremos ouvir você</h1>
      <p class="lead">Seu feedback ajuda a melhorar nossos serviços.</p>
    </section>

    <form class="card" id="feedbackForm" action="#" method="post">
      <label for="nome">Nome</label>
      <input id="nome" name="nome" type="text" placeholder="Seu nome" />

      <label for="email">Email</label>
      <input id="email" name="email" type="email" placeholder="seu@exemplo.com" />

      <label for="mensagem">Mensagem</label>
      <textarea id="mensagem" name="mensagem" placeholder="Digite seu feedback"></textarea>

      <label for="nota">Nota</label>
      <select id="nota" name="nota">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5" selected>5</option>
      </select>

      <button class="btn" type="submit">Enviar</button>
    </form>
  </main>

  <script>
    document.getElementById('feedbackForm').addEventListener('submit', function(e){
      e.preventDefault();
      alert('Obrigado! Seu feedback foi registrado (simulação).');
      this.reset();
    });
  </script>
    <footer class="footer">
    <p> sci3dcorporation@gmail.com| ☎ (62) 996670727</p>
    <p>&copy; 2025 TCC FINAL — Todos os direitos reservados.</p>
  </footer>
</body>
</html>
