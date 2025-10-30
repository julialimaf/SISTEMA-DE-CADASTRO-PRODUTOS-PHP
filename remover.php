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

$tabelas = ["modelo1", "modelo2", "modelo3", "modelo4"];


$tabela = isset($_GET['tabela']) ? html_entity_decode($_GET['tabela']) : '';
$id_modelo = 0;


if (isset($_GET['id_modelo'])) {
    $id_modelo = intval($_GET['id_modelo']);
} elseif (isset($_GET['amp;id_modelo'])) {
    
    $id_modelo = intval($_GET['amp;id_modelo']);
}


if (!in_array($tabela, $tabelas) || $id_modelo <= 0) {
    die("❌ Parâmetros inválidos: tabela=$tabela, id=$id_modelo");
}

$sql = "DELETE FROM $tabela WHERE id_modelo = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Erro ao preparar SQL: " . $conn->error);
}

$stmt->bind_param("i", $id_modelo);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    
    
    header("Location: index.php?tabela=$tabela");
    exit();
} else {
    echo "❌ Erro ao remover: " . $stmt->error;
    echo "<br><a href='index.php?tabela=$tabela'>Voltar</a>";
}

$stmt->close();
$conn->close();
?>
