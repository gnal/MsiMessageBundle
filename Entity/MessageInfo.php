<?php

namespace Msi\Bundle\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="msi_message_message_info", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_message_user", columns={"message_id", "user_id"})})
 * @ORM\Entity
 */
class MessageInfo
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Message", inversedBy="messageInfos")
     */
    protected $message;

    /**
     * @ORM\ManyToOne(targetEntity="Acme\Bundle\UserBundle\Entity\User", inversedBy="messageInfos")
     */
    protected $user;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isRead;

    /**
     * @ORM\Column(type="integer")
     */
    protected $box;

    const INBOX = 0;
    const TRASH = 1;
    const DRAFT = 2;

    public function __construct()
    {
        $this->isRead = false;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    public function getBox()
    {
        return $this->box;
    }

    public function setBox($box)
    {
        $this->box = $box;

        return $this;
    }

    public function getIsRead()
    {
        return $this->isRead;
    }

    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;

        return $this;
    }
}
