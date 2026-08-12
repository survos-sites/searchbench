<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Widen Amst columns that overflowed on the real amst_en.csv data (profiled from a too-narrow/Dutch-biased sample)
 */
final class Version20260812000220 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Widen Amst columns that overflowed on the real amst_en.csv data (profiled from a too-narrow/Dutch-biased sample)';
    }

    public function up(Schema $schema): void
    {
        // Scoped by hand to just the Amst column widening -- doctrine:migrations:diff also
        // picked up a large amount of unrelated pending schema drift (new WikiData/Media/
        // FetchPage/etc. tables, a `DROP TABLE wmca`) from other entities that predate this
        // change. Not touching that here; it needs its own explicit review, especially the
        // destructive DROP TABLE.
        $this->addSql('ALTER TABLE amst ALTER munt_land_geografisch TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE amst ALTER aardewerk_ds_type TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE amst ALTER rookpijpen_kopopening TYPE VARCHAR(32)');
        $this->addSql('ALTER TABLE amst ALTER metaal_deelmaterialen TYPE VARCHAR(64)');
        $this->addSql('ALTER TABLE amst ALTER plant_soort TYPE VARCHAR(48)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE amst ALTER munt_land_geografisch TYPE VARCHAR(9)');
        $this->addSql('ALTER TABLE amst ALTER aardewerk_ds_type TYPE VARCHAR(9)');
        $this->addSql('ALTER TABLE amst ALTER rookpijpen_kopopening TYPE VARCHAR(9)');
        $this->addSql('ALTER TABLE amst ALTER metaal_deelmaterialen TYPE VARCHAR(9)');
        $this->addSql('ALTER TABLE amst ALTER plant_soort TYPE VARCHAR(9)');
    }
}
