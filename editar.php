<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "loja";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$tabelas = ["modelo1", "modelo2", "modelo3", "modelo4"];
$tabela = isset($_GET['tabela']) ? $_GET['tabela'] : '';
$id_modelo = isset($_GET['id_modelo']) ? intval($_GET['id_modelo']) : 0;

if (!in_array($tabela, $tabelas) || $id_modelo <= 0) {
    die("Parâmetros inválidos.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cor_modelo = $_POST['cor_modelo'];
    $quantidade = $_POST['quantidade'];
    $preco_modelo = $_POST['preco_modelo'];

    $sql = "UPDATE $tabela SET cor_modelo=?, quantidade=?, preco_modelo=? WHERE id_modelo=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siii", $cor_modelo, $quantidade, $preco_modelo, $id_modelo);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php?tabela=$tabela");
    exit();
}

$sql = "SELECT * FROM $tabela WHERE id_modelo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_modelo);
$stmt->execute();
$result = $stmt->get_result();
$modelo = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Editar Modelo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
</head>
<body>
<div class="container mt-5">
    <h1>Editar Modelo</h1>
    <h4>Aqui voce esta editanto as informações do modelo que escolheu dentro do banco de dados :D<h4>
    <form method="POST" action="">
        <label class="form-label">Cor</label>
        <input type="text" class="form-control" name="cor_modelo" value="<?php echo htmlspecialchars($modelo['cor_modelo']); ?>" required>

        <label class="form-label mt-3">Quantidade</label>
        <input type="number" class="form-control" name="quantidade" value="<?php echo htmlspecialchars($modelo['quantidade']); ?>" required>

        <label class="form-label mt-3">Preço</label>
        <input type="number" class="form-control" name="preco_modelo" value="<?php echo htmlspecialchars($modelo['preco_modelo']); ?>" required>

        <button type="submit" class="btn btn-primary mt-4">Salvar Alterações</button>
        <a href="index.php?tabela=<?php echo $tabela; ?>" class="btn btn-secondary mt-4">Cancelar</a>
    </form>
</div>
</body>
</html>
