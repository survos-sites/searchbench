<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Movie;
use App\Schema\MovieSchema;
use Spatie\SchemaOrg\Graph;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class MovieController extends AbstractController
{
    #[Route('/movie/{id}', name: 'movie_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Movie $movie, Request $request, MovieSchema $schema, Graph $graph): Response
    {
        $canonicalUrl = $this->generateUrl(
            'movie_show',
            ['id' => $movie->id],
            UrlGeneratorInterface::ABSOLUTE_URL,
        );

        $schema->addToGraph($movie, $request->getSchemeAndHttpHost(), $canonicalUrl, $graph);

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
            'canonicalUrl' => $canonicalUrl,
        ]);
    }
}
