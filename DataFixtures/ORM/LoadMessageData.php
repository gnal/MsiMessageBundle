<?php

namespace Msi\Bundle\MessageBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadMessageData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        if (!$this->container->getParameter('msi_message.load_data_fixtures')) {
            return;
        }

        for ($i=0; $i < 3000; $i++) {
            $message = new \Msi\Bundle\MessageBundle\Entity\Message();
            $message
                ->setSubject('Lorem ipsum massa')
                ->setBody('<p>Pellentesque ut massa vitae tortor consequat adipiscing. Nunc consequat rutrum urna eu rhoncus. Aliquam erat volutpat. Aenean id nisl vel nisl sodales pharetra. Nam bibendum ipsum sed risus condimentum congue.</p><p>In erat lacus, vestibulum quis pulvinar a, placerat sed tortor. Proin pellentesque nibh nulla. Donec elit nunc, iaculis ac interdum sed, lacinia quis tellus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Sed malesuada enim ut lectus sagittis facilisis.</p>')
                ->setSender($manager->merge($this->getReference('user'.mt_rand(0, 99))))
                ->getRecipients()->add($manager->merge($this->getReference('user'.mt_rand(0, 99))))
            ;
            $manager->persist($message);
            $this->saveBatch($manager, $i);
        }

        $manager->flush();
        $manager->clear();
    }

    public function getOrder()
    {
        return 2;
    }

    public function saveBatch($manager, $i)
    {
        $batchSize = 20;
        if (($i % $batchSize) == 0) {
            $manager->flush();
            $manager->clear();
        }
    }
}
