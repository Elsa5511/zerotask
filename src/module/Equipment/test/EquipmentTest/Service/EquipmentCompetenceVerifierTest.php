<?php

namespace EquipmentTest\Service;

use EquipmentTest\BaseSetUp;
use Equipment\Entity\EquipmentTaxonomy;
use Equipment\Entity\CompetenceAreaTaxonomy;
use Equipment\Entity\Equipment;
use Application\Entity\User;

class EquipmentCompetenceVerifierTest extends BaseSetUp {

    public function testUserShouldHaveCompetenceWithEquipment_WhenEquipmentHasCategory_WithSameCompetenceAsUser() {
        // Given        
        $equipmentTaxonomyWithFishingCompetence = new EquipmentTaxonomy();
        $fishingCompetence = new CompetenceAreaTaxonomy();
        $fishingCompetence->setName('Fishing');
        $equipmentTaxonomyWithFishingCompetence->setCompetenceAreaTaxonomy($fishingCompetence);
        $equipment = new Equipment();
        $equipment->getEquipmentTaxonomy()->add($equipmentTaxonomyWithFishingCompetence);
        $userWithFishingCompetence = new User();
        $userWithFishingCompetence->getCompetenceAreas()->add($fishingCompetence);
        $equipmentCompetenceVerifier = new \Equipment\Service\EquipmentCompetenceVerifier();

        // When
        $userHasCompetenceWithEquipment = $equipmentCompetenceVerifier->userHasCompetenceWithEquipment($userWithFishingCompetence, $equipment);

        // Then
        $this->assertTrue($userHasCompetenceWithEquipment, 'User has competence with equipment when equipment has taxonomy with same competance as user');
    }

    public function testUserShouldHaveCompetenceWithEquipment_WhenEquipmentHasCategory_WithNoCompetenceRequirement_AndNoParent() {
        // Given
        $equipmentTaxonomy = new EquipmentTaxonomy();
        $equipment = new Equipment();
        $equipment->getEquipmentTaxonomy()->add($equipmentTaxonomy);
        $incompetentUser = new User();
        $equipmentCompetenceVerifier = new \Equipment\Service\EquipmentCompetenceVerifier();

        // When
        $userHasCompetenceWithEquipment = $equipmentCompetenceVerifier->userHasCompetenceWithEquipment($incompetentUser, $equipment);

        // Then
        $this->assertTrue($userHasCompetenceWithEquipment, 'User has compentence with equipment when equipment has taxonomy with no competence requirement and no parents.');
    }

    public function testUserShouldHaveCompetenceWithEquipment_WhenEquipmentHasSomeParentCategory_WithSameCompetenceAsUser() {
        // Given
        $parentEquipmentTaxonomy = new EquipmentTaxonomy();
        $fishingCompetence = new CompetenceAreaTaxonomy();
        $fishingCompetence->setName('Fishing');
        $parentEquipmentTaxonomy->setCompetenceAreaTaxonomy($fishingCompetence);
        $childEquipmentTaxonomy = new EquipmentTaxonomy();
        $childEquipmentTaxonomy->setParent($parentEquipmentTaxonomy);
        $equipment = new Equipment();
        $equipment->getEquipmentTaxonomy()->add($childEquipmentTaxonomy);

        $userWithFishingCompetence = new User();
        $userWithFishingCompetence->getCompetenceAreas()->add($fishingCompetence);
        $equipmentCompetenceVerifier = new \Equipment\Service\EquipmentCompetenceVerifier();

        // When
        $userHasCompetenceWithEquipment = $equipmentCompetenceVerifier->userHasCompetenceWithEquipment($userWithFishingCompetence, $equipment);

        // Then
        $this->assertTrue($userHasCompetenceWithEquipment, 'User has compentence with equipment when equipment has some parent taxonomy with same competence as user.');
    }

