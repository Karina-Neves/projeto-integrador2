<?php
// Conexão com o banco de dados
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'loja_cupcakes';

$conn = new mysqli($host, $user, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Iniciar sessão
session_start();

$erro = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Receber os dados do formulário
    $usuario = $_POST['username'];
    $senha = $_POST['password'];

    // Verificar se o usuário existe no banco de dados
    $sql = "SELECT * FROM usuarios WHERE nome = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verificar a senha
        if (password_verify($senha, $row['senha'])) {
            // Autenticação bem-sucedida
            $_SESSION['nome'] = $row['nome'];
            $_SESSION['id_usuario'] = $row['id'];
            header("Location: checkout.html"); // Redireciona para a página principal
            exit;
        } else {
            $erro = "Senha incorreta.";
        }
    } else {
        $erro = "Usuário não encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">Cake Mania</div>
    </header>

    <section class="login-section">
        <h2>Login</h2>
        <?php if ($erro): ?>
            <p style="color: red;"><?php echo $erro; ?></p>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <label for="usuario">Usuário:</label>
            <input type="text" id="usuario" name="usuario" required>
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>
            <button type="submit">Login</button>
        </form>
        <p>Não tem uma conta? <a href="cadastro.html">Cadastre-se aqui</a></p>
    </section>

    <footer>
        <p>© 2024 Cake Mania. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
