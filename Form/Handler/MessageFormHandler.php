<?php

namespace Msi\Bundle\MessageBundle\Form\Handler;

class MessageFormHandler
{
    protected $request;
    protected $messageManager;
    protected $sc;

    public function __construct($request, $messageManager, $sc)
    {
        $this->request = $request;
        $this->messageManager = $messageManager;
        $this->sc = $sc;
    }

    public function process($form)
    {
        if ($this->request->getMethod() === 'POST') {
            $form->bind($this->request);

            if ($form->isValid()) {
                $this->onSuccess($form->getData());

                return true;
            }
        }

        return false;
    }

    protected function onSuccess($object)
    {
        $object->setSender($this->sc->getToken()->getUser());
        $this->messageManager->save($object);
    }
}
