<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\ReferenceRole;
use App\Form\ReferenceRoleType;
use App\Repository\ReferenceRoleRepository;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reference_role")
 */
class ReferenceRoleController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * @Route("/", name="reference_role_index", methods={"GET"})
     *
     * @Template()
     */
    public function index(Request $request, ReferenceRoleRepository $referenceRoleRepository) : array {
        $query = $referenceRoleRepository->indexQuery();
        $pageSize = $this->getParameter('page_size');
        $page = $request->query->getint('page', 1);

        return [
            'reference_roles' => $this->paginator->paginate($query, $page, $pageSize),
        ];
    }

    /**
     * @Route("/search", name="reference_role_search", methods={"GET"})
     *
     * @Template()
     *
     * @return array
     */
    public function search(Request $request, ReferenceRoleRepository $referenceRoleRepository) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $referenceRoleRepository->searchQuery($q);
            $referenceRoles = $this->paginator->paginate($query, $request->query->getInt('page', 1), $this->getParameter('page_size'), ['wrap-queries' => true]);
        } else {
            $referenceRoles = [];
        }

        return [
            'reference_roles' => $referenceRoles,
            'q' => $q,
        ];
    }

    /**
     * @Route("/typeahead", name="reference_role_typeahead", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function typeahead(Request $request, ReferenceRoleRepository $referenceRoleRepository) {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse([]);
        }
        $data = [];
        foreach ($referenceRoleRepository->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => (string) $result,
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/new", name="reference_role_new", methods={"GET","POST"})
     * @Template()
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @return array|RedirectResponse
     */
    public function new(Request $request) {
        $referenceRole = new ReferenceRole();
        $form = $this->createForm(ReferenceRoleType::class, $referenceRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($referenceRole);
            $entityManager->flush();
            $this->addFlash('success', 'The new referenceRole has been saved.');

            return $this->redirectToRoute('reference_role_show', ['id' => $referenceRole->getId()]);
        }

        return [
            'reference_role' => $referenceRole,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/new_popup", name="reference_role_new_popup", methods={"GET","POST"})
     * @Template()
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @return array|RedirectResponse
     */
    public function new_popup(Request $request) {
        return $this->new($request);
    }

    /**
     * @Route("/{id}", name="reference_role_show", methods={"GET"})
     * @Template()
     *
     * @return array
     */
    public function show(ReferenceRole $referenceRole) {
        return [
            'reference_role' => $referenceRole,
        ];
    }

    /**
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="reference_role_edit", methods={"GET","POST"})
     *
     * @Template()
     *
     * @return array|RedirectResponse
     */
    public function edit(Request $request, ReferenceRole $referenceRole) {
        $form = $this->createForm(ReferenceRoleType::class, $referenceRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'The updated referenceRole has been saved.');

            return $this->redirectToRoute('reference_role_show', ['id' => $referenceRole->getId()]);
        }

        return [
            'reference_role' => $referenceRole,
            'form' => $form->createView(),
        ];
    }

    /**
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}", name="reference_role_delete", methods={"DELETE"})
     *
     * @return RedirectResponse
     */
    public function delete(Request $request, ReferenceRole $referenceRole) {
        if ($this->isCsrfTokenValid('delete' . $referenceRole->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($referenceRole);
            $entityManager->flush();
            $this->addFlash('success', 'The referenceRole has been deleted.');
        }

        return $this->redirectToRoute('reference_role_index');
    }
}
