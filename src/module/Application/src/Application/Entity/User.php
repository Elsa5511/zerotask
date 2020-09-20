<?php

namespace Application\Entity;

use Sysco\Aurora\Doctrine\ORM\Entity;
use BjyAuthorize\Provider\Role\ProviderInterface;
use Doctrine\ORM\Mapping as ORM;
use ZfcUser\Entity\UserInterface;
use Sysco\Aurora\Stdlib\DateTime;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Application\Repository\UserRepository")
 *
 */
class User extends Entity implements UserInterface, ProviderInterface {

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=45, nullable=false)
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=45, nullable=true)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="email_private", type="string", length=45, nullable=true)
     */
    protected $emailPrivate;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=200, nullable=false)
     */
    protected $password;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=45, nullable=true)
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=45, nullable=true)
     */
    protected $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="employee_no", type="string", length=45, nullable=true)
     */
    protected $employeeNo;

    
    
    /**
     * TODO: Why is this stored in the database? Check if needed, if else, remove,
     * and change getDisplayName to calculate on the fly.
     * Might be used from the zfc user (getCurrentUser()) in controllers.
     * 
     * @var string
     *
     * @ORM\Column(name="display_name", type="string", length=45, nullable=true)
     */
    protected $displayName;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=150, nullable=true)
     */
    protected $address;

    /**
     * @var string
     *
     * @ORM\Column(name="zip", type="string", length=15, nullable=true)
     */
    protected $zip;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=50, nullable=true)
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(name="phone1", type="string", length=50, nullable=true)
     */
    protected $phoneNumberMobile;

    /**
     * @var string
     *
     * @ORM\Column(name="phone2", type="string", length=50, nullable=true)
     */
    protected $phoneNumberOther;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_add", type="datetime", nullable=true)
     */
    protected $dateAdd;

    /**
     * @var string
     *
     * @ORM\Column(name="security_key", type="string", nullable=true)
     */
    protected $securityKey;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_update", type="datetime", nullable=true)
     */
    protected $dateUpdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_last_login", type="datetime", nullable=true)
     */
    protected $dateLastLogin;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\Role")
     * @ORM\JoinTable(name="user_role_linker",
     *   joinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="role_id", referencedColumnName="role_id")
     *   }
     * )
     */
    protected $roles;

    /**
     * @ORM\ManyToMany(targetEntity = "Equipment\Entity\CompetenceAreaTaxonomy")
     * @ORM\JoinTable(name = "user_competance_area_linker",
     *   joinColumns = {
     *     @ORM\JoinColumn(name = "user_id", referencedColumnName = "user_id", nullable=true)
     *   },
     *   inverseJoinColumns =  {
     *     @ORM\JoinColumn(name = "competence_area_taxonomy_id", referencedColumnName="competence_area_taxonomy_id")
     *   }
     * )
     */
    protected $competenceAreas;

    /**
     * @var \Application\Entity\Language
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Language")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="language_id", referencedColumnName="language_id", nullable=true)
     * })
     */
    protected $languageId;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\User")
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="superior_id", referencedColumnName="user_id")
     * })
     */
    protected $superiorId;

    /**
     * @var \Application\Entity\Organization
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Organization")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="organization_id", referencedColumnName="organization_id")
     * })
     */
    protected $organizationId;

    /**
     * @var integer
     *
     * @ORM\Column(name="state", type="integer", nullable=false)
     */
    protected $state = \Application\Service\UserService::USER_STATE_ACTIVE;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\ApplicationDescription")
     * @ORM\JoinTable(name="user_application_linker",
     *      joinColumns={
     *          @ORM\JoinColumn(name="user_id", referencedColumnName="user_id"),
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="application_id", referencedColumnName="application_id")
     *      })
     */
    protected $accessibleApplications;
    protected $lastActivity = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_password_updated", type="datetime", nullable=true)
     */
    protected $datePasswordUpdated;

    /**
     * @var boolean
     *
     * @ORM\Column(name="organization_restriction_enabled", type="boolean", nullable=false)
     */
    protected $organizationRestrictionEnabled = false;

    /**
     * Constructor
     */
    public function __construct() {
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->competenceAreas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->accessibleApplications = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId() {
        return $this->userId;
    }

    public function setId($id) {
        $this->userId = (int) $id;
        return $this;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return void
     */
    public function setUserId($id) {
        $this->userId = (int) $id;
        return $this;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username) {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail() {
        return $this->email;
    }

    public function getEmailPrivate() {
        return $this->emailPrivate;
    }

    public function setEmailPrivate($emailPrivate) {
        $this->emailPrivate = $emailPrivate;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password) {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName) {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName() {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName) {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName() {
        return $this->lastName;
    }

    /**
     * Set displayName
     *
     * @param string $displayName
     * @return User
     */
    public function setDisplayName($displayName) {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * Get displayName
     *
     * @return string 
     */
    public function getDisplayName() {
        return $this->displayName;
    }

    /**
     * Set dateAdd
     *
     * @param \DateTime $dateAdd
     * @return User
     */
    public function setDateAdd($dateAdd) {
        $this->dateAdd = new DateTime($dateAdd);
        return $this;
    }

    /**
     * Get dateAdd
     *
     * @return \DateTime 
     */
    public function getDateAdd() {
        return $this->dateAdd;
    }

    /**
     * Set dateUpdate
     *
     * @param \DateTime $dateUpdate
     * @return User
     */
    public function setDateUpdate($dateUpdate) {
        $this->dateUpdate = new DateTime($dateUpdate);

        return $this;
    }

    /**
     * Get dateUpdate
     *
     * @return \DateTime 
     */
    public function getDateUpdate() {
        return $this->dateUpdate;
    }

    /**
     * Set dateLastLogin
     *
     * @param \DateTime $dateLastLogin
     * @return User
     */
    public function setDateLastLogin($dateLastLogin) {
        $this->dateLastLogin = new DateTime($dateLastLogin);

        return $this;
    }

    /**
     * Get dateLastLogin
     *
     * @return \DateTime 
     */
    public function getDateLastLogin() {
        return $this->dateLastLogin;
    }

    /**
     * Add role
     *
     * @param \Application\Entity\Role $role
     * @return User
     */
    public function addRole(\Application\Entity\Role $role) {
        $this->roles[$role->getRoleId()] = $role;

        return $this;
    }

    /**
     * Remove role
     *
     * @param \Application\Entity\Role $role
     */
    public function removeRole(\Application\Entity\Role $role) {
        $this->roles->removeElement($role);
    }

    /*
     * Check if a user has an specific role.
     * @param string $role
     * @return bool
     */

    public function hasRole($role) {
        return array_key_exists($role, $this->roles->toArray());
    }

    /**
     * Get role
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function _getRoles() {
        return $this->roles;
    }

    public function getRoles() {
        return $this->roles->getValues();
    }

    public function setRoles($roles) {
        $this->roles = $roles;
    }

    public function removeRoles($roles) {
        foreach($roles as $role) {
            $this->roles->removeElement($role);
        }
    }

    public function addRoles($roles) {
        foreach($roles as $role) {
            $this->roles->add($role);
        }
    }


    /**
     * Set language
     *
     * @param \Application\Entity\Language $languageId
     * @return User
     */
    public function setLanguageId($languageId = null) {
        $this->languageId = $languageId;

        return $this;
    }

    public function setLanguage(\Application\Entity\Language $languageId = null) {
        $this->languageId = $languageId;

        return $this;
    }

    /**
     * Get language
     *
     * @return \Application\Entity\Language 
     */
    public function getLanguageId() {
        return $this->languageId;
    }

    public function getLanguage() {
        return $this->languageId;
    }

    public function getSuperiorId() {
        return $this->superiorId;
    }

    public function setSuperiorId($superiorId) {
        $this->superiorId = $superiorId;
    }

    /**
     * Set organization
     *
     * @param \Application\Entity\Organization $organizationId
     * @return User
     */
    public function setOrganizationId($organizationId = null) {
        $this->organizationId = $organizationId;

        return $this;
    }

    /**
     * Get organization
     *
     * @return \Application\Entity\Organization
     */
    public function getOrganizationId() {
        return $this->organizationId;
    }

    public function getFirstOrganization() {
        return (!empty($this->organizationId)) ? $this->organizationId->getName() : '';
    }

    /**
     * Get state.
     * @return int
     */
    public function getState() {
        return $this->state;
    }

    public function getSecurityKey() {
        return $this->securityKey;
    }

    public function setSecurityKey($securityKey) {
        $this->securityKey = $securityKey;
    }

    /**
     * Set state.
     * @param int $state
     *
     * @return void
     */
    public function setState($state) {
        $this->state = (int) $state;
    }

    public function setLastActivity($time) {
        $this->lastActivity = new DateTime($time);
        return $this;
    }

    public function getLastActivity() {
        return $this->lastActivity;
    }

    public function getAddress() {
        return $this->address;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function getZip() {
        return $this->zip;
    }

    public function setZip($zip) {
        $this->zip = $zip;
    }

    public function getCity() {
        return $this->city;
    }

    public function setCity($city) {
        $this->city = $city;
    }

    public function getPhone1() {
        return $this->phoneNumberMobile;
    }

    public function setPhone1($phone1) {
        $this->phoneNumberMobile = $phone1;
    }

    public function getPhone2() {
        return $this->phoneNumberOther;
    }

    public function setPhone2($phone2) {
        $this->phoneNumberOther = $phone2;
    }

    public function getCompetenceAreas() {
        return $this->competenceAreas;
    }

    public function setCompetenceAreas($competenceAreas) {
        $this->competenceAreas = $competenceAreas;
    }

    public function removeCompetenceAreas($competenceAreas) {
        foreach($competenceAreas as $competenceArea) {
            $this->competenceAreas->removeElement($competenceArea);
        }
    }

    public function addCompetenceAreas($competenceAreas) {
        foreach($competenceAreas as $competenceArea) {
            $this->competenceAreas->add($competenceArea);
        }
    }

    public function getAccessibleApplications() {
        return $this->accessibleApplications;
    }

    public function setAccessibleApplications(\Doctrine\Common\Collections\Collection $accessibleApplications) {
        $this->accessibleApplications = $accessibleApplications;
    }

    public function removeAccessibleApplications($accessibleApplications) {
        foreach($accessibleApplications as $accessibleApplication) {
            $this->accessibleApplications->removeElement($accessibleApplication);
        }
    }

    public function addAccessibleApplications($accessibleApplications) {
        foreach($accessibleApplications as $accessibleApplication) {
            $this->accessibleApplications->add($accessibleApplication);
        }
    }

    public function getEmployeeNo() {
        return $this->employeeNo;
    }

    public function setEmployeeNo($employeeNo) {
        $this->employeeNo = $employeeNo;
    }

    /**
     * Set datePasswordUpdated
     *
     * @param \DateTime $datePasswordUpdated
     * @return User
     */
    public function setDatePasswordUpdated($datePasswordUpdated) {
        $this->datePasswordUpdated = new DateTime($datePasswordUpdated);

        return $this;
    }

    /**
     * Get dateAdd
     *
     * @return \DateTime
     */
    public function getDatePasswordUpdated() {
        return $this->datePasswordUpdated;
    }

    /**
     * @return bool
     */
    public function getOrganizationRestrictionEnabled() {
        return $this->organizationRestrictionEnabled;
    }

    /**
     * @param bool $organizationRestrictionEnabled
     */
    public function setOrganizationRestrictionEnabled($organizationRestrictionEnabled) {
        $this->organizationRestrictionEnabled = $organizationRestrictionEnabled;
    }
        
    public function __toString() {
        return $this->getDisplayName();
    }

}
