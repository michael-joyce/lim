<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controller;

use App\Entity\Location;
use App\Form\LocationType;
use App\Repository\LocationRepository;
use App\Service\LinkManager;
use App\Service\ReferenceManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use GeoNames\Client as GeoNamesClient;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/location")
 */
class LocationController extends AbstractController implements PaginatorAwareInterface {
    use PaginatorTrait;

    /**
     * @Route("/", name="location_index", methods={"GET"})
     *
     * @Template()
     */
    public function index(Request $request, LocationRepository $locationRepository) : array {
        $query = $locationRepository->indexQuery();
        $pageSize = $this->getParameter('page_size');
        $page = $request->query->getint('page', 1);

        return [
            'locations' => $this->paginator->paginate($query, $page, $pageSize),
        ];
    }

    /**
     * @Route("/search", name="location_search", methods={"GET"})
     *
     * @Template()
     *
     * @return array
     */
    public function search(Request $request, LocationRepository $locationRepository) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $locationRepository->searchQuery($q);
            $locations = $this->paginator->paginate($query, $request->query->getInt('page', 1), $this->getParameter('page_size'), ['wrap-queries' => true]);
        } else {
            $locations = [];
        }

        return [
            'locations' => $locations,
            'q' => $q,
        ];
    }

    /**
     * @Route("/typeahead", name="location_typeahead", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function typeahead(Request $request, LocationRepository $locationRepository) {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse([]);
        }
        $data = [];
        foreach ($locationRepository->typeaheadQuery($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => (string) $result,
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/new", name="location_new", methods={"GET","POST"})
     * @Template()
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @return array|RedirectResponse
     */
    public function new(Request $request, LinkManager $linkManager, ReferenceManager $referenceManager) {
        $location = new Location();
        $form = $this->createForm(LocationType::class, $location, ['location' => $location]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($location);
            $em->flush();
            $linkManager->setLinks($location, $form->get('links')->getData());
            $referenceManager->setReferences($location, $form->get('references')->getData());
            $em->persist($location);
            $em->flush();
            $this->addFlash('success', 'The new location has been saved.');

            return $this->redirectToRoute('location_show', ['id' => $location->getId()]);
        }

        return [
            'location' => $location,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/new_popup", name="location_new_popup", methods={"GET","POST"})
     * @Template()
     * @IsGranted("ROLE_CONTENT_ADMIN")
     *
     * @return array|RedirectResponse
     */
    public function new_popup(Request $request, LinkManager $linkManager, ReferenceManager $referenceManager) {
        return $this->new($request, $linkManager, $referenceManager);
    }

    /**
     * Search and display results from the Geonames API in preparation for import.
     *
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/import", name="location_import", methods={"GET"})
     *
     * @Template
     *
     * @return array
     */
    public function importSearchAction(Request $request) {
        $q = $request->query->get('q');
        $results = [];
        if ($q) {
            $user = $this->getParameter('lim.geonames.username');
            $client = new GeoNamesClient($user);
            $results = $client->search([
                'name' => $q,
                'fcl' => ['A', 'P'],
                'lang' => 'en',
            ]);
        }

        return [
            'q' => $q,
            'results' => $results,
        ];
    }

    /**
     * Import one or more search results from the Geonames API.
     *
     * @throws Exception
     *
     * @return RedirectResponse
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/import", name="location_import_save", methods={"POST"})
     */
    public function importSaveAction(Request $request, EntityManagerInterface $em) {
        $user = $this->getParameter('lim.geonames.username');
        $client = new GeoNamesClient($user);
        $repo = $em->getRepository(Location::class);
        foreach ($request->request->get('geonameid') as $geonameid) {
            $data = $client->get([
                'geonameId' => $geonameid,
                'lang' => 'en',
            ]);
            if ($repo->find($geonameid)) {
                $this->addFlash('warning', "Geoname #{$geonameid} ({$data->asciiName}) is already in the database.");

                continue;
            }
            $location = new Location();
            $location->setGeonameid($data->geonameId);
            $location->setName($data->name);
            $alternateNames = [];
            $location->setLatitude($data->lat);
            $location->setLongitude($data->lng);
            $location->setFclass($data->fcl);
            $location->setFcode($data->fcode);
            if(isset($data->countryCode)) {
                $location->setCountry($data->countryCode);
            }
            $em->persist($location);
        }
        $em->flush();
        $this->addFlash('success', 'The selected geonames have been imported.');

        return $this->redirectToRoute('location_import', [$request->query->get('q')]);
    }


    /**
     * @Route("/{id}", name="location_show", methods={"GET"})
     * @Template()
     *
     * @return array
     */
    public function show(Location $location) {
        return [
            'location' => $location,
        ];
    }

    /**
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="location_edit", methods={"GET","POST"})
     *
     * @Template()
     *
     * @return array|RedirectResponse
     */
    public function edit(Request $request, Location $location, LinkManager $linkManager) {
        $form = $this->createForm(LocationType::class, $location, ['location' => $location]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $linkManager->setLinks($location, $form->get('links')->getData());
            $em->flush();
            $this->addFlash('success', 'The updated location has been saved.');

//            return $this->redirectToRoute('location_show', ['id' => $location->getId()]);
        }

        return [
            'location' => $location,
            'form' => $form->createView(),
        ];
    }

    /**
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}", name="location_delete", methods={"DELETE"})
     *
     * @return RedirectResponse
     */
    public function delete(Request $request, Location $location) {
        if ($this->isCsrfTokenValid('delete' . $location->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($location);
            $entityManager->flush();
            $this->addFlash('success', 'The location has been deleted.');
        }

        return $this->redirectToRoute('location_index');
    }
}
