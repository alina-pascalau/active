<?php
    
namespace App\Controller;
    
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\JsonResponse;
    
class ApiController extends AbstractController
{
    
    /**
     * @var integer status code - default 200 
     */
    protected $statusCode = 200;
    
    /**
     * @Route("/{reactRouting}", name="home", defaults={"reactRouting": null})
     */
    public function index()
    {
        return $this->render('default/index.html.twig');
    }
    
    /**
     * @Route("/api/categories", name="categories" , methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getCategories(): JsonResponse
    {
       
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
       
        foreach ($categories as $key => $category) {
            
            $products = $category->getProducts();
            $productsArray = [];
            foreach ($products as $product)
            {
                $productsArray[] = [
                    'id' => $product->getId(),
                    'name' => $product->getName()
                ];
            }
            $categoriesArray[$key]['products'] = $productsArray;
            $categoriesArray[$key]['id'] = $category->getId();
            $categoriesArray[$key]['name'] = $category->getName();
            $categoriesArray[$key]['description'] = $category->getDescription();
        }
       
        return $this->respond($categoriesArray);
        
        
    }
    
     /**
     * @Route("/api/product/{id}", name="delete_product", methods={"DELETE"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteProduct(int $id): JsonResponse
    {
        
            $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
            if (!$product) {
                 return $this->respondNotFound('Product not found');
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();

             $data = [
                'success' => "Product was deleted successfully",
            ];
            return $this->respond($data);
        
    }
    
    
    /**
     * @Route("/api/product/{id}", name="get_product", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getProduct(int $id): JsonResponse
    {
            $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
            if (!$product) {
                 return $this->respondNotFound('Product not found');
            }

             $data = [
                'id' => $product->getId(),
                 'name' => $product->getName(),
                 'description' => $product->getDescription(),
                 'categoryId' => $product->getCategory()->getId(),
                 'price' => $product->getPrice()
            ];
             
            return $this->respond($data);
        
    }
    
    
    /**
     * @Route("/api/product/{id}", name="update_product", methods={"PUT"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function updateProduct(int $id, Request $request): JsonResponse
    {
            //validate params
            $dataRequest = $this->validateRequest($request);
            if (! $dataRequest) {
                return $this->respondValidationError('Please provide a valid request!');
            }
            if (! isset($dataRequest['name'])) {
                return $this->respondValidationError('Please provide a name!');
            }
            if (! isset($dataRequest['description'])) {
                return $this->respondValidationError('Please provide a description!');
            }
            if (! isset($dataRequest['price'])) {
                return $this->respondValidationError('Please provide a price!');
            }
             if (floatval($dataRequest['price']) == 0) {
                return $this->respondValidationError('Please provide a valid price!');
            }
            if (! isset($dataRequest['category'])) {
                return $this->respondValidationError('Please provide a category!');
            }

            $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
            if (!$product) {
                 return $this->respondNotFound('Product not found');
            }
            
            $category = $this->getDoctrine()->getRepository(Category::class)->find($dataRequest['category']);
            
            if (!$category) {
                return $this->respondNotFound('Category not found');
            }
            
            //update the product
            $product->setName($dataRequest['name']);
            $product->setDescription($dataRequest['description']);
            $product->setPrice($dataRequest['price']);
            $product->setCategory($category);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            $data = [
                'id' => $product->getId()
            ];
            return $this->respond($data);

    }
    
     /**
     * @Route("/api/product", name="product" , methods={"POST"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createProduct(Request $request): JsonResponse
    {
            //validate params
            $dataRequest = $this->validateRequest($request);
            if (! $dataRequest) {
                return $this->respondValidationError('Please provide a valid request!');
            }
             if (! isset($dataRequest['name'])) {
                return $this->respondValidationError('Please provide a name!');
            }
            if (! isset($dataRequest['description'])) {
                return $this->respondValidationError('Please provide a description!');
            }
            if (! isset($dataRequest['price'])) {
                return $this->respondValidationError('Please provide a price!');
            }
             if (floatval($dataRequest['price']) == 0) {
                return $this->respondValidationError('Please provide a valid price!');
            }
            if (! isset($dataRequest['category'])) {
                return $this->respondValidationError('Please provide a category!');
            }

            $category = $this->getDoctrine()->getRepository(Category::class)->find($dataRequest['category']);
             if (!$category) {
                return $this->respondNotFound('Category not found');
            }

            $product = new Product();
            $product->setName($dataRequest['name']);
            $product->setDescription($dataRequest['description']);
            $product->setPrice($dataRequest['price']);
            $product->setCategory($category);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            $data = [
                'success' => "Product was added successfully",
                'id' => $product->getId()
            ];
            return $this->respond($data);
        
    }
    
    /**
     * Validates the request
     * 
     * @param Request $request
     * @return array|null
     * @throws \Exception
     */
    private function validateRequest(Request $request): ?array 
    {
        $data = json_decode($request->getContent(), true);

        return $data;
    }

    /**
     * Gets status code value
     *
     * @return integer
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Sets the value of the status code.
     *
     * @param integer $statusCode
     * @return self
     */
    protected function setStatusCode($statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Returns a JSON response
     *
     * @param array $data
     * @param array $headers
     * @return JsonResponse
     */
    public function respond($data, $headers = []): JsonResponse
    {
        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    /**
     * Creates a response with errors
     *
     * @param string $errors
     * @return JsonResponse
     */
    public function respondWithErrors($errors, $headers = []): JsonResponse
    {
        $data = [
            'errors' => $errors,
        ];

        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    /**
     * Creates a response with error Unauthorized access
     *
     * @param string $message
     * @return JsonResponse
     */
    public function respondUnauthorized($message = 'Unauthorized access!'): JsonResponse
    {
        return $this->setStatusCode(401)->respondWithErrors($message);
    }

    /**
     * Creates a response with Validation error
     *
     * @param string $message
     * @return JsonResponse
     */
    public function respondValidationError($message = 'Validation errors')
    {
        return $this->setStatusCode(422)->respondWithErrors($message);
    }

    /**
     * Creates a response with error Not found
     *
     * @param string $message
     * @return JsonResponse
     */
    public function respondNotFound($message = 'Not found!'): JsonResponse
    {
        return $this->setStatusCode(404)->respondWithErrors($message);
    }

    /**
     * Creates a response with status 201
     *
     * @param array $data
     * @return JsonResponse
     */
    public function respondCreated($data = []): JsonResponse
    {
        return $this->setStatusCode(201)->respond($data);
    }
}