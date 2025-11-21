<?php
$host = getenv('DB_HOST') ?: 'dpg-d4g4q9ur433s73egcus0-a'; // Host do Render
$port = getenv('DB_PORT') ?: '5432';
$dbname = getenv('DB_NAME') ?: 'loja_db_q3b1';
$user = getenv('DB_USER') ?: 'admin';
$password = getenv('DB_PASSWORD') ?: 'LV96YEp7bXXUrV3dAzO1OFEZLeqXF766';

try {
    $conn = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die("❌ Erro na conexão PostgreSQL: " . $e->getMessage());
}
