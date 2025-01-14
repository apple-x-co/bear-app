<?php

namespace Koriym\SqlQuality;

use PDO;
use function dirname;

require dirname(__DIR__) . '/vendor/autoload.php';

$pdo = new PDO('mysql:host=bear-mysql;dbname=bear_db', 'root', 'passw0rd', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$sqlParams = require dirname(__DIR__) . '/tests/params/sql_params.php';

$analyzer = new SqlFileAnalyzer(
    $pdo,
    new ExplainAnalyzer(),
    dirname(__DIR__) . '/var/sql',
    new AIQueryAdvisor('以上の分析を日本語で記述してください。')
);

$analyzer->analyzeSqlDirectory($sqlParams, __DIR__ . '/sql-quality');
