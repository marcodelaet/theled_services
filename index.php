<?php
echo "<h1>Ambiente Docker PHP/Apache/MariaDB</h1>";

// Teste de conexão com o MariaDB (substitua pelos seus dados)
$servername = "mariadb"; // Nome do serviço no docker-compose.yml
$username = "root";
$password = "123456abc#";
$dbname = "theled_dev_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p>Conexão com MariaDB bem-sucedida!</p>";
} catch(PDOException $e) {
    echo "<p>Falha na conexão com MariaDB: " . $e->getMessage() . "</p>";
}

phpinfo();

?>