<?php

namespace Documentation\Service;

use Sysco\Aurora\Doctrine\ORM\Service;

class HtmlContentService extends Service
{

    public function saveHtmlContent($htmlContentEntity, $htmlContent)
    {
        $htmlContentEntity->setHtmlContent($htmlContent);
        $htmlContentEntity->setDateUpdate('NOW');
        $this->persist($htmlContentEntity);

        $namespace = "success";
        $message = $this->translate('Content saved successfully');

        return array(
            'namespace' => $namespace,
            'message' => $message
        );
    }


    public function getHtmlContent($criteria = array())
    {
        return $this->getEntityRepository()->findOneBy($criteria);
    }

    public function searchByWords($words, $ownerFieldname, $owner)
    {
        $qb = $this->getEntityRepository()->createQueryBuilder('h');
        return $qb->andWhere('h.htmlContent LIKE :words')
            ->andWhere($qb->expr()->in('h.'.$ownerFieldname, '?1'))
            ->setParameter('words', "%$words%")
            ->setParameter(1, $owner)
            ->orderBy("h.$ownerFieldname")
            ->getQuery()
            ->getResult();
    }

    private function getEntityRepository()
    {
        return $this->htmlContentRepository;
    }

}