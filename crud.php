//1. Crear la base de datos y tabla
//Primero, necesitamos una base de datos. Usa phpMyAdmin o cualquier cliente SQL para crear una base de datos y una tabla.


CREATE DATABASE crud_demo;

USE crud_demo;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL
);


//2. Crear la estructura básica de archivos
//Crea un directorio llamado crud_demo y dentro, organiza estos archivos:


crud/
│
├── index.php        (Mostrar y eliminar registros)
├── create.php       (Formulario para añadir registros)
├── update.php       (Formulario para actualizar registros)
└── db.php           (Conexión a la base de datos)

//3. Crear el archivo de conexión a la base de datos (db.php)
//Este archivo se conectará a la base de datos.


<?php
$host = "localhost";
$user = "root"; // Cambia por tu usuario de MySQL
$password = ""; // Cambia por tu contraseña de MySQL
$dbname = "crud_demo";

$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}
?>
  
//4. Listar y eliminar registros (index.php)
//Este archivo mostrará los registros de la tabla y permitirá eliminarlos.


<?php
include 'db.php';

// Consultar todos los registros
$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
</head>
<body>
    <h1>Usuarios</h1>
    <a href="create.php">Añadir Usuario</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td>
                <a href="update.php?id=<?php echo $row['id']; ?>">Editar</a>
                <a href="delete.php?id=<?php echo $row['id']; ?>">Eliminar</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>


//5. Crear usuarios (create.php)
//Un formulario para añadir nuevos usuarios.


<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];

    $sql = "INSERT INTO users (name, email) VALUES ('$name', '$email')";
    mysqli_query($conn, $sql);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Usuario</title>
</head>
<body>
    <h1>Añadir Usuario</h1>
    <form method="POST">
        <label>Nombre:</label>
        <input type="text" name="name" required>
        <br>
        <label>Email:</label>
        <input type="email" name="email" required>
        <br>
        <button type="submit">Guardar</button>
    </form>
</body>
</html>

//6. Actualizar usuarios (update.php)
//Un formulario para editar información de un usuario existente.


<?php
include 'db.php';

$id = $_GET['id'];
$sql = "SELECT * FROM users WHERE id = $id";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];

    $sql = "UPDATE users SET name = '$name', email = '$email' WHERE id = $id";
    mysqli_query($conn, $sql);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
</head>
<body>
    <h1>Editar Usuario</h1>
    <form method="POST">
        <label>Nombre:</label>
        <input type="text" name="name" value="<?php echo $user['name']; ?>" required>
        <br>
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
        <br>
        <button type="submit">Actualizar</button>
    </form>
</body>
</html>

//7. Eliminar usuarios (delete.php)
//Elimina un usuario basándose en el id.


<?php
include 'db.php';

$id = $_GET['id'];

$sql = "DELETE FROM users WHERE id = $id";
mysqli_query($conn, $sql);

header("Location: index.php");
exit;
?>
