<?php

namespace App\Controller\Back;

use App\Domain\Command\Back\SaveOrganizationCommand;
use App\Domain\Command\Back\SaveOrganizationHandler;
use App\Entity\Quote\Organization;
use App\Form\Quote\OrganizationListType;
use App\Form\Quote\OrganizationType;
use App\Repository\Quote\OrganizationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class OrganizationController extends AbstractController
{
    public function __construct(
        protected OrganizationRepository $organizationRepository,
        protected TranslatorInterface $translator,
        protected EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/type-organisation/liste', name: 'bo_organization_list', methods: ['GET', 'POST'])]
    public function list(Request $request): Response
    {
        $organizationList = $this->organizationRepository->findOrdered();

        $form = $this->createForm(CollectionType::class, $organizationList, [
            'label' => false,
            'entry_type' => OrganizationListType::class,
            'entry_options' => [
                'label' => false,
            ],
            'allow_add' => false,
            'allow_delete' => false,
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->entityManager->flush();

            $this->addFlash('info', $this->translator->trans('back.organization.list.order.success'));

            return $this->redirectToRoute('bo_organization_list');
        }

        return $this->render('back/organization/list.html.twig', [
            'form' => $form->createView(),
            'organizationCount' => count($organizationList),
         ]);
    }

    #[Route('/type-organisation/nouvelle-actualite', name: 'bo_organization_new', methods: ['GET', 'POST'])]
    public function add(Request $request, SaveOrganizationHandler $saveOrganizationHandler): Response
    {
        $organization = new Organization();

        $organization->setRank($this->organizationRepository->getFirstRank() - 1);

        return $this->edit($request, $saveOrganizationHandler, $organization);
    }

    #[Route('/type-organisation/modifier/{organization}', name: 'bo_organization_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SaveOrganizationHandler $saveOrganizationHandler, Organization $organization): Response
    {
        $form = $this->createForm(OrganizationType::class, $organization);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $saveOrganizationHandler->handle(new SaveOrganizationCommand($organization));

            $this->addFlash('info', $this->translator->trans('back.organization.edit.success'));

            return $this->redirectToRoute('bo_organization_list');
        }

        return $this->render('back/organization/edit.html.twig', [
             'form' => $form->createView(),
             'organization' => $organization,
         ]);
    }

    #[Route('/type-organisation/supprimer/{organization}', name: 'bo_organization_delete', methods: ['POST'])]
    public function delete(Request $request, Organization $organization): Response
    {
        if ($this->isCsrfTokenValid('delete-'.$organization->getId(), (string) $request->request->get('_token'))) {
            $this->organizationRepository->remove($organization, true);

            $this->addFlash('info', $this->translator->trans('back.organization.delete.success'));
        }

        return $this->redirectToRoute('bo_organization_list', [], Response::HTTP_SEE_OTHER);
    }
}
