<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "loja";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$id_gastos = isset($_GET['id_gastos']) ? intval($_GET['id_gastos']) : 0;

if ($id_gastos <= 0) {
    die("❌ ID inválido: $id_gastos");
}

$sql = "DELETE FROM gastos_mensais WHERE id_gastos = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Erro ao preparar SQL: " . $conn->error);
}

$stmt->bind_param("i", $id_gastos);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: gasto_final.php?ver=1");
    exit();
} else {
    echo "❌ Erro ao remover: " . $stmt->error;
    echo "<br><a href='gasto_final.php?ver=1'>Voltar</a>";
}

$stmt->close();
$conn->close();
?>