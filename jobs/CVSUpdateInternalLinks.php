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
$data = require('cvs_events.php');
//var_dump($data);
//exit();
$updated = 0;
$failed = 0;
/*
    select e.special_id, e.id as uid, e.website_url, e.proposed_venue_url, e.scientific_programme_url, c.website, c.programme,
    c.accommodation_url from ethical_m_live.cvs_events e inner join medtech_new_live.conference c on c.id = replace(e.special_id, 'EMT', '')
    where (e.website_url != c.website OR e.proposed_venue_url != c.accommodation_url OR e.scientific_programme_url != c.programme)
 */
foreach ($data as $event) {
    $website = ($event['website_url'] == $event['website']) ? $event['website_url'] : $event['website'];
    $sciUrl = ($event['scientific_programme_url'] == $event['programme']) ? $event['scientific_programme_url'] : $event['programme'];
    $venueUrl = ($event['proposed_venue_url'] == $event['accommodation_url']) ? $event['proposed_venue_url'] : $event['accommodation_url'];
    $update = $cvsEvents->updateUrlsNew($event['uid'], $website, $sciUrl, $venueUrl);
    if (!$update) {
        $failed++;
        continue;
    }
    $updated++;
}

print("\nupdated = {$updated}\nfailed = {$failed}\n");
