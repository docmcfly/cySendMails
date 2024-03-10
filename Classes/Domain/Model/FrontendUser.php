<?php
/**
 *
 * This file is part of the "cy_send_mails" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2024 C. Gogolin <service@cylancer.net>
 *
 * @package Cylancer\CySendMails\Domain\Model
 * 
 */
namespace Cylancer\CySendMails\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * A Frontend User
 */
class FrontendUser extends AbstractEntity
{
    
    /**
     * @var ObjectStorage<FrontendUserGroup>
     */
    protected $usergroup;
    
    /**
     * @var string
     */
    protected $name = '';
    
    /**
     * @var string
     */
    protected $firstName = '';
    
    /**
     * @var string
     */
    protected $lastName = '';
    
    
    /**
     * @var string
     */
    protected $email = '';
    
    /**
     * Called again with initialize object, as fetching an entity from the DB does not use the constructor
     */
    public function initializeObject()
    {
        $this->usergroup = $this->usergroup ?? new ObjectStorage();
    }
    
    /**
     * Sets the usergroups. Keep in mind that the property is called "usergroup"
     * although it can hold several usergroups.
     *
     * @param ObjectStorage<FrontendUserGroup> $usergroup
     */
    public function setUsergroup(ObjectStorage $usergroup)
    {
        $this->usergroup = $usergroup;
    }
    
    /**
     * Adds a usergroup to the frontend user
     *
     * @param FrontendUserGroup $usergroup
     */
    public function addUsergroup(FrontendUserGroup $usergroup)
    {
        $this->usergroup->attach($usergroup);
    }
    
    /**
     * Removes a usergroup from the frontend user
     *
     * @param FrontendUserGroup $usergroup
     */
    public function removeUsergroup(FrontendUserGroup $usergroup)
    {
        $this->usergroup->detach($usergroup);
    }
    
    /**
     * Returns the usergroups. Keep in mind that the property is called "usergroup"
     * although it can hold several usergroups.
     *
     * @return ObjectStorage<FrontendUserGroup> An object storage containing the usergroup
     */
    public function getUsergroup()
    {
        return $this->usergroup;
    }
    
    /**
     * Sets the name value
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    
    /**
     * Returns the name value
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Sets the firstName value
     *
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }
    
    /**
     * Returns the firstName value
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }
    
    /**
     * Sets the lastName value
     *
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }
    
    /**
     * Returns the lastName value
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }
     
    /**
     * Sets the email value
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }
    
    /**
     * Returns the email value
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
 
}
