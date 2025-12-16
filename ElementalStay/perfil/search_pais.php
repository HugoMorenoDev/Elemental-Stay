<?php
// Establecer los datos de conexión
$host = 'localhost'; 
$usuario = 'root';   
$contraseña = 'EwnizEv5';    
$nombre_base_datos = 'elementalStay'; 

// Crear la conexión
$conn = new mysqli($host, $usuario, $contraseña, $nombre_base_datos);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el término de búsqueda enviado por AJAX
$term = $_GET['term']; 

// Consulta para obtener los países que comienzan con el término (usando LIKE)
$sql = "SELECT pais FROM paises_usuario WHERE pais LIKE ? LIMIT 10";
$stmt = $conn->prepare($sql);
$searchTerm = $term . "%"; // Buscar países que comiencen con el término ingresado
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

// Recoger los resultados
$paises = [];
while ($row = $result->fetch_assoc()) {
    $paises[] = $row['pais'];
}

// Devolver los resultados en formato JSON
echo json_encode($paises);
?>
