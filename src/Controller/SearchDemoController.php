<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Proof-of-concept pages for the api-platform/meilisearch PR: the same
 * api-grid Twig component, pointed at a Movie GetCollection operation
 * backed by ApiPlatform\Meilisearch\State\CollectionProvider instead of
 * the Doctrine ORM one -- demonstrating that api-grid (and anything else
 * consuming a GetCollection IRI) gets Meilisearch search "for free" once
 * the provider is wired on the resource, no api-grid changes required.
 */
final class SearchDemoController extends AbstractController
{
    #[Route('/demo/movie-meili-grid', name: 'demo_movie_meili_grid', methods: ['GET'])]
    public function movieMeiliGrid(): Response
    {
        return $this->render('demo/movie_meili_grid.html.twig', [
            'class' => Movie::class,
        ]);
    }
}