    public function testUserShouldHaveCompetenceWithEquipment_WhenEquipmentHasAtLeastOneTaxonomy_WithSameCompetenceAsUser() {
        // Given
        $equipmentTaxonomyWithFishingCompetence = new EquipmentTaxonomy();
        $fishingCompetence = new CompetenceAreaTaxonomy();
        $fishingCompetence->setName('Fishing');
        $equipmentTaxonomyWithFishingCompetence->setCompetenceAreaTaxonomy($fishingCompetence);

        $equipmentTaxonomyWithEngineeringCompetence = new EquipmentTaxonomy();
        $engineeringCompetence = new CompetenceAreaTaxonomy();
        $engineeringCompetence->setName('Engineering');
        $equipmentTaxonomyWithEngineeringCompetence->setCompetenceAreaTaxonomy($engineeringCompetence);

        $equipment = new Equipment();
        $equipment->getEquipmentTaxonomy()->add($equipmentTaxonomyWithEngineeringCompetence);
        $equipment->getEquipmentTaxonomy()->add($equipmentTaxonomyWithFishingCompetence);

        $userWithFishingCompetence = new User();
        $userWithFishingCompetence->getCompetenceAreas()->add($fishingCompetence);
        $equipmentCompetenceVerifier = new \Equipment\Service\EquipmentCompetenceVerifier();

        // When
        $userHasCompetenceWithEquipment = $equipmentCompetenceVerifier->userHasCompetenceWithEquipment($userWithFishingCompetence, $equipment);

        // Then
        $this->assertTrue($userHasCompetenceWithEquipment, 'User has compentence with equipment when equipment has at least one taxonomy with same competance.');
    }

    public function testUserShouldNotHaveCompetanceWithEquipment_WhenEquipmentHasTaxonomy_WithOtherCompetenceThanUser() {
        // Given
        $equipmentTaxonomyWithFishingCompetence = new EquipmentTaxonomy();
        $fishingCompetence = new CompetenceAreaTaxonomy();
        $fishingCompetence->setName('Fishing');
        $equipmentTaxonomyWithFishingCompetence->setCompetenceAreaTaxonomy($fishingCompetence);

        $equipment = new Equipment();
        $equipment->getEquipmentTaxonomy()->add($equipmentTaxonomyWithFishingCompetence);

        $incompetentUser = new User();
        $equipmentCompetenceVerifier = new \Equipment\Service\EquipmentCompetenceVerifier();

        // When
        $userHasCompetenceWithEquipment = $equipmentCompetenceVerifier->userHasCompetenceWithEquipment($incompetentUser, $equipment);

        // Then
        $this->assertFalse($userHasCompetenceWithEquipment, 'User does not have compentence with equipment when equipment has taxonomy with other competance.');
    }

    public function testUserShouldNotHaveCompetenceWithEquipment_WhenEquipmentHasSomeParentCategory_WithOtherCompetenceAsUser() {
        // ...and the child equipment taxonomy does not have any competence requirements.

        // Given
        $parentEquipmentTaxonomy = new EquipmentTaxonomy();
        $engineeringCompetence = new CompetenceAreaTaxonomy();
        $engineeringCompetence->setName('Engineering');
        $parentEquipmentTaxonomy->setCompetenceAreaTaxonomy($engineeringCompetence);
        $childEquipmentTaxonomy = new EquipmentTaxonomy();
        $childEquipmentTaxonomy->setParent($parentEquipmentTaxonomy);
        $equipment = new Equipment();
        $equipment->getEquipmentTaxonomy()->add($childEquipmentTaxonomy);

        $incompetentUser = new User();
        $equipmentCompetenceVerifier = new \Equipment\Service\EquipmentCompetenceVerifier();

        // When
        $userHasCompetenceWithEquipment = $equipmentCompetenceVerifier->userHasCompetenceWithEquipment($incompetentUser, $equipment);

        // Then
        $this->assertFalse($userHasCompetenceWithEquipment, 'User does not have compentence with equipment when equipment has some parent taxonomy with other competence as user.');
    }

}
