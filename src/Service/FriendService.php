<?php


namespace App\Service;


use App\Entity\Friend;
use App\Entity\User;
use App\Repository\SolveRepository;
use App\Repository\UserRepository;
use DateTime;

class FriendService
{

    /** @var UserRepository */
    private $userRepository;

    /** @var SolveRepository  */
    private $solveRepository;

    public function __construct(UserRepository $userRepository, SolveRepository $solveRepository)
    {
        $this->userRepository = $userRepository;
        $this->solveRepository = $solveRepository;
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
                    array_push($friendArray, $friendUser);
                } else {
                    $friendUser = $this->userRepository->getUserById($friend->getSenderId());
                    array_push($friendArray, $friendUser);
                }
            }
            return $friendArray;
        } else {

            return null;
        }

    }

    public function getListOfFriendsOrderedByLogin($friends, User $user)
    {
        $friendArray = [];
        if($friends !== ''){
            $i= 0;
            /** @var Friend $friend */
            foreach ($friends as $friend) {
                if ($user->getId() === $friend->getSenderId()) {
                    //user is the sender so friend is receiver_id
                    $friendUser = $this->userRepository->getUserById($friend->getReceiverId());
                    array_push($friendArray, $friendUser);
                } else {
                    $friendUser = $this->userRepository->getUserById($friend->getSenderId());
                    array_push($friendArray, $friendUser);
                }
            }
            /**
             * @var  $c
             * @var User $key
             */
            foreach($friendArray as $c=>$key)
            $dateTime[] = $key->getLastActive()->format('Y-m-d H:i:s');
        array_multisort($dateTime,SORT_DESC,SORT_STRING,$friendArray);
            return $friendArray;
        } else {

            return null;
        }
    }

    public function getListOfFriendsWithRanking($friendsList, User $user, $type)
    {
        array_push($friendsList, $user);
        if($type === null){
            $type = '333';
        }
        $rankList = [];
        /** @var User $friend */
        foreach($friendsList as $friend){
          $bestSolve = $this->solveRepository->getBestSolve($friend, $type);
          $idk = [
              'username' => $friend->getUsername(),
              'profilePic' => $friend->getProfilePicture(),
              'time' => $bestSolve,
          ];
            array_push($rankList, $idk);

        }
        foreach($rankList as $c=>$key)
        $dateTime[] = $key['time'];
        array_multisort($dateTime,SORT_DESC,SORT_STRING,$rankList);
        return $rankList;
    }
}