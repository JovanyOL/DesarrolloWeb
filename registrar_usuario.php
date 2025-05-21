<?php
// registrar_usuario.php
session_start();

$host = "localhost";
$user_db = "root";
$pass_db = "";
$db_name = "invenTrack";

$conn = new mysqli($host, $user_db, $pass_db, $db_name);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$rol = $_POST['rol'] ?? '';
$fullname = $_POST['fullname'] ?? '';
$phone = $_POST['phone'] ?? '';

// Validaciones básicas
if (!$username || !$email || !$password || !$confirm_password || !$rol) {
    echo "<script>alert('Complete todos los campos obligatorios.'); window.history.back();</script>";
    exit;
}

if ($password !== $confirm_password) {
    echo "<script>alert('Las contraseñas no coinciden.'); window.history.back();</script>";
    exit;
}

// Validar si usuario ya existe
$sql = "SELECT * FROM usuarios WHERE nombre_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('El usuario ya existe.'); window.history.back();</script>";
    exit;
}

// Hashear la contraseña
$hash_password = password_hash($password, PASSWORD_DEFAULT);

// Insertar usuario
$sql = "INSERT INTO usuarios (nombre_usuario, correo_electronico, contraseña, rol, nombre_completo, telefono) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $username, $email, $hash_password, $rol, $fullname, $phone);

if ($stmt->execute()) {
    echo "<script>alert('Usuario registrado exitosamente.'); window.location.href = 'inicio_sesion.html';</script>";
} else {
    echo "<script>alert('Error al registrar usuario.'); window.history.back();</script>";
}

$conn->close();
?>
