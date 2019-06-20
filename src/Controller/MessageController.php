<?php


namespace App\Controller;



use App\Entity\User;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use App\Service\MessageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    /**
     * @Route("/ajax-send-message", name="ajax-send-message", methods={"GET","POST"})
     */
    public function SendMessage(Request $request, MessageRepository $messageRepository, UserRepository $userRepository)
    {
        /** @var User $user */
        $user = $this->getUser();
        $receiverName = $request->request->get('receiver');
        /** @var User $receiver */
        $receiver = $userRepository->getUserByUsername($receiverName);
        $body = $request->request->get('body');
        $messageRepository->saveMessage($user->getId(), $receiver->getId(), $body);
        return new JsonResponse();
    }

    /**
     * @Route("/ajax-get-message", name="ajax-get-message", methods={"GET","POST"})
     */
    public function getMessages(MessageRepository $messageRepository, UserRepository $userRepository, Request $request, MessageService $messageService)
    {
        /** @var User $user */
        $user = $this->getUser();
        $receiverName = $request->request->get('receiver');
        /** @var User $receiver */
        $receiver = $userRepository->getUserByUsername($receiverName);
        $messages = $messageRepository->getMessagesBySenderAndReceiver($user->getId(), $receiver->getId());
        $formattedMessages = $messageService->formatMessages($messages, $user);
        return new JsonResponse($formattedMessages);

    }

    /**
     * @Route("/ajax-get-new-message", name="ajax-get-new-message", methods={"GET","POST"})
     */
    public function getNewMessage(MessageRepository $messageRepository, UserRepository $userRepository, Request $request, MessageService $messageService)
    {
        $requestTime = $request->request->get('time');
        $time = new \DateTime($requestTime);
        /** @var User $user */
        $user = $this->getUser();
        $receiverName = $request->request->get('receiver');
        /** @var User $receiver */
        $receiver = $userRepository->getUserByUsername($receiverName);
        $messages = $messageRepository->getNewMessagesBySenderAndReceiver($user->getId(), $receiver->getId(), $time);
        $formattedMessages = $messageService->formatMessages($messages, $user);
        return new JsonResponse($formattedMessages);
    }
}