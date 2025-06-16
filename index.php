<?php
require 'config.php';

// Manejar el formulario POST para crear hábito
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';

    if ($name) {
        $stmt = $pdo->prepare("INSERT INTO habits (name, description) VALUES (?, ?)");
        $stmt->execute([$name, $description]);
        header("Location: index.php");
        exit;
    } else {
        $error = "El nombre es obligatorio";
    }
}

// Obtener todos los hábitos
$stmt = $pdo->query("SELECT * FROM habits ORDER BY created_at DESC");
$habits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<title>Habit Tracker</title>
</head>
<body>
<h1>Habit Tracker</h1>

<?php if (!empty($error)) : ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST" action="index.php">
    <label>Nombre del hábito: <input type="text" name="name" required></label><br>
    <label>Descripción: <textarea name="description"></textarea></label><br>
    <button type="submit">Añadir hábito</button>
</form>

<h2>Hábitos</h2>
<ul>
<?php foreach ($habits as $habit) : ?>
    <li>
        <strong><?= htmlspecialchars($habit['name']) ?></strong> - <?= htmlspecialchars($habit['description']) ?>
        <form method="POST" action="delete_habit.php" style="display:inline;">
            <input type="hidden" name="id" value="<?= $habit['id'] ?>">
            <button type="submit" onclick="return confirm('¿Eliminar hábito?')">Eliminar</button>
        </form>
    </li>
<?php endforeach; ?>
</ul>

</body>
</html>