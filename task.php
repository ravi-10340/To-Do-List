<?php
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['userid'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit();
}
$userid = $_SESSION['userid'];
$con = mysqli_connect("localhost", "root", "", "mypdb");
if (!$con) {
    echo json_encode(['status' => 'error', 'message' => 'DB connection error']);
    exit();
}
$action = $_POST['action'] ?? '';
$taskId = $_POST['id'] ?? null;
$text = $_POST['task'] ?? '';
$response = [];
switch ($action) {
    case 'add':
        if (trim($text)) {
            $dueTime = $_POST['due_time'] ?? null;
            $stmt = $con->prepare("INSERT INTO tasks (userid, task, due_time) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $userid, $text, $dueTime);
            $stmt->execute();
            $response = ['status' => 'success', 'id' => $stmt->insert_id];
        }
        break;
    case 'delete':
        $stmt = $con->prepare("DELETE FROM tasks WHERE id = ? AND userid = ?");
        $stmt->bind_param("ii", $taskId, $userid);
        $stmt->execute();
        $response = ['status' => 'success'];
        break;
    case 'toggle':
        $stmt = $con->prepare("UPDATE tasks SET is_done = NOT is_done WHERE id = ? AND userid = ?");
        $stmt->bind_param("ii", $taskId, $userid);
        $stmt->execute();
        $response = ['status' => 'success'];
        break;
    case 'fetch':
        $stmt = $con->prepare("SELECT id, task, is_done, due_time FROM tasks WHERE userid = ? ORDER BY id DESC LIMIT 100");
        $stmt->bind_param("i", $userid);
        $stmt->execute();
        $result = $stmt->get_result();
        $tasks = $result->fetch_all(MYSQLI_ASSOC);
        $response = ['status' => 'success', 'tasks' => $tasks];
        break;
    default:
        $response = ['status' => 'error', 'message' => 'Invalid action'];
}
echo json_encode($response);
?>
