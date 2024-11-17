<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\Serializer\SerializerInterface;

class SearchController extends AbstractController
{
    private ProductRepository $productRepository;
    private SerializerInterface $serializer;
    private CacheInterface $cache;

    public function __construct(ProductRepository $productRepository, SerializerInterface $serializer, CacheInterface $cache)
    {
        $this->productRepository = $productRepository;
        $this->serializer = $serializer;
        $this->cache = $cache;
    }

    #[Route('/search', name: 'search', methods: ['GET'])]
    public function search(Request $request): Response
    {
        $name = $request->query->get('name', '');
        $category = $request->query->get('category', '');
        $price = $request->query->get('price', '');
        $id = $request->query->get('id', '');

        $filters = [];
        if ($name) $filters['name'] = $name;
        if ($category) $filters['category'] = $category;
        if ($price) $filters['price'] = $price;
        if ($id) $filters['id'] = $id;

        // Sorting and Pagination
        $sort = $request->query->get('sort', 'id');
        $page = (int)$request->query->get('page', 1);
        $perPage = (int)$request->query->get('perPage', 10);

        // Sanitize cache key to remove reserved characters
        $cacheKey = "search_results:" . md5(json_encode($filters) . $sort . $page . $perPage);
        $sanitizedCacheKey = preg_replace('/[{}()\/\\@:]/', '_', $cacheKey);

        // Fetch data from cache or database if not cached
        $results = $this->cache->get($sanitizedCacheKey, function() use ($filters, $sort, $page, $perPage) {
            return $this->productRepository->searchWithPagination($filters, $sort, $page, $perPage);
        });

        // Count the total products to calculate total pages for pagination
        $totalProducts = $this->productRepository->countFiltered($filters);
        $totalPages = ceil($totalProducts / $perPage);

        return $this->render('product/index.html.twig', [
            'products' => $results,
            'filters' => $filters, // Pass filters for pagination and export
            'page' => $page,
            'totalPages' => $totalPages,
            'perPage' => $perPage,
            'sort' => $sort,
            'name' => $name,
            'category' => $category,
            'price' => $price,
            'id' => $id,
        ]);
    }

    #[Route('/export', name: 'export', methods: ['GET'])]
    public function export(Request $request): StreamedResponse
    {
        $filters = [
            'name' => $request->query->get('name', ''),
            'category' => $request->query->get('category', ''),
            'price' => $request->query->get('price', ''),
            'id' => $request->query->get('id', ''),
        ];

        // Fetch data using the custom search method
        $data = $this->productRepository->search($filters, 'id');

        // Create a streamed response for CSV export
        $response = new StreamedResponse(function() use ($data) {
            $handle = fopen('php://output', 'w+');
            fputcsv($handle, ['ID', 'Name', 'Category', 'Price', 'Created At']); // CSV header

            foreach ($data as $product) {
                fputcsv($handle, [
                    $product['id'],
                    $product['name'],
                    $product['category'],
                    $product['price'],
                    $product['createdAt']->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="zia-assessment.csv"');
        return $response;
    }
}
