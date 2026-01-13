<body>
    <div id="container">
<?php
$servername     = $_ENV['DATABASE_HOST'];
$dbname         = $_ENV['DATABASE_DB'];
$username       = $_ENV['DATABASE_USER'];
$password       = $_ENV['DATABASE_PASSWD'];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p>Conexão com MariaDB bem-sucedida!</p>";
} catch(PDOException $e) {
    echo "<p>Falha na conexão com MariaDB: " . $e->getMessage() . "</p>";
}
$subfoldering = (count($aCurrentPage) > 1) ? "/".$aCurrentPage[1] : "";
$folder = $aCurrentPage[0].$subfoldering;

require_once("./resources/views/$folder/$currentModule.php");
?>
    </div>
</body>