<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

$servername = "localhost:8080";
$username = "root";
$password = "";
$dbname = "controle_estoque";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$method = $_SERVER['REQUEST_METHOD'];
parse_str(file_get_contents("php://input"), $input);

switch ($method) {
    case 'GET':
        $sql = "SELECT * FROM produtos";
        $result = $conn->query($sql);
        $data = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($data);
        break;
        
    case 'POST':
        $nome = $_POST['nome'];
        $quantidade = $_POST['quantidade'];
        $preco = $_POST['preco'];
        $categoria = $_POST['categoria'];
        $sql = "INSERT INTO produtos (nome, quantidade, preco, categoria) VALUES ('$nome', $quantidade, $preco, '$categoria')";
        $conn->query($sql);
        echo json_encode(["id" => $conn->insert_id]);
        break;
        
    case 'PUT':
        $id = $input['id'];
        $quantidade = $input['quantidade'];
        $sql = "UPDATE produtos SET quantidade = $quantidade WHERE id = $id";
        $conn->query($sql);
        echo json_encode(["success" => $conn->affected_rows > 0]);
        break;
        
    case 'DELETE':
        $id = $input['id'];
        $sql = "DELETE FROM produtos WHERE id = $id";
        $conn->query($sql);
        echo json_encode(["success" => $conn->affected_rows > 0]);
        break;
}

$conn->close();
?>
