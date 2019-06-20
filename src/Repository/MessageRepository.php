<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function saveMessage($senderId, $receiverId, $body)
    {
        $message = new Message();
        $message->setSenderId($senderId);
        $message->setReceiverId($receiverId);
        $message->setBody($body);
        $message->setCreatedAt(new \DateTime());
        $em = $this->getEntityManager();
        $em->persist($message);
        $em->flush();
    }

    public function getMessagesBySenderAndReceiver($senderId, $receiverId)
    {
        $messagesOfSender = $this->createQueryBuilder('message')
            ->where('message.senderId = :userId')
            ->andWhere('message.receiverId = :receiverId')
            ->setParameter('userId', $senderId)
            ->setParameter('receiverId', $receiverId)
            ->orderBy('message.createdAt')
            ->getQuery()
            ->getResult();

        $messagesOfReceiver = $this->createQueryBuilder('message')
            ->where('message.senderId = :receiverId')
            ->andWhere('message.receiverId = :userId')
            ->setParameter('userId', $senderId)
            ->setParameter('receiverId', $receiverId)
            ->orderBy('message.createdAt')
            ->getQuery()
            ->getResult();
        $result = array_merge($messagesOfSender,$messagesOfReceiver);
        /**
         * @var  $c
         * @var Message $key
         */
        foreach($result as $c=>$key)
            $dateTime[] = $key->getCreatedAt()->format('Y-m-d H:i:s');
        array_multisort($dateTime,SORT_ASC,SORT_STRING,$result);
        return $result;
    }

    public function getNewMessagesBySenderAndReceiver($senderId, $receiverId,$time)
    {
        $messagesOfSender = $this->createQueryBuilder('message')
            ->where('message.senderId = :userId')
            ->andWhere('message.receiverId = :receiverId')
            ->andWhere('message.createdAt > :time')
            ->setParameter('userId', $senderId)
            ->setParameter('receiverId', $receiverId)
            ->setParameter('time', $time)
            ->orderBy('message.createdAt')
            ->getQuery()
            ->getResult();

        $messagesOfReceiver = $this->createQueryBuilder('message')
            ->where('message.senderId = :receiverId')
            ->andWhere('message.receiverId = :userId')
            ->andWhere('message.createdAt > :time')
            ->setParameter('userId', $senderId)
            ->setParameter('receiverId', $receiverId)
            ->setParameter('time', $time)
            ->orderBy('message.createdAt')
            ->getQuery()
            ->getResult();
        $result = array_merge($messagesOfSender,$messagesOfReceiver);
        /**
         * @var  $c
         * @var Message $key
         */
        if(!empty($result)){
            foreach($result as $c=>$key)
                $dateTime[] = $key->getCreatedAt()->format('Y-m-d H:i:s');
            array_multisort($dateTime,SORT_ASC,SORT_STRING,$result);
        }

        return $result;
    }
    // /**
    //  * @return Message[] Returns an array of Message objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Message
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
