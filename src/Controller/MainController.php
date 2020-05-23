<?php

namespace App\Controller;

use App\Entity\Rate;
use App\Form\RateType;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="main_")
 */
class MainController extends AbstractController
{
    /**
     * Method for the home page to show the 3 lastest and the 3 gradest recipes
     * @Route("/", name="home")
     */
    public function home(RecipeRepository $recipeRepository, Request $request)
    {
        $rate = new Rate;
        $form = $this->createForm(RateType::class, $rate);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            // Comme EntityManagerInterface n'est utilisé que dans le if autant le mettre
            // là pour ne pas surcharger le paramconverter et ma méthode en chargement 
            $em = $this->getDoctrine()->getManager();
            $em->persist($rate);
            $em->flush();

            return $this->redirectToRoute('main_home');
        }

        return $this->render('main/home.html.twig', [
            'bestRecipes'=>$recipeRepository->findBestRecipes(),
            'latestRecipes' => $recipeRepository->findLatestRecipes(),
            'form' => $form->createView(),
        ]);
    }
}
