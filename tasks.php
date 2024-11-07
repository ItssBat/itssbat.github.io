<?php
header('Content-Type: application/json');

$filename = 'tasks.json';

// Comprovar si el fitxer existeix i crear-lo si no
if (!file_exists($filename)) {
    file_put_contents($filename, json_encode([]));
}

// Llegir les tasques
$tasks = json_decode(file_get_contents($filename), true);

// Gestió de les peticions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Afegir una nova tasca
    $data = json_decode(file_get_contents('php://input'), true);
    $data['id'] = count($tasks) + 1; // Generar un ID per a la tasca
    $tasks[] = $data;
    file_put_contents($filename, json_encode($tasks));
    echo json_encode(['success' => true]);
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Eliminar una tasca
    $data = json_decode(file_get_contents('php://input'), true);
    $taskId = $data['id'];

    foreach ($tasks as $index => $task) {
        if ($task['id'] == $taskId) {
            unset($tasks[$index]);
            file_put_contents($filename, json_encode(array_values($tasks))); // Actualitzar el fitxer
            echo json_encode(['success' => true]);
            exit;
        }
    }
    echo json_encode(['success' => false, 'message' => 'Tasca no trobada']);
    exit;
}

// Retornar totes les tasques
echo json_encode(['tasks' => $tasks]);
