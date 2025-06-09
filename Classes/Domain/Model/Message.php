<?php
declare(strict_types=1);
namespace Cylancer\CySendMails\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;

/**
 *
 * This file is part of the "cy_send_mails" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2025 C. Gogolin <service@cylancer.net>
 *
 * 
 */
class Message extends AbstractEntity
{

    protected bool $sendSenderAddress = true;

    protected bool $copyToSender = true;

    protected ?FrontendUser $sender = null;

    protected ?string $receivers = '';

    protected ?string $subject = '';

    protected ?string $message = '';

    /** @var ObjectStorage<FileReference> */
    protected ObjectStorage $attachments;

    protected ?string $attachmentsMetaData = null;

    protected ?string $key = null;

    public function __construct()
    {
        $this->attachments = new ObjectStorage();
    }

    public function initializeObject(): void
    {
        $this->attachments = $this->attachments ?? new ObjectStorage();
    }

    public function getSendSenderAddress(): bool
    {
        return $this->sendSenderAddress;
    }

    public function setSendSenderAddress(bool $sendSenderAddress): void
    {
        $this->sendSenderAddress = $sendSenderAddress;
    }

    public function getCopyToSender(): bool
    {
        return $this->copyToSender;
    }

    public function setCopyToSender(bool $copyToSender): void
    {
        $this->copyToSender = $copyToSender;
    }

    public function getSender(): ?FrontendUser
    {
        return $this->sender;
    }

    public function setSender(?FrontendUser $sender): void
    {
        $this->sender = $sender;
    }

    public function getReceivers(): ?string
    {
        return $this->receivers;
    }

    public function setReceivers(?string $receivers): void
    {
        $this->receivers = $receivers;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject)
    {
        $this->subject = $subject;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    public function getAttachments(): ObjectStorage
    {
        return $this->attachments;
    }

    public function setAttachments(ObjectStorage $attachments): void
    {
        $this->attachments = $attachments;
    }

    public function getAttachmentsMetaData(): ?string
    {
        return $this->attachmentsMetaData;
    }

    public function setAttachmentsMetaData(?string $attachmentsMetaData): void
    {
        $this->attachmentsMetaData = $attachmentsMetaData;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey($key): void
    {
        $this->key = $key;
    }
}