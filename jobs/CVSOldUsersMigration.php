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

$oldUsers = $cvsExtras->getOldUsers(0, 0);

//print ("\nthe total of found users is\n" . count($oldUsers) . "\n");
//exit();

$userInsert = 0;
$userInsertFailed = 0;
$userUpdate = 0;
$userUpdateFailed = 0;
$dump = 0;
$dumpFailed = 0;
$failed = 0;
foreach ($oldUsers as &$user) {
    $password = 'mte_new' . random_int(1, 100);
    try {
        /*
        $userId = $cvsFormManager->cvsRegister($user['first_name'], $user['last_name'], $user['email_address'], $user['username'], $password,
            $password, ((empty($user['type_other']))? $user['user_type'] : $user['type_other']), $user['name'], $user['telephone'], $user['address'], $user['address2'],
            $user['zip'], $user['country'], $user['job_title'], $user['city'], true);
        */

        $userId = $cvsUsers->addCustomUsersOld($user);
    } catch (\Throwable $e) {
        $failedUser = $cvsExtras->addFailedUsers($user);
        if ($failedUser) {
            $failed++;
        }
    }

    if (!$userId) {
        unset($user);
        $userInsertFailed++;
        continue;
    }

    /*
    $userInsert++;

    $updateOldUser = $cvsUsers->updateOldIdAndDates($user['id'], $userId, $user['created_at'], $user['updated_at']);

    if (!$updateOldUser) {
        unset($user);
        $userUpdateFailed++;
        continue;
    }


    $userUpdate++;
 */
    $addUserDump = $cvsExtras->dumpOldUserData($userId, $user);

    if (!$addUserDump) {
        unset($user);
        $dumpFailed++;
        continue;
    }

    $dump++;
    unset($user);
}


print("Number of inserted users is {$userInsert}\n Number of failed inserts is {$userInsertFailed}\n number of updated user is {$userUpdate}\n number of failed updates is {$userUpdateFailed}\n dumps are {$dump}\n failed dumps are {$dumpFailed}\n failed is {$failed}\n");
exit();
