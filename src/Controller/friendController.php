<?php


namespace App\Controller;

use App\Entity\User;
use App\Repository\FriendsRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class friendController extends AbstractController
{

    /**
     * @Route("/friends", name="friends")
     * @Template
     */
    public function friends(FriendsRepository $friendsRepository, UserRepository $userRepository)
    {
        /** @var User $user */
        $user = $this->getUser();
        $allRequests = $friendsRepository->getRequestedFriendships($user);
        $allRequestSenders = $userRepository->getAllSenderUsersById($allRequests);
        return $this->render('default/friends.html.twig',[
            'allRequestSenders' =>$allRequestSenders,
            'user' => $user,
        ]);

    }

    /**
     * @Route("/send-friend-answer", name="send-friend-answer", methods={"POST"})
     */
    public function sendFriendAnswer(Request $request, FriendsRepository $friendsRepository, UserRepository $userRepository)
    {
        /** @var User $user */
        $user = $this->getUser();
        $requestType = $request->request->get('requestType');
        $senderId = $request->request->get('senderId');
        try{

            /** @var User $sender */
            $sender = $userRepository->getUserById($senderId);
        } catch (\Exception $exception){
            $this->addFlash('error', $exception);
        }

        if($requestType === 'accepted')
        {
            $idk = $friendsRepository->changeRequestStatus($requestType, $senderId);
            return new JsonResponse([
                'status'=> 'success',
                'message' => $sender->getUsername().' is successfully added as friend'
            ]);
        } else {
            return new JsonResponse([
                'status'=> 'error',
                'message' => $sender->getUsername().' is not added as friend'
            ]);
        }


    }
    /**
     * @Route("/check-online", name="check-online", methods={"POST"})
     */
    public function checkOnline(UserRepository $userRepository)
    {
        $user = $this->getUser();
        $userRepository->setLastActiveForUser($user);
    }
}