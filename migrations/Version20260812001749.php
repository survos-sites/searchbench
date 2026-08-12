<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Widen Amst.citationUrl -- missed in the prior two passes because its JSONL
 * key is snake_case (citation_url) while every other overflowing column used
 * camelCase, so the earlier by-key length scan silently skipped it.
 */
final class Version20260812001749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Widen Amst.citation_url (65 chars in real data, was declared 53)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE amst ALTER citation_url TYPE VARCHAR(90)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE amst ALTER citation_url TYPE VARCHAR(53)');
    }
}
