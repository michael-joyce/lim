<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Reference;
use App\Repository\ReferenceRepository;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reference")
 */
class ReferenceController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * @Route("/", name="reference_index", methods={"GET"})
     *
     * @Template()
     */
    public function index(Request $request, ReferenceRepository $referenceRepository) : array {
        $query = $referenceRepository->indexQuery();
        $pageSize = $this->getParameter('page_size');
        $page = $request->query->getint('page', 1);

        return [
            'references' => $this->paginator->paginate($query, $page, $pageSize),
        ];
    }

    /**
     * @Route("/search", name="reference_search", methods={"GET"})
     *
     * @Template()
     *
     * @return array
     */
    public function search(Request $request, ReferenceRepository $referenceRepository) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $referenceRepository->searchQuery($q);
            $references = $this->paginator->paginate($query, $request->query->getInt('page', 1), $this->getParameter('page_size'), ['wrap-queries' => true]);
        } else {
            $references = [];
        }

        return [
            'references' => $references,
            'q' => $q,
        ];
    }

    /**
     * @Route("/{id}", name="reference_show", methods={"GET"})
     * @Template()
     *
     * @return array
     */
    public function show(Reference $reference) {
        return [
            'reference' => $reference,
        ];
    }
}
