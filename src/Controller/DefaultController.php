<?php

namespace App\Controller;

use App\Entity\Solve;
use App\Entity\User;
use App\Repository\SolveRepository;
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
    public function dashboard(): array
    {
        return [
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
            $solve = $solveRepository->addSolve($time, $cubeType, $user);

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
}
