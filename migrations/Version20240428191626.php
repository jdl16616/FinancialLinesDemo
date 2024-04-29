<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240428191626 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE creditnote DROP FOREIGN KEY creditnote_company_FK');
        $this->addSql('ALTER TABLE creditnote DROP FOREIGN KEY creditnote_company_FK_1');
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY invoice_company_FK');
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY invoice_company_FK_1');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY payment_company_FK_1');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY payment_company_FK');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE creditnote');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE payment');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(100) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE creditnote (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, creditor BIGINT UNSIGNED NOT NULL, debtor BIGINT UNSIGNED NOT NULL, reference VARCHAR(100) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, createdAt DATE DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL, amount DOUBLE PRECISION DEFAULT NULL, INDEX creditnote_company_FK (creditor), INDEX creditnote_company_FK_1 (debtor), PRIMARY KEY(id)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE invoice (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, creditor BIGINT UNSIGNED NOT NULL, debtor BIGINT UNSIGNED NOT NULL, reference VARCHAR(100) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, creationDate DATE DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL, endDate DATE NOT NULL, amount DOUBLE PRECISION DEFAULT NULL, INDEX invoice_company_FK (creditor), INDEX invoice_company_FK_1 (debtor), PRIMARY KEY(id)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE payment (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, creditor BIGINT UNSIGNED NOT NULL, debtor BIGINT UNSIGNED NOT NULL, reference VARCHAR(100) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, createdAt DATE DEFAULT \'CURRENT_TIMESTAMP\', amount DOUBLE PRECISION DEFAULT NULL, iban VARCHAR(37) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, INDEX payment_company_FK (creditor), INDEX payment_company_FK_1 (debtor), PRIMARY KEY(id)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE creditnote ADD CONSTRAINT creditnote_company_FK FOREIGN KEY (creditor) REFERENCES company (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE creditnote ADD CONSTRAINT creditnote_company_FK_1 FOREIGN KEY (debtor) REFERENCES company (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT invoice_company_FK FOREIGN KEY (creditor) REFERENCES company (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT invoice_company_FK_1 FOREIGN KEY (debtor) REFERENCES company (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT payment_company_FK_1 FOREIGN KEY (debtor) REFERENCES company (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT payment_company_FK FOREIGN KEY (creditor) REFERENCES company (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
