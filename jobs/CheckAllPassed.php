<?php
require __DIR__ . '/../vendor/autoload.php';
define('CODEBASE', true);

require __DIR__ . '/../configs/configs-cli.php';
require __DIR__ . '/../helpers/helpers.php';

use CodeBase\CustomEmailer as Mailer;
use CodeBase\Models\CustomQuery;

$customQuery = new CustomQuery();

$passedUsers = $customQuery->getPassedUsers();
//$participentLeaders = $customQuery->getParticipentLeaders();
//$groupedUsers = groupBy($passedUsers, 'organisation_name');
//$finalList = groupByInner($groupedUsers, $participentLeaders, 'organisation_name');
$check = 0;

foreach ($passedUsers as $leader) {
    if ($customQuery->alreadyNotifiedOrganisation($leader['organisation_id'])) {
        continue;
    }
    //$leader = $organisation[0];
    $mail = new Mailer();
    $mail->setFrom('go');
    $mail->forceFrom(EC_EMAIL);
    $mail->setTo($leader['email']);
    $mail->setSubject('All Members Have Passed Ethical Charter Assessment');
    $mail->setBody("Dear {$leader['first_name']} <br /><br /> This is to notify you that all of the registered members into the Ethical Charter System have passed the
        self assessment and you can now generate your certificate<br /><br />Ethical Charter System");
    $mail->send();
    $customQuery->addNotifiedOrganisation($organisation[0]['organisation_id']);
    unset($mail);
    $check++;
}
 
print("\nCheck Completed = {$check}\n");
