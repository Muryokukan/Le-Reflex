<?php

namespace App\Controller;

use App\Repository\PizzaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PizzaController extends AbstractController
{
    #[Route('/pizza', name: 'app_pizza_menu')]
    public function menu(PizzaRepository $pizzaRepository): Response
    {
        $pizzas = $pizzaRepository->findAllWithToppings();
        
        foreach ($pizzas as $pizza) {

            $pizza->setPrice($pizza->getPrice());
            foreach ($pizza->getToppings() as $topping) {
                $topping->setAdditionalPrice($topping->getAdditionalPrice());
            }
        }
        
        
        return $this->render('pizza/menu.html.twig', [
            'pizzas' => $pizzas,
        ]);
    }
}