<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241115201524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create product table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('product');   
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['length' => 255]);
        $table->addColumn('price', 'float');
        $table->addColumn('created_at', 'datetime');
        $table->addColumn('category', 'string', ['length' => 255]);
        $table->setPrimaryKey(['id']);
        
        // Create an index on the "name" column
        $table->addIndex(['name'], 'idx_name');

    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('product');

    }
}
