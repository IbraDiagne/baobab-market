<?php
session_start();

$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data)) {
    http_response_code(400);
    exit();
}

$_SESSION["cart"] = $data;

echo json_encode(["status" => "ok"]);
