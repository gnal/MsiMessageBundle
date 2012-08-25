<?php

namespace Msi\Bundle\MessageBundle\Provider;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Provider
{
    protected $messageManager;
    protected $user;

    public function __construct(SecurityContextInterface $sc, $messageManager)
    {
        $this->user = $sc->getToken()->getUser();
        $this->messageManager = $messageManager;
    }

    public function getInboxMessages()
    {
        $messages = new ArrayCollection($this->messageManager->findAllInboxMessages());

        foreach ($messages as $k => $v) {
            if ($this->user->getTrashedMessages()->contains($v)) {
                $messages->remove($k);
            }
        }

        return $messages;
    }

    public function getMessage($id)
    {
        $message = $this->messageManager->findOneInboxMessage($id);
        if (!$message) {
            throw new NotFoundHttpException();
        }
        $this->messageManager->markAsRead($message);

        return $message;
    }
}
