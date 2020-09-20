<?php

namespace Equipment\Service;

class EquipmentCompetenceVerifier {

    public function userHasCompetenceWithEquipment($user, $equipment) {
        $userCompetenceAreas = $user->getCompetenceAreas();
        $equipmentTaxonomies = $equipment->getEquipmentTaxonomy();

        foreach ($equipmentTaxonomies->toArray() as $equipmentTaxonomy) {
            $firstEquipmentTaxonomyWithCompetenceRequirement = $this->getFirstParentOrSelfWithCompetenceRequirement($equipmentTaxonomy);

            if ($firstEquipmentTaxonomyWithCompetenceRequirement === null || !$this->hasCompetenceRequirement($firstEquipmentTaxonomyWithCompetenceRequirement)) {
                return true; // No competence required
            } else if ($userCompetenceAreas->contains($firstEquipmentTaxonomyWithCompetenceRequirement->getCompetenceAreaTaxonomy())) {
                return true;
            }
        }
        return false;
    }

    private function getFirstParentOrSelfWithCompetenceRequirement($equipmentTaxonomy) {
        if ($equipmentTaxonomy === null ||
                $equipmentTaxonomy->getCompetenceAreaTaxonomy() !== null) {
            return $equipmentTaxonomy;
        } else {
            return $this->getFirstParentOrSelfWithCompetenceRequirement($equipmentTaxonomy->getParent());
        }
    }

    private function hasCompetenceRequirement($equipmentTaxonomy) {
        return $equipmentTaxonomy->getCompetenceAreaTaxonomy() !== null;
    }

}
