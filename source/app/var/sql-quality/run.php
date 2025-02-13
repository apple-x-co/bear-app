<?php

namespace Koriym\SqlQuality;

use PDO;
use function dirname;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

$pdo = new PDO('mysql:host=sql-quality;dbname=sql_quality_db', 'sql_quality', 'passw0rd', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$sqlParams = require 'params.php';

$analyzer = new SqlFileAnalyzer(
    $pdo,
    new ExplainAnalyzer(),
    dirname(__DIR__, 2) . '/var/sql',
    new AIQueryAdvisor('以上の分析を日本語で記述してください。'),
    null,
);

$analyzer->analyzeSqlDirectory($sqlParams, __DIR__ . '/results');
