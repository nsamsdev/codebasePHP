<?php
require __DIR__ . '/../vendor/autoload.php';
define('CODEBASE', true);

require __DIR__ . '/../configs/configs-cli.php';
require __DIR__ . '/../helpers/helpers.php';

use CodeBase\CustomEmailer as Mailer;
use CodeBase\Models\CustomQuery;

$customQuery = new CustomQuery();

$orgUsers = $customQuery->getCertsExpiry();

// var_dump($orgUsers);die;

foreach ($orgUsers as $org) {
    if (!empty($customQuery->checkCertExpiryReminderSent($org['organisation_id']))) {
        continue;
    }

    $mailer = new Mailer();
    $mailer->setFrom('d');
    $mailer->forceFrom(EC_EMAIL);
    $mailer->setSubject('Important – Ethical MedTech Trusted Partner logo will expire in 2 months');
    $mailer->setTo($org['email']);
    $message = require __DIR__ . '/../app/messages/leader_notification_for_logo_or_cert_about_to_end.php';
    $mailer->setBody($message);
    $mailer->send();

    $customQuery->addCertsReminderSent($org['organisation_id']);
}
