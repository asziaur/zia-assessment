<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use DateTime;

class InsertDummyProductDataCommand extends Command
{
    // Set the command name
    protected static $defaultName = 'app:insert-dummy-products';

    private EntityManagerInterface $entityManager;

    // Constructor injection of EntityManager
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this->setName('app:insert-dummy-products');
        $this->setDescription('Inserts 15 rows of dummy data into the product table');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Sample dummy data
        $dummyData = [
            ['name' => 'Product 1', 'price' => 10.99, 'created_at' => new DateTime(), 'category' => 'Electronics'],
            ['name' => 'Product 2', 'price' => 15.49, 'created_at' => new DateTime(), 'category' => 'Electronics'],
            ['name' => 'Product 3', 'price' => 25.00, 'created_at' => new DateTime(), 'category' => 'Furniture'],
            ['name' => 'Product 4', 'price' => 99.99, 'created_at' => new DateTime(), 'category' => 'Furniture'],
            ['name' => 'Product 5', 'price' => 7.50, 'created_at' => new DateTime(), 'category' => 'Clothing'],
            ['name' => 'Product 6', 'price' => 45.99, 'created_at' => new DateTime(), 'category' => 'Clothing'],
            ['name' => 'Product 7', 'price' => 12.99, 'created_at' => new DateTime(), 'category' => 'Books'],
            ['name' => 'Product 8', 'price' => 5.99, 'created_at' => new DateTime(), 'category' => 'Books'],
            ['name' => 'Product 9', 'price' => 199.99, 'created_at' => new DateTime(), 'category' => 'Appliances'],
            ['name' => 'Product 10', 'price' => 399.99, 'created_at' => new DateTime(), 'category' => 'Appliances'],
            ['name' => 'Product 11', 'price' => 8.99, 'created_at' => new DateTime(), 'category' => 'Toys'],
            ['name' => 'Product 12', 'price' => 22.00, 'created_at' => new DateTime(), 'category' => 'Toys'],
            ['name' => 'Product 13', 'price' => 50.50, 'created_at' => new DateTime(), 'category' => 'Beauty'],
            ['name' => 'Product 14', 'price' => 19.99, 'created_at' => new DateTime(), 'category' => 'Beauty'],
            ['name' => 'Product 15', 'price' => 60.00, 'created_at' => new DateTime(), 'category' => 'Sports'],
        ];

        foreach ($dummyData as $data) {
            $product = new Product();
            $product->setName($data['name']);
            $product->setPrice($data['price']);
            $product->setCreatedAt($data['created_at']);
            $product->setCategory($data['category']);
            
            $this->entityManager->persist($product);
        }

        // Commit the changes to the database
        $this->entityManager->flush();

        $output->writeln('15 dummy products have been inserted successfully.');

        return Command::SUCCESS;
    }
}
