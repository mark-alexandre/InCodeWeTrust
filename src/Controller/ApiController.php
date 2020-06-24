<?php

namespace App\Controller;

use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenFoodFacts;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/{product}/{txsalt}", name="api")
     * @param float $txsalt
     * @return Response
     * @throws OpenFoodFacts\Exception\BadRequestException
     * @throws InvalidArgumentException
     */
    public function index(string $product, float $txsalt)
    {
        $api = new OpenFoodFacts\Api('food','fr');

        $products = $api->search($product);

        $pro =[];
        foreach($products as $k)
        {
            if (isset($k->getData()['nutriments']['salt']))
            {
                if ($k->getData()['nutriments']['salt']< $txsalt)
                {
                    $pro[] = $k;
                }
            }

        }

        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
            'product' => $products,
            'pro' => $pro
        ]);
    }
}
