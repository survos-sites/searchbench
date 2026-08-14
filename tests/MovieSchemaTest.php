<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class MovieSchemaTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $_ENV['DATABASE_URL'] = 'sqlite:///:memory:';
        $_SERVER['DATABASE_URL'] = 'sqlite:///:memory:';

        $this->client = self::createClient();

        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        self::assertInstanceOf(EntityManagerInterface::class, $entityManager);

        (new SchemaTool($entityManager))->createSchema([
            $entityManager->getClassMetadata(Movie::class),
        ]);
    }

    public function testMoviePageEmitsLinkedSchemaOrgGraph(): void
    {
        $movie = $this->movie(900001);
        $this->persist($movie);

        $crawler = $this->client->request('GET', '/movie/900001');

        self::assertResponseIsSuccessful();
        self::assertRouteSame('movie_show');

        $scripts = $crawler->filter('script[type="application/ld+json"]');
        self::assertCount(1, $scripts);

        $payload = json_decode($scripts->text(), true, flags: JSON_THROW_ON_ERROR);
        self::assertSame('https://schema.org', $payload['@context']);

        $nodes = [];
        foreach ($payload['@graph'] as $node) {
            $nodes[$node['@id']] = $node;
        }

        $canonicalUrl = 'http://localhost/movie/900001';
        $movieNode = $nodes[$canonicalUrl.'#movie'];
        $pageNode = $nodes[$canonicalUrl.'#webpage'];

        self::assertSame('Movie', $movieNode['@type']);
        self::assertSame('Schema Test Movie', $movieNode['name']);
        self::assertSame(['Drama', 'Science Fiction'], $movieNode['genre']);
        self::assertSame(['@id' => $canonicalUrl.'#movie'], $pageNode['mainEntity']);
        self::assertSame(['@id' => $canonicalUrl.'#webpage'], $movieNode['mainEntityOfPage']);
        self::assertSame('AggregateRating', $nodes[$canonicalUrl.'#movie-rating']['@type']);
        self::assertSame(42, $nodes[$canonicalUrl.'#movie-rating']['ratingCount']);
        self::assertArrayNotHasKey('budget', $movieNode);
    }

    public function testMoviePageRendersUntrustedTextScriptSafely(): void
    {
        $movie = $this->movie(900002);
        $movie->overview = 'Untrusted </script><script>alert("schema")</script> text';
        $this->persist($movie);

        $crawler = $this->client->request('GET', '/movie/900002');

        self::assertResponseIsSuccessful();
        self::assertCount(1, $crawler->filter('script[type="application/ld+json"]'));
        self::assertStringNotContainsString('</script><script>alert', $this->client->getResponse()->getContent() ?: '');
        self::assertStringContainsString('\\u003C/script\\u003E', $this->client->getResponse()->getContent() ?: '');

        $payload = json_decode($crawler->filter('script[type="application/ld+json"]')->text(), true, flags: JSON_THROW_ON_ERROR);
        $movieNode = array_values(array_filter(
            $payload['@graph'],
            static fn (array $node): bool => ($node['@type'] ?? null) === 'Movie',
        ))[0];

        self::assertSame($movie->overview, $movieNode['description']);
    }

    private function movie(int $id): Movie
    {
        $movie = new Movie();
        $movie->id = $id;
        $movie->title = 'Schema Test Movie';
        $movie->overview = 'A movie used to verify JSON-LD output.';
        $movie->genres = ['Drama', '', 'Science Fiction'];
        $movie->director = 'Example Director';
        $movie->actors = ['First Actor', 'Second Actor'];
        $movie->year = 2026;
        $movie->votes = 42;
        $movie->rating = '8.4';
        $movie->posterUrl = 'https://example.com/poster.jpg';

        return $movie;
    }

    private function persist(Movie $movie): void
    {
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        self::assertInstanceOf(EntityManagerInterface::class, $entityManager);

        $entityManager->persist($movie);
        $entityManager->flush();
    }
}
