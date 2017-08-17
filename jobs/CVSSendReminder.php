<?php
require __DIR__ . '/../vendor/autoload.php';
define('CODEBASE', __DIR__ . '/../');

require __DIR__ . '/../configs/configs-cli.php';
require __DIR__ . '/../helpers/helpers.php';

use CodeBase\Managers\CVSManager;
use CodeBase\Models\CVSEvents;
use CodeBase\Models\CVSUsers;
use CodeBase\Models\CVSEventsStatuses;
use CodeBase\CustomEmailer as Mailer;

$events = new CVSEvents();
$customHelper = (new CVSManager())->getCustomHelper();
$toRemindEvents = $events->getReminderEvents();
$users = new CVSUsers();
$statuses = new CVSEventsStatuses();
$reminderCount = 0;
foreach ($toRemindEvents as $specialId) {
    $event = $customHelper->getEventDetails($specialId['special_id']);
    $user = $users->getUserDetailsById($event['user_id']);
    $status = $statuses->getEventStatus($event['status_id']);


    $reminderDate = (empty($event['reminder_date']) || is_null($event['reminder_date'])) ? 'now' : $event['reminder_date'];
    $nonCompliantDate = (empty($event['non_compliant_date']) || is_null($event['non_compliant_date'])) ? 'now' : $event['non_compliant_date'];
    $assessmentNotifcationDate = (empty($event['assessment_notification_date']) || is_null($event['assessment_notification_date'])) ? $event['created_at'] : $event['assessment_notification_date'];
    $reminderDeadline = (empty($event['reminder_deadline']) || is_null($event['reminder_deadline'])) ? 'now' : $event['reminder_deadline'];

    $dateTime1 = new \DateTime($reminderDate);
    $dateTime2 = new \DateTime($nonCompliantDate);
    $dateTime3 = new \DateTime($assessmentNotifcationDate);
    $dateTime4 = new \DateTime($reminderDeadline);
    $dateTime5 = new \DateTime($event['created_at']);
    $dateTime6 = new \DateTime($user['created_at']);
    $dateTime7 = new \DateTime($event['start_date']);
    $dateTime8 = new \DateTime($event['end_date']);
    $event['reminder_date'] = $dateTime1->format('d-m-Y');
    $event['non_compliant_date'] = $dateTime2->format('d-m-Y');
    $event['assessment_notification_date'] = $dateTime3->format('d-m-Y');
    $event['reminder_deadline'] = $dateTime4->format('d-m-Y');
    $event['created_at'] = $dateTime5->format('d-m-Y');
    $user['created_at'] = $dateTime6->format('d-m-Y');
    $event['start_date'] = $dateTime7->format('d-m-Y');
    $event['end_date'] = $dateTime8->format('d-m-Y');

    $message = require(MESSAGES_DIR . 'reminder_if_is_none_compliant_criteria_organiser.php');
    $subject = 'EthicalMedTech - CVS - Non Compliant Criteria Reminder';


    $mail = new Mailer();
    $mail->setFrom('00');
    $mail->forceFrom(CVS_EMAIL);
    $mail->setSubject($subject);
    $mail->setBody($message);
    if (!empty($event['organisor_email'])) {
        $mail->setTo($event['organisor_email']);
    } else {
        $mail->setTo($user['email']);
    }
    $mail->send();


    $events->updateReminderSent($event['id']);
    unset($mail);
    unset($user);
    unset($event);
    unset($status);


    $reminderCount++;
}


print("\n Reminders Sent ({$reminderCount}), Done\n");
exit();
