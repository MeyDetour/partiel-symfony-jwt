<?php

namespace App\Service;

use App\Entity\Event;

class ValidatorService
{
    static function isValidEvent(Event $event): bool
    {
        $now = new \DateTime();
        if ($event->getStartDate() > $now and $event->getState() == "onSchedule") {
            return true;
        }
        return false;
    }

    static function isAuthorizedToSeePrivateEvent($user, Event $event): bool
    {

        $profile = $user->getProfile();
        if ($profile == $event->getOrganisator() or !$event->isProfileInAdministrators($profile)) {
            return true;
        }



        if (!$event->isPublic() and !$profile->isEventInEventsOfUser($event) and !$profile->isEventInInvited($event)) {

            return false;

        }

        return true;
    }
}