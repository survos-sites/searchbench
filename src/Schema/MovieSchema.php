<?php

declare(strict_types=1);

namespace App\Schema;

use App\Entity\Movie;
use Spatie\SchemaOrg\Graph;
use Spatie\SchemaOrg\Person;

final class MovieSchema
{
    public function addToGraph(Movie $movie, string $siteUrl, string $canonicalUrl, Graph $graph): void
    {
        $siteUrl = rtrim($siteUrl, '/');
        $websiteId = $siteUrl.'/#website';
        $webPageId = $canonicalUrl.'#webpage';
        $movieId = $canonicalUrl.'#movie';

        $website = $graph->webSite('website')
            ->identifier($websiteId)
            ->url($siteUrl)
            ->name('SearchBench');

        $movieNode = $graph->movie('movie-'.$movie->id)
            ->identifier($movieId)
            ->url($canonicalUrl);

        if ($this->hasText($movie->title)) {
            $movieNode->name(trim($movie->title));
        }

        if ($this->hasText($movie->overview)) {
            $movieNode->description(trim($movie->overview));
        }

        if ($movie->year !== null) {
            $movieNode->dateCreated((string) $movie->year);
        }

        $genres = $this->cleanStrings($movie->genres);
        if ($genres !== []) {
            $movieNode->genre($genres);
        }

        if ($this->hasText($movie->director)) {
            $movieNode->director($this->person($graph, $siteUrl, $movie->director)->referenced());
        }

        $actors = array_map(
            fn (string $actor) => $this->person($graph, $siteUrl, $actor)->referenced(),
            $this->cleanStrings($movie->actors),
        );
        if ($actors !== []) {
            $movieNode->actor($actors);
        }

        if ($this->hasText($movie->posterUrl)) {
            $image = $graph->imageObject('poster-'.$movie->id)
                ->identifier($movieId.'-poster')
                ->contentUrl(trim($movie->posterUrl));

            if ($this->hasText($movie->title)) {
                $image->name(trim($movie->title).' poster');
            }

            $movieNode->image($image->referenced());
        }

        $rating = filter_var($movie->rating, FILTER_VALIDATE_FLOAT);
        if ($rating !== false && $rating >= 0.0 && $rating <= 10.0 && $movie->votes !== null && $movie->votes > 0) {
            $aggregateRating = $graph->aggregateRating('movie-'.$movie->id)
                ->identifier($movieId.'-rating')
                ->ratingValue($rating)
                ->ratingCount($movie->votes)
                ->worstRating(0)
                ->bestRating(10);

            $movieNode->aggregateRating($aggregateRating->referenced());
        }

        $webPage = $graph->webPage('movie-'.$movie->id)
            ->identifier($webPageId)
            ->url($canonicalUrl)
            ->isPartOf($website->referenced())
            ->mainEntity($movieNode->referenced());

        if ($this->hasText($movie->title)) {
            $webPage->name(trim($movie->title));
        }

        $movieNode->mainEntityOfPage($webPage->referenced());
    }

    private function person(Graph $graph, string $siteUrl, string $name): Person
    {
        $name = trim($name);
        $key = hash('xxh128', mb_strtolower($name));

        return $graph->person($key)
            ->identifier($siteUrl.'/people/'.rawurlencode(mb_strtolower($name)))
            ->name($name);
    }

    /**
     * @param array<array-key, mixed>|null $values
     *
     * @return list<string>
     */
    private function cleanStrings(?array $values): array
    {
        if ($values === null) {
            return [];
        }

        $strings = array_map(
            static fn (string $value): string => trim($value),
            array_filter($values, static fn (mixed $value): bool => is_string($value) && trim($value) !== ''),
        );

        return array_values(array_unique($strings));
    }

    private function hasText(?string $value): bool
    {
        return $value !== null && trim($value) !== '';
    }
}
