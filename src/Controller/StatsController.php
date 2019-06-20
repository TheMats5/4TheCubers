<?php


namespace App\Controller;

use App\Entity\User;
use App\Repository\FriendsRepository;
use App\Repository\SolveRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class StatsController extends AbstractController
{
    /**
     * @Route("/stats", name="stats")
     * @Template
     */
    public function stats(FriendsRepository $friendsRepository, SolveRepository $solveRepository)
    {
        /** @var User $user */
        $user = $this->getUser();

        $friendRequests = $friendsRepository->getRequestedFriendships($user);
        $amountOfRequest = count($friendRequests);
        $allStats = $solveRepository->getAllSolvesByUser($user);
        return $this->render('default/stats.html.twig',[
            'allStats' => $allStats,
            'user' => $user,
            'amountOfRequest' => $amountOfRequest,
        ]);
    }

    /**
     * @Route("/get-all-stats-chart", name="get-all-stats-chart")
     */
    public function getGeneralStats(SolveRepository $solveRepository)
    {
        $user = $this->getUser();
        $allSolves = $solveRepository->getAllSolvesCountByUser($user);
        $allPlus2 = $solveRepository->getAllPlus2CountByUser($user);
        $allDNF = $solveRepository->getAllDnfCountByUser($user);

        return new JsonResponse([$allSolves, $allPlus2, $allDNF]);
    }
}