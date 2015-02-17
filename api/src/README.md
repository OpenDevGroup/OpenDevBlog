# OpenDev

This is the main area for custom code.

## HumanDate

    $humanDate = new \OpenDev\HumanDate();
    $now = new DateTime('1991-05-17 23:59:59 UTC');
    $dateTime = new DateTime('1991-05-18 00:00:00 UTC');
    $result = $humanDate->parse($datetime, $now); // Tomorrow
