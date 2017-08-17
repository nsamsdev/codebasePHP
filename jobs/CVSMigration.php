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
$start = 0;
$end = 50;
$year = '2016';
$count = 0;
$failedUser = 0;
$failedEvent = 0;
$eventCount = 0;
$failedDoc = 0;
$movedDoc = 0;
$messageAdded = 0;
$failedMessage = 0;
$failedCri = 0;
$addedCri = 0;
$failedEventUpdate = 0;
$eventUpdate = 0;
$failedIdUpdate = 0;
$userIdUpdate = 0;
$failedOldMessageUpdate = 0;
$oldMessageUpdate = 0;
$oldUsers = $cvsExtras->getOldUsersIds();


$preClearance = [
    'Pre-Clearance approved',
    'To be reviewed for Pre-Clearance',
    'Pre-Clearance not approved',
    'Saved for Pre-Clearance'
];

$localCriteriaNames = [
    1 => 'Scientific Programme',
    2 => 'Geographic Location',
    3 => 'Conference Venue',
    4 => 'Hospitality (Coffee breaks, lunches, welcome reception and gala dinner)',
    5 => 'Accommodation',
    6 => 'Accompanying persons/spouses',
    7 => 'Communication Support',
    8 => 'Social programme'
];

try {
    foreach ($oldUsers as &$user) {


        //get User Events
        $events = $cvsExtras->getOldEvents($user['old_id']);
        if (empty($events)) {
            unset($user);
            continue;
        }

        $userId = $user['id'];

        foreach ($events as &$event) {
            $currentHospitality = [];
            $currentRegistration = [];
            $hospitalityStatus = 0;
            $registrationStatus = 0;
            $event['documents'] = $cvsExtras->getOldEventDocs($event['id']);
            if (!empty($event['accommodation_document'])) {
                $event['documents'][] = ['file' => $event['accommodation_document']];
            }

            if (empty($event['medical_area_other'])) {
                $event['medical_area_other'] = $event['medical_area'];
            }

            $event['messages'] = $cvsExtras->getOldEventMessages($event['id']);
            $event['e_comments'] = $cvsExtras->getOldComments($event['id']);
            $event['criterias'] = $cvsExtras->getOldCriteria($event['id']);
            $criteriasAndStatus = [];

            foreach ($event['criterias'] as $cri) {
                if ($cri['assessment_criteria_id'] == 6 || $cri['assessment_criteria_id'] == 8) {
                    /*@todo the check and comparison for old assessment criteria */
                    $currentRegistration[] = $cri['status'];
                    continue;
                }

                if ($cri['assessment_criteria_id'] == 4 || $cri['assessment_criteria_id'] == 5) {
                    $currentHospitality[] = $cri['status'];
                }
                $cri['name'] = $localCriteriaNames[$cri['assessment_criteria_id']];
                if ($cri['assessment_criteria_id'] == 7) {
                    $cri['assessment_criteria_id'] = 6;
                }
                $criteriasAndStatus[] = $cri;
            }

            //to work with registration package and hospitality
            if (!empty($currentHospitality)) {
                $hospitality = $currentHospitality[0] ?? 1;
                $accommodation = $currentHospitality[1] ?? 1;

                if ($hospitality == 1 && $accommodation == 1) {
                    $hospitalityStatus = 1;
                } elseif ($hospitality == 1 && $accommodation == 2) {
                    $hospitalityStatus = 1;
                } elseif ($hospitality == 1 && $accommodation == 3) {
                    $hospitalityStatus = 3;
                } elseif ($hospitality == 1 && $accommodation == 0) {
                    $hospitalityStatus = 1;
                } elseif ($hospitality == 2 && $accommodation == 1) {
                    $hospitalityStatus = 1;
                } elseif ($hospitality == 2 && $accommodation == 2) {
                    $hospitalityStatus = 2;
                } elseif ($hospitality == 2 && $accommodation == 3) {
                    $hospitalityStatus = 3;
                } elseif ($hospitality == 2 && $accommodation == 0) {
                    $hospitalityStatus = 2;
                } elseif ($hospitality == 3 && $accommodation == 1) {
                    $hospitalityStatus = 3;
                } elseif ($hospitality == 3 && $accommodation == 2) {
                    $hospitalityStatus = 3;
                } elseif ($hospitality == 3 && $accommodation == 3) {
                    $hospitalityStatus = 3;
                } elseif ($hospitality == 3 && $accommodation == 0) {
                    $hospitalityStatus = 3;
                } elseif ($hospitality == 0 && $accommodation == 1) {
                    $hospitalityStatus = 1;
                } elseif ($hospitality == 0 && $accommodation == 2) {
                    $hospitalityStatus = 2;
                } elseif ($hospitality == 0 && $accommodation == 3) {
                    $hospitalityStatus = 3;
                }

                foreach ($criteriasAndStatus as &$criStatus) {
                    if ($criStatus['assessment_criteria_id'] == 4) {
                        $criStatus['status'] = $hospitalityStatus;
                    }
                }
            }

            if (!empty($currentRegistration)) {
                $accompanyingPerson = $currentRegistration[0] ?? 1;
                $socialProgram = $currentRegistration[1] ?? 1;
                //@todo continue here with criteria fixing
                if ($hospitality == 1 && $accommodation == 1) {
                    $registrationStatus = 1;
                } elseif ($accompanyingPerson == 1 && $socialProgram == 2) {
                    $registrationStatus = 1;
                } elseif ($accompanyingPerson == 1 && $socialProgram == 3) {
                    $registrationStatus = 3;
                } elseif ($accompanyingPerson == 1 && $socialProgram == 0) {
                    $registrationStatus = 1;
                } elseif ($accompanyingPerson == 2 && $socialProgram == 1) {
                    $registrationStatus = 1;
                } elseif ($accompanyingPerson == 2 && $socialProgram == 2) {
                    $registrationStatus = 2;
                } elseif ($accompanyingPerson == 2 && $socialProgram == 3) {
                    $registrationStatus = 3;
                } elseif ($accompanyingPerson == 2 && $socialProgram == 0) {
                    $registrationStatus = 2;
                } elseif ($accompanyingPerson == 3 && $socialProgram == 1) {
                    $registrationStatus = 3;
                } elseif ($accompanyingPerson == 3 && $socialProgram == 2) {
                    $registrationStatus = 3;
                } elseif ($accompanyingPerson == 3 && $socialProgram == 3) {
                    $registrationStatus = 3;
                } elseif ($accompanyingPerson == 3 && $socialProgram == 0) {
                    $registrationStatus = 3;
                } elseif ($accompanyingPerson == 0 && $socialProgram == 1) {
                    $registrationStatus = 1;
                } elseif ($accompanyingPerson == 0 && $socialProgram == 2) {
                    $registrationStatus = 2;
                } elseif ($accompanyingPerson == 0 && $socialProgram == 3) {
                    $registrationStatus = 3;
                }

                $criteriasAndStatus[] = [
                    'name' => 'Registration Package',
                    'assessment_criteria_id' => 5,
                    'status' => $registrationStatus
                ];
            }

            //add events
            $newEvent = [
                'eventName' => $event['name'] ?? '',
                'eventShortcut' => $event['acronym'] ?? '',
                'evenArea' => $event['medical_area_other'] ?? '',
                'otherArea' => $event['medical_area_other'] ?? '',
                'eventStart' => $event['start_date'] ?? '',
                'eventEnd' => $event['end_date'] ?? '',
                'organiserName_s' => $event['organiser'] ?? '',
                'contactName_s' => $event['ccontact_person'] ?? '',
                'organiserEmail' => $event['email'] ?? ($event['general_email'] ?? ''),
                'eventWebsite' => $event['website'] ?? '',
                'eventType' => 'Unkown Old Event',
                'eventTypeOther' => 'Unkown Old Event',
                'is_old' => true,
                'attendByGeographicArea' => 'Unkown',
                'fromMoreThanOneCountry' => ((strlen($event['countries']) > 4) ? 'YES' : 'NO'),
                'scientificProgramDocs' => [],
                'scientificProgramUrl' => $event['programme'] ?? '',
                'feesAndBenefitsDocs' => [],
                'feesAndBenefitsUrl' => 'Unkown',
                'registrationPackageDocs' => [],
                'registrationPackageUrl' => 'Unkown',
                'venueName' => $event['venue_name'] ?? '',
                'venueType' => $event['venue_category'] ?? '',
                'cityTown' => $event['venue_city'] ?? '',
                'country' => $event['venue_country'] ?? '',
                'localAssoc' => $event['venue_national_asociation'] ?? 'Unkown',
                'accomUrl' => $event['accommodation_url'] ?? '',
                'accomDoc' => [],
                'otherDoc_s' => [],
                'autoPublish' => ($event['publish_preclearance'] == 1) ? 'auto_publish' : null,
                'cli_user' => $userId
            ];
            if (!in_array($event['status'], $preClearance)) {
                $eventId = $cvsFormManager->addEvent($newEvent);
            } else {
                $eventId = $cvsFormManager->addPreEvent($newEvent);
            }

            if (!$eventId) {
                $cvsExtras->addFailedEvent($event, $userId);
                unset($event);
                $failedEvent++;
                continue;
            }

            //update created and updated at
            $updatedCreated = $cvsEvents->updateOldEventDatesAndArchive($event['submission_date'] ?? '', ($event['assessed_on'] ?? $GLOBALS['dateTimeHour']), $eventId, 'EMT' . $event['id'], (int)$event['archive']);

            if (!$updatedCreated) {
                $failedEventUpdate++;
                continue;
            } else {
                $eventUpdate++;
            }

            //update event_status_old
            $statusUpdated = $cvsEvents->updateOldEventStatus($event['status'], $eventId, $event['status_comment'], $event['assessed_on']);


            //add new criteria
            foreach ($criteriasAndStatus as &$cri) {
                $criCheck = $criteriaChecks->addOrUpdateEventCriteriaCheck($eventId, $cri['assessment_criteria_id'], $cri['status'] ?? 0, (($cri['assessment_criteria_id'] == 5) ? $event['comments'] : ''), $cri['name']);
                if (!$criCheck) {
                    $failedCri++;
                    unset($cri);
                    continue;
                }
                unset($cri);
                $addedCri++;
            }

            //add event messages
            $commentsAndMessages = array_merge($event['messages'], $event['e_comments']);
            foreach ($commentsAndMessages as &$message) {
                if (isset($message['comment'])) {
                    $addedMessageId = $cvsMessages->addSystemMessage('user_message', 'system', 'user', $eventId, $userId, $message['comment']);//, $message['mailto'], $message['subject']);
                    if (!$addedMessageId) {
                        unset($message);
                        continue;
                    }
                    $updateOldMessages = $cvsMessages->updateOldMessages($message['created_at'], ($message['updated_at'] ?? $GLOBALS['dateTimeHour']), $addedMessageId);

                    unset($message);
                } else {
                    $addedMessageId = $cvsMessages->addSystemMessage('system_message', 'system', 'user', $eventId, $userId, $message['message'], $message['mailto'], $message['subject']);
                    if (!$addedMessageId) {
                        $failedMessage++;
                        unset($message);
                        continue;
                    }

                    $updateOldMessages = $cvsMessages->updateOldMessages($message['created_at'], ($message['updated_at'] ?? $GLOBALS['dateTimeHour']), $addedMessageId);
                    if (!$updateOldMessages) {
                        $failedOldMessageUpdate++;
                    } else {
                        $oldMessageUpdate++;
                    }
                    $messageAdded++;
                    unset($message);
                }
            }

            //add event documents
            foreach ($event['documents'] as &$document) {
                $name = substr($document['file'], -50);
                $newPath = STORAGE_LOCATION . md5(uniqid()) . generateRandomString() . $name;
                while (file_exists($newPath)) {
                    $newPath = STORAGE_LOCATION . md5(uniqid()) . generateRandomString() . $name;
                }
                if (!copy(CODEBASE . 'old_data/' . $document['file'], $newPath)) {
                    $failedDoc++;
                    unset($document);
                    continue;
                }
                $docMoved = $cvsDocuments->addDoc($name, $newPath, 'event_doc', $userId, $eventId);

                if (!$docMoved) {
                    $failedDoc++;
                    unset($docMoved);
                    continue;
                }
                unset($document);
                unset($docMoved);
                $movedDoc++;
            }

            $addOldEventData = $cvsExtras->addOldEventData($userId, $eventId, $event);
            unset($addOldEventData);
            $eventCount++;
            unset($event);
        }
        unset($user);
    }
} catch (\Exception $e) {
    print('oops there is an error: ' . $e->getMessage());
    exit();
}

print("\n added user are {$count} and failed user are {$failedUser}, and add events are {$eventCount} and failed events are {$failedEvent}, and moved Document is {$movedDoc} and failed Document {$failedDoc} and added messages are {$messageAdded} and failed messages are {$failedMessage}, and added criteria {$addedCri} and failed criteria {$failedCri}, and event updated {$eventUpdate} and failed event update {$failedEventUpdate}, and user id update {$userIdUpdate} and failed user id update {$failedIdUpdate} \n");
exit();
