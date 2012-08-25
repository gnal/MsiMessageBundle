<?php

namespace Msi\Bundle\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="msi_message_message")
 * @ORM\Entity
 */
class Message
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column()
     */
    protected $subject;

    /**
     * @ORM\Column(type="text")
     */
    protected $body;

    /**
     * @ORM\ManyToMany(targetEntity="Acme\Bundle\UserBundle\Entity\User", inversedBy="receivedMessages")
     * @ORM\JoinTable(name="msi_message_messages_recipients")
     **/
    protected $recipients;

    /**
     * @ORM\ManyToOne(targetEntity="Acme\Bundle\UserBundle\Entity\User", inversedBy="sentMessages")
     */
    protected $sender;

    /**
     * @ORM\ManyToMany(targetEntity="Acme\Bundle\UserBundle\Entity\User", inversedBy="trashedMessages")
     * @ORM\JoinTable(name="msi_message_messages_trashed_by")
     **/
    protected $trashedBy;

    /**
     * @ORM\ManyToMany(targetEntity="Acme\Bundle\UserBundle\Entity\User", inversedBy="readMessages")
     * @ORM\JoinTable(name="msi_message_messages_read_by")
     **/
    protected $readBy;

    /**
     * @ORM\ManyToMany(targetEntity="Acme\Bundle\UserBundle\Entity\User", inversedBy="deletedMessages")
     * @ORM\JoinTable(name="msi_message_messages_deleted_by")
     **/
    protected $deletedBy;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     */
    protected $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->recipients = new ArrayCollection();
        $this->trashedBy = new ArrayCollection();
        $this->readBy = new ArrayCollection();
        $this->deletedBy = new ArrayCollection();
    }

    public function getTrashedBy()
    {
        return $this->trashedBy;
    }

    public function setTrashedBy($trashedBy)
    {
        $this->trashedBy = $trashedBy;

        return $this;
    }

    public function getReadBy()
    {
        return $this->readBy;
    }

    public function setReadBy($readBy)
    {
        $this->readBy = $readBy;

        return $this;
    }

    public function getDeletedBy()
    {
        return $this->deletedBy;
    }

    public function setDeletedBy($deletedBy)
    {
        $this->deletedBy = $deletedBy;

        return $this;
    }

    public function getRecipients()
    {
        return $this->recipients;
    }

    public function setRecipients($recipients)
    {
        $this->recipients = $recipients;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    public function getSender()
    {
        return $this->sender;
    }

    public function setSender($sender)
    {
        $this->sender = $sender;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }
}
