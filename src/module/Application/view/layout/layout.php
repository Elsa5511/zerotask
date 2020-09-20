<?php

function getLanguageIsoCode($userIdentity) {
    if ($userIdentity && $userIdentity->getLanguage() !== null) {
        return $userIdentity->getLanguage()->getIsocode();
    }
    else {
        return 'en';
    }
}
