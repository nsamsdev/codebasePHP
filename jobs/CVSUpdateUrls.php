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
$failed = 0;
$completed = 0;
$currentEvents = $cvsExtras->getAllCurrentEventsIds();

foreach ($currentEvents as $event) {
    //$oldId = str_replace(['EMT', ' '], '', $event['special_id']);
    //$oldEvent = $cvsExtras->getOldEventDetails($oldId);
    $websiteUrl = $event['website_url'] ?? '';
    $programme = $event['scientific_programme_url'] ?? '';
    $proposedVenueUrl = $event['proposed_venue_url'] ?? '';
    $updated = $cvsEvents->updateUrlsNew($event['special_id'], $websiteUrl, $programme, $proposedVenueUrl);
    if (!$updated) {
        $failed++;
        continue;
    }
    $completed++;
}

/*
foreach($cvsEvents->getUrls() as $eventUrls) {
    $eventId = $eventUrls['id'];
    $websiteUrl = str_replace('https/', 'https:/', str_replace('http/', 'http:/',$eventUrls['website_url']));
    $proposedVenueUrl = str_replace('https/', 'https:/', str_replace('http/', 'http:/',$eventUrls['proposed_venue_url']));
    $scientificUrl = str_replace('https/', 'https:/', str_replace('http/', 'http:/',$eventUrls['scientific_programme_url']));
    $regUrl = str_replace('https/', 'https:/', str_replace('http/', 'http:/',$eventUrls['event_registration_fees_and_benefits_url']));

    //print("\n{$eventUrls['website_url']}\n");

    $updated = $cvsEvents->updateUrls($eventId, $websiteUrl, $scientificUrl, $proposedVenueUrl, $regUrl);
    if (!$updated) {
        $failed++;
        continue;
    }
    $completed++;
}
 */

print("\ncompleted = {$completed}\nfailed = {$failed}\n");
