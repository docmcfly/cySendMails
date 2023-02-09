<?php
declare(strict_types = 1);
namespace Cylancer\CySendMails\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
 
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
class Message extends AbstractEntity
{

    /**
     *
     * @var Boolean
     */
    protected $sendSenderAddress = true;

    /**
     *
     * @var Boolean
     */
    protected $copyToSender = true;

    /**
     *
     * @var FrontendUser
     */
    protected $sender = null;

    /**
     *
     * @var string
     */
    protected $receivers = '';

    /**
     *
     * @var string
     */
    protected $subject = '';

    /**
     *
     * @var string
     */
    protected $message = '';

    /**
     *
     * @var array
     */
    protected $attachments = null;

    /**
     *
     * @var String
     */
    protected $attachmentsMetaData = null;

    /**
     *
     * @var String
     */
    protected $key = null;

    /**
     *
     * @return boolean
     */
    public function getSendSenderAddress(): bool
    {
        return $this->sendSenderAddress;
    }

    /**
     *
     * @param boolean $sendSenderAddress
     * @return void
     */
    public function setSendSenderAddress(bool $sendSenderAddress): void
    {
        $this->sendSenderAddress = $sendSenderAddress;
    }

    /**
     *
     * @return boolean
     */
    public function getCopyToSender(): bool
    {
        return $this->copyToSender;
    }

    /**
     *
     * @param boolean $copyToSender
     * @return void
     */
    public function setCopyToSender(bool $copyToSender): void
    {
        $this->copyToSender = $copyToSender;
    }

    /**
     *
     * @return FrontendUser
     */
    public function getSender(): ?FrontendUser
    {
        return $this->sender;
    }

    /**
     *
     * @param FrontendUser $sender
     * @return void
     */
    public function setSender(FrontendUser $sender): void
    {
        $this->sender = $sender;
    }

    /**
     *
     * @return string
     */
    public function getReceivers(): String
    {
        return $this->receivers;
    }

    /**
     *
     * @param string $receivers
     * @return void
     */
    public function setReceivers(String $receivers): void
    {
        $this->receivers = $receivers;
    }

    /**
     *
     * @return string
     */
    public function getSubject(): String
    {
        return $this->subject;
    }

    /**
     *
     * @param string $subject
     * @return void
     */
    public function setSubject(String $subject)
    {
        $this->subject = $subject;
    }

    /**
     *
     * @return string
     */
    public function getMessage(): String
    {
        return $this->message;
    }

    /**
     *
     * @param string $message
     * @return void
     */
    public function setMessage(String $message): void
    {
        $this->message = $message;
    }

    /**
     *
     * @return array
     */
    public function getAttachments(): ?array
    {
        return $this->attachments;
    }

    /**
     *
     * @param array $attachments
     */
    public function setAttachments(array $attachments): void
    {
        $this->attachments = $attachments;
    }

    /**
     *
     * @return string
     */
    public function getAttachmentsMetaData(): string
    {
        return $this->attachmentsMetaData;
    }

    /**
     *
     * @param string $attachmentsMetaData
     */
    public function setAttachmentsMetaData($attachmentsMetaData): void
    {
        $this->attachmentsMetaData = $attachmentsMetaData;
    }

    /**
     *
     * @return string
     */
    public function getKey(): ?String
    {
        return $this->key;
    }

    /**
     *
     * @param string $key
     * @return void
     */
    public function setKey($key): void
    {
        $this->key = $key;
    }
}