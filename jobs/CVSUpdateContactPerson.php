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
$notUpdated = 0;
$updated = 0;
foreach ($cvsExtras->getOldEventContacts() as $oldEvent) {
    $specialId = 'EMT' . $oldEvent['id'];
    $contact = $oldEvent['contact_person'] ?? '';
    if (!$cvsExtras->updateEventContacts($specialId, $contact)) {
        $notUpdated++;
        ;
        continue;
    }
    $updated++;
}


print("\nupdated = {$updated}\nfailed = {$notUpdated}\n");
