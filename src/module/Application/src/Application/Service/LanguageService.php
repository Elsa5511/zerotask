<?php

namespace Application\Service;

use Sysco\Aurora\Doctrine\ORM\Service;
use Sysco\Aurora\Stdlib\DateTime;

class LanguageService extends Service
{
    /** English Language */

    const USER_LANGUAGE_ID_DEFAULT = 1;

    private function getEntityRepository() {
        return $this->getEntityManager()->getRepository('Application\Entity\Language');
    }

    /**
     * Return a list of all active users
     * (active users have a state value equal to 1)
     */
    public function fetchAll()
    {
        return $this->getEntityRepository()->findAll();
    }
    
    public function getLanguageById($languageId)
    {
        return $this->getEntityRepository()->find($languageId);
    }

    public function persistData($language)
    {
        $now = new DateTime('NOW');

        $isNewLanguage = is_null($language->getId());

        if ($isNewLanguage) {
            $language->setDateAdd($now);
        }

        $language->setDateUpdate($now);

        parent::persist($language);
        return $language->getLanguageId();
    }

    /**
     * Remove User-Language Relationships before delete a language
     * and setting a language default for users
     *
     */
    public function removeUserRelationships($languageId)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->update('Application\Entity\User', 'u')
                ->set('u.languageId', '?1')
                ->where('u.languageId = :language_id')
                ->setParameter('language_id', $languageId)
                ->setParameter(1, self::USER_LANGUAGE_ID_DEFAULT);
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

}
