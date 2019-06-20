<?php


namespace App\Controller;

use App\Entity\User;
use App\Form\UpdateProfileForm;
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
    public function stats(FriendsRepository $friendsRepository, SolveRepository $solveRepository, Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(UpdateProfileForm::class, $user);

        $friendRequests = $friendsRepository->getRequestedFriendships($user);
        $amountOfRequest = count($friendRequests);
        $allStats = $solveRepository->getAllSolvesByUser($user);
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
        return $this->render('default/stats.html.twig',[
            'profileForm' => $form->createView(),
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