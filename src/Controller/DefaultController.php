<?php

namespace App\Controller;

use App\Entity\Solve;
use App\Entity\User;
use App\Repository\SolveRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index()
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/ajax-time", name="ajax-time", methods={"GET","POST"})
     */
    public function ajaxTime(Request $request, SolveRepository $solveRepository)
    {
        /** @var User $user */
        $user = $this->getUser();
        if(null !== $request->request->get('time')){

            /** @var int $time */
            $time = $request->request->get('time');
            $solve = $solveRepository->addSolve($time, $user);

            return new JsonResponse($solve);
        };
        return new Response('failed', 201);
    }
}
