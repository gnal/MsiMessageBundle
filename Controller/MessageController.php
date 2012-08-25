<?php

namespace Msi\Bundle\MessageBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Msi\Bundle\MessageBundle\Form\Type\MessageFormType;
use Doctrine\Common\Collections\ArrayCollection;

class MessageController extends ContainerAware
{
    public function indexAction()
    {
        return $this->container->get('templating')->renderResponse('MsiMessageBundle:Message:index.html.twig');
    }

    public function inboxAction()
    {
        $messages = $this->getProvider()->getInboxMessages();

        return $this->container->get('templating')->renderResponse('MsiMessageBundle:Message:inbox_content.html.twig', array('messages' => $messages));
    }

    public function sentAction()
    {
        $messages = $this->getMessageManager()->findSentMessages();

        return $this->container->get('templating')->renderResponse('MsiMessageBundle:Message:sent.html.twig', array('message' => $message, 'messages' => $messages));
    }

    public function trashAction()
    {
        $messages = $this->getMessageManager()->findReceived();

        return $this->container->get('templating')->renderResponse('MsiMessageBundle:Message:trash.html.twig', array('message' => $message, 'messages' => $messages));
    }

    public function showAction()
    {
        $id = trim($this->container->get('request')->query->get('id'));
        $message = $this->getProvider()->getMessage($id);

        return $this->container->get('templating')->renderResponse('MsiMessageBundle:Message:show_content.html.twig', array('message' => $message));
    }

    public function newAction()
    {
        $object = $this->getMessageManager()->create();
        $form = $this->container->get('form.factory')->create(new MessageFormType(), $object);
        $formHandler = $this->container->get('msi_message.message_form_handler');

        if ($formHandler->process($form)) {

            return new RedirectResponse($this->container->get('router')->generate('msi_message_message_new'));
        }

        return $this->container->get('templating')->renderResponse('MsiMessageBundle:Message:new.html.twig', array('form' => $form->createView()));
    }

    public function markAsTrashAction()
    {
        $id = $this->container->get('request')->attributes->get('id');
        if ($id) {
            $message = $this->getMessageManager()->findReceived($id);
            if (!$message) {
                throw new NotFoundHttpException();
            }
            $this->getMessageManager()->markAsTrashed($message);
        } else {
            throw new NotFoundHttpException();
        }

        return new Response();
    }

    protected function getMessageManager()
    {
        return $this->container->get('msi_message.message_manager');
    }

    protected function getProvider()
    {
        return $this->container->get('msi_message.provider');
    }
}
