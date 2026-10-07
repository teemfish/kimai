<?php

$databaseUrl = (string) getenv('DBTEST_URL');
$params = parse_url($databaseUrl);

if ($params === false || !isset($params['scheme'], $params['host'], $params['path'])) {
    echo 'Invalid DATABASE_URL';
    exit(10);
}

$driver = match ($params['scheme']) {
    'mysql', 'mysql2', 'pdo-mysql' => 'mysql',
    'postgres', 'postgresql', 'pgsql', 'pdo-pgsql' => 'pgsql',
    default => null,
};

if ($driver === null || !in_array($driver, PDO::getAvailableDrivers(), true)) {
    echo 'Unsupported or unavailable database driver';
    exit(10);
}

$host = $params['host'];
$port = $params['port'] ?? ($driver === 'pgsql' ? 5432 : 3306);
$database = rawurldecode(substr($params['path'], 1));
$user = rawurldecode($params['user'] ?? '');
$password = rawurldecode($params['pass'] ?? '');
$dsn = "$driver:host=$host;port=$port;dbname=$database";
if ($driver === 'pgsql') {
    $dsn .= ';connect_timeout=5';
    parse_str($params['query'] ?? '', $options);
    foreach (['sslmode', 'sslrootcert', 'sslcert', 'sslkey'] as $option) {
        if (isset($options[$option]) && is_string($options[$option])) {
            $dsn .= ';' . $option . '=' . $options[$option];
        }
    }
}

echo 'Testing DB:';

try {
    new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $ex) {
    $error = $ex->errorInfo[1] ?? null;
    if ($driver === 'mysql' && $error === 1049) {
        // Kimai creates the database during installation.
        return;
    }
    if ($driver === 'mysql' && $error === 1045) {
        echo 'Access denied';
        exit(1);
    }
    if ($driver === 'pgsql' && preg_match('/database ".*" does not exist/', $ex->getMessage()) === 1) {
        return;
    }
    if ($driver === 'pgsql' && str_contains($ex->getMessage(), 'password authentication failed')) {
        echo 'Access denied';
        exit(1);
    }

    echo 'Database connection unavailable';
    exit(5);
}
