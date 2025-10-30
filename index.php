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


$tabela_selecionada = isset($_GET['tabela']) ? $_GET['tabela'] : '';


if (!in_array($tabela_selecionada, $tabelas)) {
    $tabela_selecionada = '';
}

$result_dados = null;

if ($tabela_selecionada) {
    $sql = "SELECT id_modelo, cor_modelo, quantidade, preco_modelo FROM $tabela_selecionada";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result_dados = $stmt->get_result();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Modelos Cadastrados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="container mt-5">
    <h1>Modelos Cadastrados</h1>

    <form method="GET" action="">
        <label for="tabela" class="form-label">Escolha o tipo de modelo</label>
        <select class="form-control" id="tabela" name="tabela" required>
            <option value="">Selecione uma tabela</option>
            <?php
            foreach ($tabelas as $tab) {
                $selected = ($tabela_selecionada == $tab) ? 'selected' : '';
                echo "<option value='$tab' $selected>" . ucfirst($tab) . "</option>";
            }
            ?>
        </select>
        <button type="submit" class="btn btn-primary mt-3">Carregar Dados</button>
        <a href="inserir.php" class="btn btn-success mt-3">Inserir Novo Modelo</a>
    </form>

    <?php if ($tabela_selecionada && $result_dados): ?>
        <h3 class="mt-4">Tabela: <?php echo htmlspecialchars($tabela_selecionada); ?></h3>

        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cor</th>
                    <th>Quantidade</th>
                    <th>Preço</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_dados->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id_modelo']); ?></td>
                        <td><?php echo htmlspecialchars($row['cor_modelo']); ?></td>
                        <td><?php echo htmlspecialchars($row['quantidade']); ?></td>
                        <td><?php echo "R$ " . number_format($row['preco_modelo'], 2, ',', '.'); ?></td>
                        <td>
                            <a href="editar.php?tabela=<?php echo $tabela_selecionada; ?>&id_modelo=<?php echo $row['id_modelo']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="remover.php?tabela=<?php echo urlencode($tabela_selecionada); ?>&id_modelo=<?php echo $row['id_modelo']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja remover este modelo?');">Remover</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

       
    <?php elseif ($tabela_selecionada): ?>
        <p class="mt-4 text-danger">Nenhum registro encontrado nesta tabela.</p>
    <?php endif; ?>
</div>
</body>
</html>
