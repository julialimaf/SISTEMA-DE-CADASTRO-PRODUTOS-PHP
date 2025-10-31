<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "loja";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$tabelas = ["gastos_mensais"];
$tabela = isset($_GET['tabela']) ? $_GET['tabela'] : '';
$id_modelo = isset($_GET['id_gasots']) ? intval($_GET['id_gastos']) : 0;

if (!in_array($tabela, $tabelas) || $id_gasto <= 0) {
    die("Parâmetros inválidos.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mes_gasto = $_POST['mes_gasto'];
    $gasto_confeccao = $_POST['gasto_confeccao']; 
    $gasto_mao_de_obra_corte= $_POST['gasto_mao_de_obra_corte'];
    $gasto_material = $_POST['gasto_material'];
    

    $sql = "UPDATE $tabela SET mes_gasto=?,gasto_confeccao=?,gasto_mao_de_obra_corte=?,gasto_material=? WHERE id_gastos=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siii", $mes_gasto,$gasto_confeccao,$gasto_mao_de_obra_corte,$gasto_material, $id_gastos);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php?tabela=$tabela");
    exit();
}

$sql = "SELECT * FROM $tabela WHERE id_gastos = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_gastos);
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
    <h1>Editar gasto</h1>
    <h5>Aqui voce esta editanto as informações do modelo que escolheu dentro do banco de dados :D<h5>
    <form method="POST" action="">
        <label class="form-label">Mês do gasto</label>
        <input type="text" class="form-control" name="mes_gasto" value="<?php echo htmlspecialchars($modelo['mes_gasto']); ?>" required>
        
        <label class="form-label mt-3">Gasto com a confecção</label>
        <input type="number" class="form-control" name="gasto_confeccao" value="<?php echo htmlspecialchars($modelo['gasto_confeccao']); ?>" required>

        <label class="form-label mt-3">Gasto com mão de obra (corte)</label>
        <input type="number" class="form-control" name="gasto_mao_de_obra_corte" value="<?php echo htmlspecialchars($modelo['gasto_mao_de_obra_corte']); ?>" required>

        
        <label class="form-label mt-3">Gasto com material</label>
        <input type="number" class="form-control" name="gasto_material" value="<?php echo htmlspecialchars($modelo['gasto_material']); ?>" required>






        <button type="submit" class="btn btn-primary mt-4">Salvar Alterações</button>
        <a href="index.php?tabela=<?php echo $tabela; ?>" class="btn btn-secondary mt-4">Cancelar</a>
    </form>
</div>
</body>
</html>
