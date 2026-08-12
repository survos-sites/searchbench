<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260812001112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Widen the remaining Amst columns that overflowed on real amst_en.csv data';
    }

    public function up(Schema $schema): void
    {
        // Same scoping note as Version20260812000220: doctrine:migrations:diff picked up
        // unrelated pending drift (new WikiData/Media/FetchPage/CommandProcess tables, a
        // destructive `DROP TABLE wmca`, product.exact_price) from other entities. Trimmed
        // by hand to just the Amst column widenings measured against the real dataset.
        $this->addSql('ALTER TABLE amst ALTER vondstnummer TYPE VARCHAR(30)');
        $this->addSql('ALTER TABLE amst ALTER code TYPE VARCHAR(30)');
        $this->addSql('ALTER TABLE amst ALTER project_code TYPE VARCHAR(30)');
        $this->addSql('ALTER TABLE amst ALTER subcategorie TYPE VARCHAR(70)');
        $this->addSql('ALTER TABLE amst ALTER object TYPE VARCHAR(80)');
        $this->addSql('ALTER TABLE amst ALTER niveau2 TYPE VARCHAR(130)');
        $this->addSql('ALTER TABLE amst ALTER niveau3 TYPE VARCHAR(260)');
        $this->addSql('ALTER TABLE amst ALTER niveau4 TYPE VARCHAR(190)');
        $this->addSql('ALTER TABLE amst ALTER munt_autoriteit_politiek TYPE VARCHAR(70)');
        $this->addSql('ALTER TABLE amst ALTER munt_muntsoort TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE amst ALTER munt_muntplaats_productieplaats TYPE VARCHAR(60)');
        $this->addSql('ALTER TABLE amst ALTER image TYPE VARCHAR(110)');
        $this->addSql('ALTER TABLE amst ALTER trefwoorden TYPE VARCHAR(170)');
        $this->addSql('ALTER TABLE amst ALTER vak TYPE VARCHAR(30)');
        $this->addSql('ALTER TABLE amst ALTER aardewerk_herkomst TYPE VARCHAR(60)');
        $this->addSql('ALTER TABLE amst ALTER aardewerk_opp_behandeling TYPE VARCHAR(90)');
        $this->addSql('ALTER TABLE amst ALTER aardewerk_decorgroepen TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE amst ALTER glas_kleur TYPE VARCHAR(70)');
        $this->addSql('ALTER TABLE amst ALTER glas_herkomst TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE amst ALTER objectdeel TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE amst ALTER rookpijpen_model TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE amst ALTER aardewerk_merk TYPE VARCHAR(60)');
        $this->addSql('ALTER TABLE amst ALTER bouwmaterialen_afbeelding TYPE VARCHAR(40)');
        $this->addSql('ALTER TABLE amst ALTER rookpijpen_steelbehandeling TYPE VARCHAR(60)');
        $this->addSql('ALTER TABLE amst ALTER rookpijpen_productiecentrum TYPE VARCHAR(60)');
        $this->addSql('ALTER TABLE amst ALTER rookpijpen_oppervlaktebehandeling_kop TYPE VARCHAR(40)');
        $this->addSql('ALTER TABLE amst ALTER rookpijpen_radering TYPE VARCHAR(70)');
        $this->addSql('ALTER TABLE amst ALTER rookpijpen_bijmerk_rechts TYPE VARCHAR(60)');
        $this->addSql('ALTER TABLE amst ALTER rookpijpen_merk_of_hielmerk TYPE VARCHAR(60)');
        $this->addSql('ALTER TABLE amst ALTER rookpijpen_pijpenmaker TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE amst ALTER rookpijpen_bijmerk_links TYPE VARCHAR(60)');
        $this->addSql('ALTER TABLE amst ALTER fauna_soort TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE amst ALTER kunststof_eigenaar TYPE VARCHAR(70)');
        $this->addSql('ALTER TABLE amst ALTER kunststof_eenheid_waarde TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE amst ALTER metaal_productiecentrum TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE amst ALTER fauna_element TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE amst ALTER past_aan_hoort_bij TYPE VARCHAR(420)');
        $this->addSql('ALTER TABLE amst ALTER natuursteen_subsoort TYPE VARCHAR(90)');
        $this->addSql('ALTER TABLE amst ALTER natuursteen_productiesporen TYPE VARCHAR(70)');
        $this->addSql('ALTER TABLE amst ALTER leer_deelmaterialen TYPE VARCHAR(40)');
        $this->addSql('ALTER TABLE amst ALTER leer_leersoort TYPE VARCHAR(30)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE amst ALTER vondstnummer TYPE VARCHAR(19)');
        $this->addSql('ALTER TABLE amst ALTER code TYPE VARCHAR(19)');
        $this->addSql('ALTER TABLE amst ALTER project_code TYPE VARCHAR(4)');
        $this->addSql('ALTER TABLE amst ALTER subcategorie TYPE VARCHAR(33)');
        $this->addSql('ALTER TABLE amst ALTER object TYPE VARCHAR(17)');
        $this->addSql('ALTER TABLE amst ALTER niveau2 TYPE VARCHAR(87)');
        $this->addSql('ALTER TABLE amst ALTER niveau3 TYPE VARCHAR(81)');
        $this->addSql('ALTER TABLE amst ALTER niveau4 TYPE VARCHAR(58)');
        $this->addSql('ALTER TABLE amst ALTER munt_autoriteit_politiek TYPE VARCHAR(8)');
        $this->addSql('ALTER TABLE amst ALTER munt_muntsoort TYPE VARCHAR(7)');
        $this->addSql('ALTER TABLE amst ALTER munt_muntplaats_productieplaats TYPE VARCHAR(8)');
        $this->addSql('ALTER TABLE amst ALTER image TYPE VARCHAR(80)');
        $this->addSql('ALTER TABLE amst ALTER trefwoorden TYPE VARCHAR(62)');
        $this->addSql('ALTER TABLE amst ALTER vak TYPE VARCHAR(5)');
        $this->addSql('ALTER TABLE amst ALTER aardewerk_herkomst TYPE VARCHAR(30)');
        $this->addSql('ALTER TABLE amst ALTER aardewerk_opp_behandeling TYPE VARCHAR(49)');
        $this->addSql('ALTER TABLE amst ALTER aardewerk_decorgroepen TYPE VARCHAR(19)');
        $this->addSql('ALTER TABLE amst ALTER glas_kleur TYPE VARCHAR(27)');
        $this->addSql('ALTER TABLE amst ALTER glas_herkomst TYPE VARCHAR(21)');
        $this->addSql('ALTER TABLE amst ALTER objectdeel TYPE VARCHAR(15)');
        $this->addSql('ALTER TABLE amst ALTER rookpijpen_model TYPE VARCHAR(13)');
        $this->addSql('ALTER TABLE amst ALTER aardewerk_merk TYPE VARCHAR(21)');
        $this->addSql('ALTER TABLE amst ALTER bouwmaterialen_afbeelding TYPE VARCHAR(12)');
        $this->addSql('ALTER TABLE amst ALTER rookpijpen_steelbehandeling TYPE VARCHAR(10)');
        $this->addSql('ALTER TABLE amst ALTER rookpijpen_productiecentrum TYPE VARCHAR(10)');
        $this->addSql('ALTER TABLE amst ALTER rookpijpen_oppervlaktebehandeling_kop TYPE VARCHAR(10)');
        $this->addSql('ALTER TABLE amst ALTER rookpijpen_radering TYPE VARCHAR(35)');
        $this->addSql('ALTER TABLE amst ALTER rookpijpen_bijmerk_rechts TYPE VARCHAR(21)');
        $this->addSql('ALTER TABLE amst ALTER rookpijpen_merk_of_hielmerk TYPE VARCHAR(18)');
        $this->addSql('ALTER TABLE amst ALTER rookpijpen_pijpenmaker TYPE VARCHAR(26)');
        $this->addSql('ALTER TABLE amst ALTER rookpijpen_bijmerk_links TYPE VARCHAR(26)');
        $this->addSql('ALTER TABLE amst ALTER fauna_soort TYPE VARCHAR(18)');
        $this->addSql('ALTER TABLE amst ALTER kunststof_eigenaar TYPE VARCHAR(28)');
        $this->addSql('ALTER TABLE amst ALTER kunststof_eenheid_waarde TYPE VARCHAR(7)');
        $this->addSql('ALTER TABLE amst ALTER metaal_productiecentrum TYPE VARCHAR(6)');
        $this->addSql('ALTER TABLE amst ALTER fauna_element TYPE VARCHAR(17)');
        $this->addSql('ALTER TABLE amst ALTER past_aan_hoort_bij TYPE VARCHAR(190)');
        $this->addSql('ALTER TABLE amst ALTER natuursteen_subsoort TYPE VARCHAR(14)');
        $this->addSql('ALTER TABLE amst ALTER natuursteen_productiesporen TYPE VARCHAR(8)');
        $this->addSql('ALTER TABLE amst ALTER leer_deelmaterialen TYPE VARCHAR(5)');
        $this->addSql('ALTER TABLE amst ALTER leer_leersoort TYPE VARCHAR(4)');
    }
}
