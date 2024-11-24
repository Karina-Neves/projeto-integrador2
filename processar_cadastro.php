<?php
// Configurações do banco de dados
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "loja_cupcakes";

// Conexão com o banco de dados
$conn = new mysqli($servidor, $usuario, $senha, $banco);

// Verifica se a conexão foi bem-sucedida
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Obtendo os dados do formulário
$usuario = $_POST['usuario'];
$email = $_POST['email'];
$cpf = $_POST['cpf'];
$senha = $_POST['senha'];
$confirmar_senha = $_POST['confirmar_senha'];

// Validação de e-mail
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Erro: E-mail inválido. Certifique-se de que está no formato exemplo@gmail.com.");
}

// Validação de CPF
if (!preg_match("/^\d{3}\.\d{3}\.\d{3}-\d{2}$/", $cpf)) {
    die("Erro: CPF inválido. Certifique-se de que está no formato 111.222.333-44.");
}

// Validação de senha
if ($senha !== $confirmar_senha) {
    die("Erro: As senhas não coincidem!");
}

// Hash da senha para maior segurança
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

// Inserindo os dados no banco de dados
$sql = "INSERT INTO usuarios (nome, email, cpf, senha) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("ssss", $usuario, $email, $cpf, $senha_hash);

    if ($stmt->execute()) {
        echo "<!DOCTYPE html>
            <html lang='pt-BR'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Cadastro Realizado</title>
                <link rel='stylesheet' href='style4.css'>
            </head>
            <body>
                <!-- Cabeçalho -->
                <header>
                    <div class='logo'>Cake Mania</div>
                </header>

                <main class='thank-you'>
                    <h1>Cadastro realizado com sucesso!</h1>
                    <a href='login.html'>Faça login</a>
                </main>

                <!-- Rodapé -->
                <footer>
                    <p>Informações sobre a empresa:<br>Cake Mania é uma empresa fictícia criada por Karina Neves como requisito para o projeto transdisciplinar em Ciência da Computação.</p>
                </footer>
            </body>
            </html>";
    } else {
        echo "Erro ao realizar o cadastro: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Erro na preparação da query: " . $conn->error;
}

// Fechando a conexão
$conn->close();
?>
