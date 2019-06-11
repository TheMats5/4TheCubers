<?php

namespace App\Controller;

use App\Entity\Solve;
use App\Entity\User;
use App\Repository\FriendsRepository;
use App\Repository\SolveRepository;
use App\Repository\UserRepository;
use App\Service\FriendService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="dashboard")
     * @Template
     */
    public function dashboard(FriendsRepository $friendsRepository, FriendService $friendService, UserRepository $userRepository): array
    {
        try{
            $user = $this->getUser();
            $userRepository->setLastActiveForUser($user);
            $friends = $friendsRepository->getAllFriends($user);
            $friendsList = $friendService->getListOfFriends($friends, $user);
            $allOnlineFriends = $friendsRepository->getAllOnlineFriend($friendsList);
            $friendRequests = $friendsRepository->getRequestedFriendships($user);
            $amountOfRequest = count($friendRequests);
        } catch(\Exception $exception){
            $this->addFlash('error', $exception);
        }


        return [
            'user' => $user,
            'friends' => $friendsList,
            'amounntOfRequest' => $amountOfRequest,
            'allOnlineFriends' => $allOnlineFriends,
        ];
    }




    /**
     * @Route("/ajax-save-solve", name="ajax-save-solve", methods={"GET","POST"})
     */
    public function ajaxSaveSolve(Request $request, SolveRepository $solveRepository)
    {
        /** @var User $user */
        $user = $this->getUser();
        if(null !== $request->request->get('time')){

            /** @var int $time */
            $time = $request->request->get('time');
            $cubeType = $request->request->get('cubeType');
            $scramble = $request->request->get('scramble');
            $plus2 = $request->request->get('plus2');
            $solveRepository->addSolve($time, $cubeType, $scramble, $plus2, $user);

            return new Response('succes', 200);
        };
        return new Response('failed', 201);
    }

    /**
     * @Route("/ajax-get-solves", name="ajax-get-solves", methods={"GET", "POST"})
     */
    public function ajaxGetSolves(Request $request, SolveRepository $solveRepository)
    {
        $user = $this->getUser();
        $type = $request->request->get('searchByType');
        $data = $solveRepository->getAllSolvesByUser($user, $type);

        return new JsonResponse($data);
    }

    /**
     * @Route("/get-general-stats", name="get-general-stats", methods={"GET"})
     */
    public function getGeneralStats(SolveRepository $solveRepository)
    {
        $user = $this->getUser();
        $allSolves = $solveRepository->getAllSolvesCountByUser($user);
        $allPlus2 = $solveRepository->getAllPlus2CountByUser($user);
        $allDNF = $solveRepository->getAllDnfCountByUser($user);

        return new JsonResponse([$allSolves, $allPlus2, $allDNF]);
    }

    /**
     * @Route("/get-personal-stats", name="get-personal-stats", methods={"GET", "POST"})
     */
    public function getPersonalStats(Request $request, SolveRepository $solveRepository)
    {
        $user = $this->getUser();
        $type = $request->request->get('type');
        $bestSolve = $solveRepository->getBestSolve($user, $type);
        $ao5 = $solveRepository->getAverageOFLatestSolves($user, $type, 5);
        $ao12 = $solveRepository->getAverageOFLatestSolves($user, $type, 12);
        return new JsonResponse([$bestSolve, $ao5, $ao12]);
    }

    /**
     * @Route("/send-friendRequest", name="send-friendRequest", methods={"POST"})
     */
    public function sendFriendRequest(Request $request, FriendsRepository $friendsRepository, UserRepository $userRepository)
    {
        $user = $this->getUser();
        $username = $request->request->get('username');
        $friend = $userRepository->getUserByUsername($username);
        if(empty($friend)){
            return new JsonResponse([
                'status'=> 'error',
                'message' => 'user not found'
            ]);
        } else {
            $friendsRepository->createFriendRequest($user, $friend);
            return new JsonResponse([
                'status'=> 'success',
                'message' => 'user not found'
            ]);
        }


    }
}
