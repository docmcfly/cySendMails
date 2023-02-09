<?php
namespace Cylancer\CySendMails\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 *
 * This file is part of the "cy_send_mails" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2023 C. Gogolin <service@cylancer.net>
 *
 * @package Cylancer\CySendMails\Domain\Model
 * 
 */
class FrontendUserGroup extends AbstractEntity
{

    /**
     *
     * @var ObjectStorage<FrontendUserGroup>
     */
    protected $receiverGroup = null;

    /**
     *
     * @var string
     */
    protected $title = '';

    /**
     *
     * @var string
     */
    protected $receiverGroupName = '';

    /**
     *
     * @var ObjectStorage<FrontendUserGroup>
     */
    protected $subgroup = null;

    /**
     * __construct
     */
    public function __construct()
    {
        // Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->receiverGroup = new ObjectStorage();
        $this->subgroup = new ObjectStorage();
    }

    /**
     * Adds a ReceiverGroup
     *
     * @param FrontendUserGroup $receiverGroup
     * @return void
     */
    public function addReceiverGroup(FrontendUserGroup $receiverGroup)
    {
        $this->receiverGroup->attach($receiverGroup);
    }

    /**
     * Removes a ReceiverGroup
     *
     * @param FrontendUserGroup $receiverGroupToRemove
     *            The ReceiverGroup to be removed
     * @return void
     */
    public function removeReceiverGroup(FrontendUserGroup $receiverGroupToRemove)
    {
        $this->receiverGroup->detach($receiverGroupToRemove);
    }

    /**
     * Returns the receiverGroup
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<FrontendUserGroup> $receiverGroup
     */
    public function getReceiverGroup()
    {
        return $this->receiverGroup;
    }

    /**
     * Sets the receiverGroup
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<FrontendUserGroup> $receiverGroup
     * @return void
     */
    public function setReceiverGroup(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $receiverGroup)
    {
        $this->receiverGroup = $receiverGroup;
    }

    /**
     * Sets the title value
     *
     * @param string $title
     */
    public function setTitle(String $title): void
    {
        $this->title = $title;
    }

    /**
     * Returns the title value
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Sets the receiver group name value
     *
     * @param string $receiverGroupName
     */
    public function setReceiverGroupName(String $receiverGroupName): void
    {
        $this->receiverGroupName = $receiverGroupName;
    }

    /**
     * Returns the receiver group name value
     *
     * @return string
     */
    public function getReceiverGroupName(): string
    {
        return $this->receiverGroupName;
    }

    /**
     * Sets the subgroups.
     * Keep in mind that the property is called "subgroup"
     * although it can hold several subgroups.
     *
     * @param ObjectStorage<FrontendUserGroup> $subgroup
     *            An object storage containing the subgroups to add
     */
    public function setSubgroup(ObjectStorage $subgroup)
    {
        $this->subgroup = $subgroup;
    }

    /**
     * Adds a subgroup to the frontend user
     *
     * @param FrontendUserGroup $subgroup
     */
    public function addSubgroup(FrontendUserGroup $subgroup)
    {
        $this->subgroup->attach($subgroup);
    }

    /**
     * Removes a subgroup from the frontend user group
     *
     * @param FrontendUserGroup $subgroup
     */
    public function removeSubgroup(FrontendUserGroup $subgroup)
    {
        $this->subgroup->detach($subgroup);
    }

    /**
     * Returns the subgroups.
     * Keep in mind that the property is called "subgroup"
     * although it can hold several subgroups.
     *
     * @return ObjectStorage<FrontendUserGroup> An object storage containing the subgroups
     */
    public function getSubgroup()
    {
        return $this->subgroup;
    }
}