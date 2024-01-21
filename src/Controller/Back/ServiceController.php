<?php

namespace App\Controller\Back;

use App\Domain\Command\Back\SaveServiceCommand;
use App\Domain\Command\Back\SaveServiceHandler;
use App\Entity\Quote\Service;
use App\Form\Quote\ServiceListType;
use App\Form\Quote\ServiceType;
use App\Repository\Quote\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ServiceController extends AbstractController
{
    public function __construct(
        protected ServiceRepository $serviceRepository,
        protected TranslatorInterface $translator,
        protected EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/type-service/liste', name: 'bo_service_list', methods: ['GET', 'POST'])]
    public function list(Request $request): Response
    {
        $serviceList = $this->serviceRepository->findOrdered();

        $form = $this->createForm(CollectionType::class, $serviceList, [
            'label' => false,
            'entry_type' => ServiceListType::class,
            'entry_options' => [
                'label' => false,
            ],
            'allow_add' => false,
            'allow_delete' => false,
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->entityManager->flush();

            $this->addFlash('info', $this->translator->trans('back.service.list.order.success'));

            return $this->redirectToRoute('bo_service_list');
        }

        return $this->render('back/service/list.html.twig', [
            'form' => $form->createView(),
            'serviceCount' => count($serviceList),
         ]);
    }

    #[Route('/type-service/nouveau-service', name: 'bo_service_new', methods: ['GET', 'POST'])]
    public function add(Request $request, SaveServiceHandler $saveServiceHandler): Response
    {
        $service = new Service();

        $service->setRank($this->serviceRepository->getFirstRank() - 1);

        return $this->edit($request, $saveServiceHandler, $service);
    }

    #[Route('/type-service/modifier/{service}', name: 'bo_service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SaveServiceHandler $saveServiceHandler, Service $service): Response
    {
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $saveServiceHandler->handle(new SaveServiceCommand($service));

            $this->addFlash('info', $this->translator->trans('back.service.edit.success'));

            return $this->redirectToRoute('bo_service_list');
        }

        return $this->render('back/service/edit.html.twig', [
             'form' => $form->createView(),
             'service' => $service,
         ]);
    }

    #[Route('/type-service/supprimer/{service}', name: 'bo_service_delete', methods: ['POST'])]
    public function delete(Request $request, Service $service): Response
    {
        if ($this->isCsrfTokenValid('delete-'.$service->getId(), (string) $request->request->get('_token'))) {
            $this->serviceRepository->remove($service, true);

            $this->addFlash('info', $this->translator->trans('back.service.delete.success'));
        }

        return $this->redirectToRoute('bo_service_list', [], Response::HTTP_SEE_OTHER);
    }
}
