<?php
require __DIR__ . '/../vendor/autoload.php';
define('CODEBASE', true);

require __DIR__ . '/../configs/configs-cli.php';

use CodeBase\CustomEmailer as Mailer;
use CodeBase\Models\Users;
use CodeBase\Models\UserDetails;
use CodeBase\Models\CustomQuery;
use CodeBase\Models\Extras;

$customQuery = new CustomQuery();
$usersModel = new Users();
$usersDetails = new UserDetails();
$extras = new Extras();
$noTestUsers = $customQuery->getNoTestUsers();

foreach ($noTestUsers as $user) {
    if ($extras->alreadyNotified($user['id'])) {
        continue;
    }

    if ($user['lvl'] == 2) {
        if (!$extras->checkIsTakingPart($user['id'])) {
            continue;
        }
    }
    $mail = new Mailer();
    $mail->setFrom('00');
    $mail->forceFrom(EC_EMAIL);
    $mail->setSubject('Important - Ethical Charter Self-Assessment Reminder');
    $mail->setBody("Dear {$user['first_name']} <br /><br /> This is a reminder to let you know that you have not yet taken the self assessment form via the ethical charter system.<br /><br />Ethical Charter System");
    $mail->setTo($user['email']);
    $mail->send();
    unset($mail);
    $extras->markUserReminderSent($user['id']);
}

print("Reminders Sent\n");
