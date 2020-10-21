<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Occupation;
use App\Form\OccupationType;
use App\Repository\OccupationRepository;
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
 * @Route("/occupation")
 */
class OccupationController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * @Route("/", name="occupation_index", methods={"GET"})
     *
     * @Template()
     */
    public function index(Request $request, OccupationRepository $occupationRepository) : array {
        $query = $occupationRepository->indexQuery();
        $pageSize = $this->getParameter('page_size');
        $page = $request->query->getint('page', 1);

        return [
            'occupations' => $this->paginator->paginate($query, $page, $pageSize),
        ];
    }

    /**
     * @Route("/search", name="occupation_search", methods={"GET"})
     *
     * @Template()
     *
     * @return array
     */
    public function search(Request $request, OccupationRepository $occupationRepository) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $occupationRepository->searchQuery($q);
            $occupations = $this->paginator->paginate($query, $request->query->getInt('page', 1), $this->getParameter('page_size'), ['wrap-queries' => true]);
        } else {
            $occupations = [];
        }

        return [
            'occupations' => $occupations,
            'q' => $q,
        ];
    }

    /**
     * @Route("/typeahead", name="occupation_typeahead", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function typeahead(Request $request, OccupationRepository $occupationRepository) {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse([]);
        }
        $data = [];
        foreach ($occupationRepository->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => (string) $result,
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/new", name="occupation_new", methods={"GET","POST"})
     * @Template()
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @return array|RedirectResponse
     */
    public function new(Request $request) {
        $occupation = new Occupation();
        $form = $this->createForm(OccupationType::class, $occupation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($occupation);
            $entityManager->flush();
            $this->addFlash('success', 'The new occupation has been saved.');

            return $this->redirectToRoute('occupation_show', ['id' => $occupation->getId()]);
        }

        return [
            'occupation' => $occupation,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/new_popup", name="occupation_new_popup", methods={"GET","POST"})
     * @Template()
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @return array|RedirectResponse
     */
    public function new_popup(Request $request) {
        return $this->new($request);
    }

    /**
     * @Route("/{id}", name="occupation_show", methods={"GET"})
     * @Template()
     *
     * @return array
     */
    public function show(Occupation $occupation) {
        return [
            'occupation' => $occupation,
        ];
    }

    /**
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="occupation_edit", methods={"GET","POST"})
     *
     * @Template()
     *
     * @return array|RedirectResponse
     */
    public function edit(Request $request, Occupation $occupation) {
        $form = $this->createForm(OccupationType::class, $occupation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'The updated occupation has been saved.');

            return $this->redirectToRoute('occupation_show', ['id' => $occupation->getId()]);
        }

        return [
            'occupation' => $occupation,
            'form' => $form->createView(),
        ];
    }

    /**
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}", name="occupation_delete", methods={"DELETE"})
     *
     * @return RedirectResponse
     */
    public function delete(Request $request, Occupation $occupation) {
        if ($this->isCsrfTokenValid('delete' . $occupation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($occupation);
            $entityManager->flush();
            $this->addFlash('success', 'The occupation has been deleted.');
        }

        return $this->redirectToRoute('occupation_index');
    }
}
