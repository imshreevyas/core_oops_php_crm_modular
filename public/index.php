<?php
require __DIR__ . '/../vendor/autoload.php';

use CoreCRM\Database\Connection;
use CoreCRM\Repositories\MysqlCustomerRepository;
use CoreCRM\Models\Customer;

// create DB connection (adjust env or pass config)
$conn = new Connection([
    'host' => '127.0.0.1',
    'port' => '3306',
    'database' => 'core_crm',   // ensure this DB exists
    'user' => 'root',
    'password' => ''            // change if needed
]);

$repo = new MysqlCustomerRepository($conn);

// create sample customers (only if none exist)
if (count($repo->all()) === 0) {
    $repo->save(new Customer('Alice Example','alice@example.com','919812345678'));
    $repo->save(new Customer('Bob Example','bob@example.com','919876543210'));
}

echo "<h3>Customers</h3><pre>";
print_r($repo->all());
echo "</pre>";
