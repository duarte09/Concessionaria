<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Anunciante</title>
</head>
<body>
    <form id="formCadastroAnunciante">
        <input type="text" name="nome" placeholder="Nome" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Cadastrar</button>
    </form>

    <div id="message"></div>

    <script>
        document.getElementById("formCadastroAnunciante").onsubmit = function(event) {
            event.preventDefault();
            var formData = new FormData(this);

            fetch("controllers/AnuncianteController.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById("message").innerText = data.message;
            })
            .catch(error => {
                console.error("Erro:", error);
            });
        };
    </script>
</body>
</html>