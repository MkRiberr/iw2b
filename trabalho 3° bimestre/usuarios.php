<?php
header("Content-type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

$host = "localhost";
$user = "root";
$pass = "";
$db   = "sistema";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "falha na conexão: " . $conn->connect_error]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
//echo $method;
switch ($method) {
    case 'GET':
        if (isset($_GET['pesquisa'])) {
            $pesquisa = "%" . $_GET['pesquisa'] . "%";

            $stmt = $conn->prepare("SELECT * FROM usuarios WHERE Login LIKE ?");
            $stmt->bind_param("s", $pesquisa);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $conn->query("SELECT * FROM usuarios ORDER BY ID DESC");
        }

        $retorno = [];
        while ($linha = $result->fetch_assoc()) {
            $retorno[] = $linha;
        }

        echo json_encode($retorno);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("INSERT INTO usuarios (Login, Nome, Email, Senha, Ativo) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $data['LOGIN'], $data['NOME'], $data['EMAIL'], $data['SENHA'], $data['ATIVO']);
        $stmt->execute();
        
        echo json_encode(["status" => "ok", "insert_id" => $stmt->insert_id]);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("UPDATE usuarios SET LOGIN=?, NOME=?, EMAIL=?, SENHA=?, ATIVO=? WHERE ID=?");
        $stmt->bind_param("ssssii", $data['LOGIN'], $data['NOME'], $data['EMAIL'], $data['SENHA'], $data['ATIVO'], $data['ID']);
        $stmt->execute();

        echo json_encode(["status" => "ok"]);
        break;

    case 'DELETE':
        $id = $_GET['id'];
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE ID=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        echo json_encode(["status" => "ok"]);
        break;
}

$conn->close();
?>
