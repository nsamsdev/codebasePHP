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


$newUserIds = $cvsExtras->getNewUsersIds();


foreach ($newUserIds as $newUserId) {
    $userId = str_replace('new_', '', $newUserId['old_id']);
    $realId = $newUserId['id'];
    $events = $cvsExtras->getOldNewUserEvents($userId);


    foreach ($events as $event) {
        $eventDocs = $cvsDocuments->getDocsClean($event['id']);
        $eventMessages = $cvsMessages->getEventMessagesClean($event['id']);
        $eventId = $cvsEvents->addCustomEvent($event, $realId);

        if (!$eventId) {
            continue;
        }

        foreach ($eventDocs as $doc) {
            $name = $doc['document_title'];
            $newPath = STORAGE_LOCATION . md5(uniqid()) . generateRandomString() . $name;
            while (file_exists($newPath)) {
                $newPath = STORAGE_LOCATION . md5(uniqid()) . generateRandomString() . $name;
            }
            if (!copy($doc['document_path'], $newPath)) {
                continue;
            }
            $docAdded = $cvsDocuments->addDoc($doc['document_title'], $newPath, $doc['uploader_type'], $realId, $eventId);
        }

        foreach ($eventMessages as $eventMessage) {
            $cvsMessages->addSystemMessage($eventMessage['message_type'], $eventMessage['message_from'], $eventMessage['message_to'], $eventId,
                $realId, $eventMessage['message'], $eventMessage['mail_to'], $eventMessage['subject']);
        }
    }
}
