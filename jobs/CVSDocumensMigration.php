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
$notfound = 0;
$looped = 0;
$filesFound = 0;
$notCopied = 0;
$filesCpoied = 0;
foreach ($cvsEvents->getAllEventsIds() as $event) {
    $oldId = str_replace('EMT', '', $event['special_id']);
    $eventId = $event['id'];

    $documents = $cvsExtras->getOldEventDocs($oldId);

    foreach ($documents as $document) {
        $file = pathinfo($document['file']);
        @$file_extension = $file['extension'];
    
        $looped ++;
        //add event documents
        
        if (!file_exists('/var/www/vhosts/ethicalmedtech.eu/httpdocs/medtech-apps/old_data/' . $document['file'])) {
            $notfound++;
            continue;
        } else {
            $filesFound++;
        }

        $oldFileName = $file['filename'] . '.' . $file_extension;
        $newName = md5(uniqid()) . generateRandomString() . "." . $file_extension;
        $newPath = '/var/www/vhosts/ethicalmedtech.eu/httpdocs/medtech-apps/public/__fl_uploads/medtech/' . $newName;
        
        while (file_exists($newPath)) {
            $newPath = '/var/www/vhosts/ethicalmedtech.eu/httpdocs/medtech-apps/public/__fl_uploads/medtech/' . md5(uniqid()) . generateRandomString() . "." . $file_extension;
        }


        if (!copy('/var/www/vhosts/ethicalmedtech.eu/httpdocs/medtech-apps/old_data/' . $document['file'], $newPath)) {
            $notCopied++;
            continue;
        }

        $filesCpoied++;
        $docMoved = $cvsDocuments->addDoc($oldFileName, $newPath, 'migrated_doc', $event['uid'], $eventId);
    }
}


print("\nlooped = {$looped}\nfound = {$filesFound}\nnot found = {$notfound}\ncopied = {$filesCpoied}\nnot copied = {$notCopied}");
exit();
