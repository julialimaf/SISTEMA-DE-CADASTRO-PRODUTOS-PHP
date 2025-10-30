<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "loja";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$mensagem = "";
$mostrar_tabela = isset($_GET['ver']) ? true : false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mes_gasto = isset($_POST['mes_gasto']) ? $_POST['mes_gasto'] : '';
    $gasto_confeccao = floatval($_POST['gasto_confeccao']);
    $gasto_mao_de_obra_corte = floatval($_POST['gasto_mao_de_obra_corte']);
    $gasto_material = floatval($_POST['gasto_material']);
    $gasto_total = $gasto_confeccao + $gasto_mao_de_obra_corte + $gasto_material;
    
    $sql = "INSERT INTO gastos_mensais (mes_gasto, gasto_confeccao, gasto_mao_de_obra_corte, gasto_material, gasto_total) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdddd", $mes_gasto, $gasto_confeccao, $gasto_mao_de_obra_corte, $gasto_material, $gasto_total);
    
    if ($stmt->execute()) {
        $mensagem = "✅ Dados salvos com sucesso!";
    } else {
        $mensagem = "❌ Erro ao salvar: " . $stmt->error;
    }
    $stmt->close();
}

// Buscar dados para exibir
$dados_gastos = null;
if ($mostrar_tabela) {
    $sql = "SELECT * FROM gastos_mensais ORDER BY id_gastos DESC";
    $result = $conn->query($sql);
    $dados_gastos = $result;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Planilha de Gastos Mensais</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f3f4f6;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      min-height: 100vh;
    }

    .container {
      margin-top: 60px;
      background: white;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      width: 90%;
      max-width: 700px;
      padding: 30px;
    }

    h1 {
      text-align: center;
      color: #1e293b;
      margin-bottom: 25px;
    }

    .mensagem {
      text-align: center;
      padding: 10px;
      margin-bottom: 20px;
      border-radius: 8px;
      font-weight: bold;
    }

    .sucesso {
      background-color: #dcfce7;
      color: #16a34a;
    }

    .erro {
      background-color: #fecaca;
      color: #dc2626;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    th, td {
      border: 1px solid #e2e8f0;
      padding: 12px;
      text-align: center;
      font-size: 16px;
    }

    th {
      background-color: #e2e8f0;
      color: #1e293b;
    }

    input[type="number"] {
      width: 90%;
      padding: 6px;
      text-align: right;
      border: 1px solid #cbd5e1;
      border-radius: 6px;
      font-size: 15px;
    }

    .resultado {
      font-weight: bold;
      color: #16a34a;
      background-color: #dcfce7;
    }

    .botao {
      display: block;
      width: 100%;
      background-color: #2563eb;
      color: white;
      padding: 12px;
      font-size: 16px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: 0.3s;
    }

    .botao:hover {
      background-color: #1e40af;
    }

    .rodape {
      text-align: center;
      color: #475569;
      font-size: 14px;
      margin-top: 15px;
    }
  </style>
</head>
<body>

  <div class="container">
    <h1>📊 Planilha de Gastos Mensais</h1>

    <?php if ($mensagem): ?>
      <div class="mensagem <?php echo strpos($mensagem, '✅') !== false ? 'sucesso' : 'erro'; ?>">
        <?php echo $mensagem; ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="">
      <table>
        <tr>
          <th>Descrição</th>
          <th>Valor (R$)</th>
        </tr>
        <tr>
          <td>Mês</td>
          <td><input type="text" name="mes_gasto" placeholder="Ex: Janeiro 2024" required style="width: 90%; padding: 6px; border: 1px solid #cbd5e1; border-radius: 6px;"></td>
        </tr>
        <tr>
          <td>Gasto com Confecção</td>
          <td><input type="number" name="gasto_confeccao" id="gasto_confeccao" step="0.01" min="0" placeholder="0,00"></td>
        </tr>
        <tr>
          <td>Mão de Obra (Corte)</td>
          <td><input type="number" name="gasto_mao_de_obra_corte" id="gasto_mao_de_obra_corte" step="0.01" min="0" placeholder="0,00"></td>
        </tr>
        <tr>
          <td>Gasto com Material</td>
          <td><input type="number" name="gasto_material" id="gasto_material" step="0.01" min="0" placeholder="0,00"></td>
        </tr>
        <tr class="resultado">
          <td>Total (R$)</td>
          <td id="gasto_total">0,00</td>
        </tr>
      </table>

      <button type="submit" class="botao">Salvar no Banco de Dados</button>
    </form>

    <a href="?ver=1" class="botao" style="text-decoration: none; margin-top: 10px; background-color: #16a34a;">Ver Tabela de Gastos</a>
    <a href="index.php" class="botao" style="text-decoration: none; margin-top: 10px; background-color: #f59e0b;">Voltar ao Index</a>
    
    <?php if ($mostrar_tabela && $dados_gastos): ?>
      <h2 style="text-align: center; margin-top: 30px;">Gastos Registrados</h2>
      <table style="margin-top: 20px;">
        <tr>
          <th>ID</th>
          <th>Mês</th>
          <th>Confecção</th>
          <th>Mão de Obra</th>
          <th>Material</th>
          <th>Total</th>
        </tr>
        <?php while ($row = $dados_gastos->fetch_assoc()): ?>
          <tr>
            <td><?php echo $row['id_gastos']; ?></td>
            <td><?php echo htmlspecialchars($row['mes_gasto']); ?></td>
            <td>R$ <?php echo number_format($row['gasto_confeccao'], 2, ',', '.'); ?></td>
            <td>R$ <?php echo number_format($row['gasto_mao_de_obra_corte'], 2, ',', '.'); ?></td>
            <td>R$ <?php echo number_format($row['gasto_material'], 2, ',', '.'); ?></td>
            <td>R$ <?php echo number_format($row['gasto_total'], 2, ',', '.'); ?></td>
          </tr>
        <?php endwhile; ?>
      </table>
      <a href="gasto_final.php" class="botao" style="text-decoration: none; margin-top: 10px; background-color: #6b7280;">Voltar ao Formulário</a>
    <?php endif; ?>

    <div class="rodape">
      Sistema de Controle Financeiro — Loja 🧾
    </div>
  </div>

  <script>
    function calcularGastoFinal() {
      const confeccao = parseFloat(document.getElementById('gasto_confeccao').value) || 0;
      const maoDeObra = parseFloat(document.getElementById('gasto_mao_de_obra_corte').value) || 0;
      const material = parseFloat(document.getElementById('gasto_material').value) || 0;

      const total = confeccao + maoDeObra + material;

      document.getElementById('gasto_total').textContent = total.toFixed(2).replace('.', ',');
    }

    const inputs = document.querySelectorAll('input[type="number"]');
    inputs.forEach(input => {
      input.addEventListener('input', calcularGastoFinal);
    });
  </script>

</body>
</html>