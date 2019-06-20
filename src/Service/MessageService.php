<?php


namespace App\Service;


use App\Entity\Message;
use App\Entity\User;
use App\Repository\UserRepository;

class MessageService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    public function formatMessages($messages, User $user)
    {
        $formattedMessage = [];
        /** @var Message $message */
        foreach ($messages as $message){
            if($user->getId() === $message->getSenderId()){
                $type = 'sender';
                $title = 'me';
            } else {
                /** @var User $receiver */
                $receiver = $this->userRepository->getUserById($message->getReceiverId());
                $type = 'receiver';
                $title = $receiver->getUsername();
            }
            $array=[
                'type' =>$type,
                'title' => $title,
                'body' => $message->getBody(),
                'timestamp' => $message->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
            array_push($formattedMessage, $array);

        }
        return $formattedMessage;
    }

}