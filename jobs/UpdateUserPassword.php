<?php
require __DIR__ . '/../vendor/autoload.php';
define('CODEBASE', __DIR__ . '/../');

require __DIR__ . '/../configs/configs-cli.php';
require __DIR__ . '/../helpers/helpers.php';

use CodeBase\Managers\CVSManager;
use CodeBase\Models\CVSEvents;
use CodeBase\Models\CVSUsers;
use CodeBase\Models\CVSEventsStatuses;
use CodeBase\Models\CVSExtras;
use CodeBase\Models\CVSEventsDocuments;
use CodeBase\Models\CVSMessages;
use CodeBase\Managers\FormsManager;
use CodeBase\Models\CVSEventsCriteriaChecks;

$cvsManager = new CVSManager();
$cvsMessages = new CVSMessages();
$cvsEvents = new CVSEvents();
$cvsUsers = new CVSUsers();
$cvsStatuses = new CVSEventsStatuses();
$cvsExtras = new CVSExtras();
$cvsDocuments = new CVSEventsDocuments();
$cvsFormManager = new FormsManager();
$criteriaChecks = new CVSEventsCriteriaChecks();

$username = $argv[1] ?? '';
$password = $argv[2] ?? '';

if (empty($username) || empty($password)) {
    print("\nempty fields\n");
    exit();
}

$user = $cvsUsers->getUserByUsername($username);

if (empty($user)) {
    print("\nUser not found\n");
    exit();
}

//var_dump($user);
//exit();

$updated = $cvsUsers->changeUserPassword($user['id'], $password);

if (!$updated) {
    print("\nunable to update password\n");
    exit();
}


print("\n Password updated to {$password}\n");
exit();
