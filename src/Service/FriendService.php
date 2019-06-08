<?php


namespace App\Service;


use App\Entity\Friend;
use App\Entity\User;
use App\Repository\UserRepository;

class FriendService
{

    /** @var UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getListOfFriends($friends, User $user)
    {
        $friendArray = [];
        if($friends !== ''){
            $i= 0;
            /** @var Friend $friend */
            foreach ($friends as $friend) {
                if ($user->getId() === $friend->getSenderId()) {
                    //user is the sender so friend is receiver_id
                    $friendUser = $this->userRepository->getUserById($friend->getReceiverId());
                    array_push($friendArray, $friendUser[0]);
                } else {
                    $friendUser = $this->userRepository->getUserById($friend->getSenderId());
                    array_push($friendArray, $friendUser[0]);
                }
            }
            return $friendArray;
        } else {

            return null;
        }



    }
}