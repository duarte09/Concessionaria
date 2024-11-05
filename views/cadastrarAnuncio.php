<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Anúncio</title>
</head>
<body>
    <form id="formCadastroAnuncio" enctype="multipart/form-data">
        <input type="text" name="titulo" placeholder="Título" required>
        <textarea name="descricao" placeholder="Descrição" required></textarea>
        <input type="number" name="preco" placeholder="Preço" required>
        <input type="file" name="fotos[]" multiple required>
        <button type="submit">Cadastrar Anúncio</button>
    </form>

    <div id="message"></div>

    <script>
        document.getElementById("formCadastroAnuncio").onsubmit = function(event) {
            event.preventDefault();
            var formData = new FormData(this);

            fetch("controllers/AnuncioController.php?action=cadastrarAnuncio", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById("message").innerText = data.message;
            })
            .catch(error => console.error("Erro:", error));
        };
    </script>
</body>
</html>
