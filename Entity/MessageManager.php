<?php

namespace Msi\Bundle\MessageBundle\Entity;

use Msi\Bundle\AdminBundle\Entity\BaseManager;

class MessageManager extends BaseManager
{
    protected $user;

    public function __construct($class, $sc)
    {
        parent::__construct($class);
        $this->user = $sc->getToken()->getUser();
    }

    public function findOneInboxMessage($id)
    {
        $qb = $this->repository->createQueryBuilder('a')
            ->innerJoin('a.recipients', 'r')
            ->innerJoin('a.sender', 's')
            ->addSelect('s')
            ->andWhere('r.id = :userId')
            ->setParameter('userId', $this->user->getId())
            ->addOrderBy('a.createdAt', 'DESC')
        ;

        $qb->andWhere('a.id = :id')->setParameter('id', $id);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findAllInboxMessages()
    {
        $qb = $this->repository->createQueryBuilder('a')
            ->innerJoin('a.recipients', 'r')
            ->innerJoin('a.sender', 's')
            ->addSelect('s')
            ->andWhere('r.id = :userId')
            ->setParameter('userId', $this->user->getId())
            ->addOrderBy('a.createdAt', 'DESC')
        ;

        return $qb->getQuery()->execute();
    }

    public function findSent($id = null)
    {
        $qb = $this->repository->createQueryBuilder('a')
            ->andWhere('a.sender = :user')
            ->setParameter('user', $user)
            ->addOrderBy('a.createdAt', 'DESC')
        ;

        if ($id) {
            $qb->andWhere('a.id = :id')->setParameter('id', $id);

            return $qb->getQuery()->getOneOrNullResult();
        }

        return $qb->getQuery()->execute();
    }

    public function markAsRead($message)
    {
        if ($message->getReadBy()->contains($this->user)) {
            return;
        }

        $message->getReadBy()->add($this->user);
        $this->save($message);
    }

    public function markAsTrashed($message)
    {
        if ($message->getTrashedBy()->contains($this->user)) {
            return;
        }

        $message->getTrashedBy()->add($this->user);
        $this->save($message);
    }

    public function markAsDeleted($message)
    {
        if ($message->getDeletedBy()->contains($this->user)) {
            return;
        }

        $message->getDeletedBy()->add($this->user);
        $this->save($message);
    }
}
