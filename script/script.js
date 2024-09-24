function redirectToRestricted() {
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;

    if (email === "" || password === "") {
        alert("Por favor, preencha todos os campos.");
        return false;
    }

    if (email === "admin@mail.com" && password === "1234") {
        window.location.href = "interna.html";
    } else {
        alert("Credenciais inválidas. Tente novamente.");
    }

    return false;
}