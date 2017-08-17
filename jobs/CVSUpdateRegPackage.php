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
$data = require('cvs_events_criteria_checks.php');
$failed = 0;
$updated = 0;
foreach ($data as $event) {
    if ($event['event_id'] == $event['eid']) {
        //remove package name
        $removeComent = $criteriaChecks->removeUnwantedComment($event['criteria_id'], $event['criteria_txt'], $event['eid']);
        if (!$removeComent) {
            $failed++;
            continue;
        }
        $updated++;
    }
}

print("\nupdated = {$updated}\nfailed = {$failed}");
exit();

//var_dump($data);exit();
