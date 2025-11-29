<?php
date_default_timezone_set('America/Managua'); // Ajusta a tu zona horaria local
require '../Conexion/conex.php';


// Recibir datos de AJAX
$por_pagina = isset($_GET['por_pagina']) ? (int)$_GET['por_pagina'] : 5;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$busqueda = isset($_GET['busqueda']) ? $conn->real_escape_string($_GET['busqueda']) : '';
$inicio = ($pagina - 1) * $por_pagina;

// Contar total de monedas con búsqueda
$sql_count = "SELECT COUNT(*) AS total FROM Moneda
              WHERE nombre LIKE '%$busqueda%' OR simbolo LIKE '%$busqueda%'";
$result_count = $conn->query($sql_count);
$total_monedas = $result_count->fetch_assoc()['total'];
$total_paginas = ceil($total_monedas / $por_pagina);

// Consulta con límite y búsqueda
$sql = "
    SELECT * 
    FROM Moneda 
    WHERE (nombre, fecha_creacion) IN (
        SELECT nombre, MAX(fecha_creacion)
        FROM Moneda
        GROUP BY nombre
    )
    AND (nombre LIKE '%$busqueda%' OR simbolo LIKE '%$busqueda%')
    ORDER BY fecha_creacion DESC
    LIMIT $inicio, $por_pagina
";
$result = $conn->query($sql);
$monedas = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $monedas[] = $row;
    }
}

// Mostrar tabla
if (!empty($monedas)) {
    echo '<div class="table-responsive">
            <table class="table table-striped text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>🏷️ Nombre</th>
                        <th>💲 Símbolo</th>
                        <th>🌍 Tipo</th>
                        <th>🇳🇮 País</th>
                        <th>✅ Estado</th>
                    </tr>
                </thead>
                <tbody>';
    foreach ($monedas as $moneda) {
        echo '<tr>
                <td>💰 '.htmlspecialchars($moneda['nombre']).'</td>
                <td>💵 '.htmlspecialchars($moneda['simbolo']).'</td>
                <td>🌎 '.htmlspecialchars($moneda['tipo']).'</td>
                <td>🏳️‍🌈 '.htmlspecialchars($moneda['pais']).'</td>
                <td>🟢 '.htmlspecialchars($moneda['estado']).'</td>
              </tr>';
    }
    echo '</tbody></table></div>';

    // Paginación
    if ($total_paginas > 1) {
        echo '<nav><ul class="pagination justify-content-center">';
        for ($i = 1; $i <= $total_paginas; $i++) {
            echo '<li class="page-item '.($i==$pagina?'active':'').'">
                    <a href="#" class="page-link" data-pagina="'.$i.'">'.$i.'</a>
                  </li>';
        }
        echo '</ul></nav>';
    }
} else {
    echo "<p class='text-center text-muted'>🚫 No hay monedas que coincidan con la búsqueda.</p>";
}

$conn->close();