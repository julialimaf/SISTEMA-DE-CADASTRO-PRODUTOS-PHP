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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tabela = $_POST['tabela'];
    $cor_modelo = $_POST['cor_modelo'];
    $quantidade = $_POST['quantidade'];
    $preco_modelo = $_POST['preco_modelo'];

    if (in_array($tabela, $tabelas)) {
        $sql = "INSERT INTO $tabela (cor_modelo, quantidade, preco_modelo) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $cor_modelo, $quantidade, $preco_modelo);
        $stmt->execute();
        $stmt->close();
        header("Location: index.php?tabela=" . $tabela);
        exit();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Inserir Modelo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="container mt-5">
    <h1>Inserir Informações na tabela</h1>
    <form method="POST" action="">
        <label for="tabela" class="form-label">Selecione a Tabela</label>
        <select class="form-control" id="tabela" name="tabela" required>
            <option value="">Selecione</option>
            <?php foreach ($tabelas as $tab): ?>
                <option value="<?php echo $tab; ?>"><?php echo ucfirst($tab); ?></option>
            <?php endforeach; ?>
        </select>

        <label for="cor_modelo" class="form-label mt-3">Cor</label>
        <input type="text" class="form-control" id="cor_modelo" name="cor_modelo" required>

        <label for="quantidade" class="form-label mt-3">Quantidade</label>
        <input type="number" class="form-control" id="quantidade" name="quantidade" required>

        <label for="preco_modelo" class="form-label mt-3">Preço</label>
        <input type="number" class="form-control" id="preco_modelo" name="preco_modelo" required>

        <button type="submit" class="btn btn-success mt-4">Inserir</button>
        <a href="index.php" class="btn btn-secondary mt-4">Voltar</a>
    </form>
</div>
</body>
</html>
