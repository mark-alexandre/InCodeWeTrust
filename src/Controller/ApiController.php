<?php

namespace App\Controller;

use App\Form\ApiFormType;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenFoodFacts;

/**
 * Class ApiController
 * @package App\Controller
 * @Route("/api-advice")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/", name="api")
     * @param Request $request
     * @return Response
     * @throws InvalidArgumentException
     * @throws OpenFoodFacts\Exception\BadRequestException
     */
    public function index(Request $request)
    {
        $form = $this->createForm(
            ApiFormType::class);
        $form->handleRequest($request);
        $prod = [];

        if ($form->isSubmitted() && $form->isValid())
        {

            $data =$form->getData();
            $api = new OpenFoodFacts\Api('food', 'fr');

            $product = $data['foodSearch'];
            $products = $api->search($product,1,50);

            $rateSalt = $data['number'];

            foreach ($products as $k) {
                if (isset($k->getData()['nutriments']['salt'])) {
                    if ($k->getData()['nutriments']['salt'] < $rateSalt) {
                        $prod[] = $k;
                    }
                }
            }
        }

        return $this->render('admin/api/index.html.twig', [
            'controller_name' => 'ApiController',
            'form' => $form->createView(),
            'pro' => $prod
        ]);
    }
}
