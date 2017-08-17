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

$allEvents = $cvsEvents->getAllEventsIds();


foreach ($allEvents as $event) {
    if (!is_null($event['special_id'])) {
        if (strpos($event['special_id'], 'MTE') !== false) {
            $event['special_id'] = str_replace('MTE', 'EMT', $event['special_id']);
        }
    }

    if (!is_null($event['old_special_id'])) {
        if (strpos($event['old_special_id'], 'MTE') !== false) {
            $event['old_special_id'] = str_replace('MTE', 'EMT', $event['old_special_id']);
        }
    }

    $cvsEvents->updateEventsIdsOld($event['special_id'], $event['old_special_id'], $event['id']);
}
