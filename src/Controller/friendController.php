<?php


namespace App\Controller;

use App\Entity\User;
use App\Form\UpdateProfileForm;
use App\Repository\FriendsRepository;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use App\Service\FriendService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class friendController extends AbstractController
{

    /**
     * @Route("/friends", name="friends")
     * @Template
     */
    public function friends(MessageRepository $messageRepository, FriendsRepository $friendsRepository, UserRepository $userRepository, Request $request, FriendService $friendService)
    {
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(UpdateProfileForm::class, $user);

        $allRequests = $friendsRepository->getRequestedFriendships($user);
        $allRequestSenders = $userRepository->getAllSenderUsersById($allRequests);
        $friends = $friendsRepository->getAllFriends($user);
        $friendRequests = $friendsRepository->getRequestedFriendships($user);
        $amountOfRequest = count($friendRequests);
        $friendsList = $friendService->getListOfFriendsOrderedByLogin($friends, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $file = $request->files->get('update_profile_form')['profilePicture'];
            if(null !== $file){
                $uploads_directory = $this->getParameter('uploads_directory');
                $filename = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $uploads_directory,
                    $filename
                );
                $user->setProfilePicture("uploads/".$filename);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('friends');
        }

        return $this->render('default/friends.html.twig',[
            'profileForm' => $form->createView(),
            'allRequestSenders' =>$allRequestSenders,
            'user' => $user,
            'friends' => $friends,
            'amountOfRequest' => $amountOfRequest,
            'friendslist' => $friendsList
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

        if($requestType === 'request_accepted')
        {
            $idk = $friendsRepository->changeRequestStatus($requestType, $senderId);
            return new JsonResponse([
                'status'=> 'success',
                'message' => $sender->getUsername().' is successfully added as friend'
            ]);
        } else {
            $idk = $friendsRepository->changeRequestStatus($requestType, $senderId);
            return new JsonResponse([
                'status'=> 'error',
                'message' => 'Friend request from '.$sender->getUsername().' is deleted'
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
        return new Response('succes', 200);
    }

    /**
     * @Route("/ajax-get-ranking", name="ajax-get-ranking", methods={"GET","POST"})
     */
    public function getRanking(Request $request, FriendsRepository $friendsRepository, FriendService $friendService)
    {
        $user = $this->getUser();
        $friends = $friendsRepository->getAllFriends($user);
        $friendsList = $friendService->getListOfFriendsOrderedByLogin($friends, $user);
        $type = $request->request->get('type');
        $friendsRanking = $friendService->getListOfFriendsWithRanking($friendsList, $user, $type);

        return new JsonResponse($friendsRanking);
    }



}