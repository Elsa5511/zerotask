<?php

namespace Equipment\Repository;

use Acl\Repository\EntityRepository;
use Equipment\Service\EquipmentService;

class Equipment extends EntityRepository {

    public function deleteMany($equipmentIds) {
        $dqlEquipment = 'DELETE FROM Equipment\Entity\Equipment e  
            WHERE e.equipmentId IN (' . implode(',', $equipmentIds) . ')';

        $queryEquipment = $this->getEntityManager()->createQuery($dqlEquipment);
        $queryEquipment->getResult();
    }

    public function excludeEquipment($equipmentId = 0) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('e')
                ->from($this->getEntityName(), 'e')
                ->andWhere($qb->expr()->not($qb->expr()->in('e.equipmentId', $equipmentId)))
                ->andWhere(sprintf("e.application = '%s'", $this->getApplication()))
                ->andWhere("e.status = 'active'");
        return $qb->getQuery()->getResult();
    }

    /**
     * 
     * @param integer $equipmentId
     * @param type $translator
     * @return type
     */
    public function getEntitiesRelated($equipmentId, $translator) {
        $equipmentInstances = $this->getEquipmentInstancesRelated($equipmentId, $translator);

        $attachments = $this->getAttachmentsRelated($equipmentId, $translator);

        $result = array_merge($equipmentInstances, $attachments);
        return $result;
    }

    public function getEquipmentsForEveryApplication() {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder
                ->select('e')
                ->from('Equipment\Entity\Equipment', 'e')
                ->andWhere('e.status = :status')
                ->setParameter('status', 'active');
        return $queryBuilder->getQuery()->getResult();
    }

    public function equipmentHasAttachments($equipmentId) {
        return $this->equipmentHasRelation($equipmentId, 'Equipment\Entity\EquipmentAttachment');
    }

    public function equipmentHasInstances($equipmentId) {
        return $this->equipmentHasRelation($equipmentId, 'Equipment\Entity\EquipmentInstance');
    }

    public function equipmentHasDocumentation($equipmentId) {
        return $this->equipmentHasRelation($equipmentId, 'Documentation\Entity\DocumentationSection');
    }

    public function equipmentHasTraining($equipmentId) {
        return $this->equipmentHasRelation($equipmentId, 'Training\Entity\TrainingSection');
    }

    public function equipmentHasCertification($equipmentId) {
        return $this->equipmentHasRelation($equipmentId, 'Certification\Entity\Certification');
    }

    public function equipmentHasExercises($equipmentId) {
        return $this->equipmentHasRelation($equipmentId, 'Quiz\Entity\Exercise');
    }

    public function equipmentHasBestPractices($equipmentId) {
        return $this->equipmentHasRelation($equipmentId, 'BestPractice\Entity\BestPractice');
    }

    public function equipmentHasRelation($equipmentId, $relatedEntity) {
        $hasRelationDql = "SELECT COUNT(relatedEntity) FROM " . $relatedEntity . " relatedEntity "
                . "WHERE relatedEntity.equipment = (:equipmentId)";
        $query = $this->getEntityManager()->createQuery($hasRelationDql);
        $query->setParameter('equipmentId', $equipmentId);
        return $query->getSingleScalarResult() > 0;
    }

    private function getEquipmentInstancesRelated($equipmentId, $translator) {
        $equipmentInstanceText = $translator->translate("Equipment instance");
        $serialNumberText = $translator->translate("Serial number");

        $dqlForEquipmentInstance = "SELECT CONCAT('" . $equipmentInstanceText . ": ', eq.serialNumber,' (" . $serialNumberText . ")') AS "
                . EquipmentService::ALIAS_KEY_RELATIONSHIPS . " 
                    FROM Equipment\Entity\EquipmentInstance eq
                    WHERE eq.equipment = (:equipmentId)";
        $query = $this->getEntityManager()->createQuery($dqlForEquipmentInstance);
        $query->setParameter('equipmentId', $equipmentId);
        return $query->getResult();
    }

    private function getAttachmentsRelated($equipmentId, $translator) {
        $attachmentText = $translator->translate("Attachment");

        $dqlForAttachment = "SELECT CONCAT('" . $attachmentText . ": ', eia.file) AS " . EquipmentService::ALIAS_KEY_RELATIONSHIPS . "
                    FROM Equipment\Entity\EquipmentAttachment eia
                    WHERE eia.equipment = (:equipmentId)";

        $query = $this->getEntityManager()->createQuery($dqlForAttachment);
        $query->setParameter('equipmentId', $equipmentId);
        return $query->getResult();
    }

    public function getEquipmentSearch($criteria) {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('e')
                ->from($this->getEntityName(), 'e')
                ->andWhere("e.status = 'active'")
                ->andWhere($qb->expr()->eq('e.application', ':application'))
                ->orderBy('e.title', 'ASC')
                ->setParameter('application', $this->getApplication());

        /* Equipment taxonomies */
        if (isset($criteria['taxonomies'])) {
            foreach ($criteria['taxonomies'] as $key => $value) {
                if (is_array($value)) {
                    $qb->leftJoin('e.equipmentTaxonomy', $key)
                            ->andWhere($qb->expr()->in($key . '.equipmentTaxonomyId', ':' . $key))
                            ->setParameter($key, $value);
                }
            }
        }

        /* Equipment attributes equal comparator */
        if ($criteria['attributes_equal'] !== '') {
            $qb->leftJoin(
                'LadocDocumentation\Entity\LadocDocumentation',
                'ld',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'e.equipmentId = ld.equipment'
            );
            $qb->leftJoin(
                'LadocDocumentation\Entity\LoadBasicInformation',
                'lbi',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'ld.id = lbi.ladocDocumentation'
            );

            foreach ($criteria['attributes_equal'] as $key => $value) {
                if (!empty($value)) {
                    if(strtolower($this->getApplication()) == "ladoc" && $key == "nsn") {
                        $values = explode(',', $value);
                        $ors = $qb->expr()->orX();
                        foreach ($values as $v) {
                            $val = trim($v);
                            $ors->add($qb->expr()->eq('e.nsn', "'$val'"));
                            $ors->add($qb->expr()->eq('lbi.equivalentModels', "'$val'"));
                        }
                        $qb->andWhere($ors);
                    } else {
                        $qb->andWhere($qb->expr()->eq('e.' . $key, ':' . $key))->setParameter($key , $value);
                    }
                }
            }
        }

        /* Equipment attributes */
        if (isset($criteria['attributes'])) {
            foreach ($criteria['attributes'] as $key => $value) {
                if (is_array($value)) {
                    $qb->andWhere($qb->expr()->in('e.' . $key, ':' . $key))->setParameter($key, $value);
                } elseif (!empty($value)) {
                    $qb->andWhere($qb->expr()->like('e.' . $key, ':' . $key))->setParameter($key, $value);
                }
            }
        }

        return $qb->getQuery()->getResult();
    }

}
