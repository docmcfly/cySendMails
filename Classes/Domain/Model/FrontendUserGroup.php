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
 * (c) 2025 C. Gogolin <service@cylancer.net>
 *
 */
class FrontendUserGroup extends AbstractEntity
{

    /** @var ObjectStorage<FrontendUserGroup> */
    protected $receiverGroup = null;

    protected ?string $title = '';

    protected ?string $receiverGroupName = '';

    /**  @var ObjectStorage<FrontendUserGroup> */
    protected $subgroup = null;

    public function __construct()
    {
        // Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    protected function initStorageObjects()
    {
        $this->receiverGroup = new ObjectStorage();
        $this->subgroup = new ObjectStorage();
    }

    public function addReceiverGroup(FrontendUserGroup $receiverGroup): void
    {
        $this->receiverGroup->attach($receiverGroup);
    }

    public function removeReceiverGroup(FrontendUserGroup $receiverGroupToRemove): void
    {
        $this->receiverGroup->detach($receiverGroupToRemove);
    }

    public function getReceiverGroup(): ObjectStorage
    {
        return $this->receiverGroup;
    }

    public function setReceiverGroup(ObjectStorage $receiverGroup): void
    {
        $this->receiverGroup = $receiverGroup;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setReceiverGroupName(?string $receiverGroupName): void
    {
        $this->receiverGroupName = $receiverGroupName;
    }

    public function getReceiverGroupName(): ?string
    {
        return $this->receiverGroupName;
    }

    public function setSubgroup(ObjectStorage $subgroup): void
    {
        $this->subgroup = $subgroup;
    }

    public function addSubgroup(FrontendUserGroup $subgroup): void
    {
        $this->subgroup->attach($subgroup);
    }

    public function removeSubgroup(FrontendUserGroup $subgroup): void
    {
        $this->subgroup->detach($subgroup);
    }

    public function getSubgroup(): ObjectStorage
    {
        return $this->subgroup;
    }
}