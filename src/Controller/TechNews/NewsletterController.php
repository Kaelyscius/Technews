<?php

namespace App\Controller\TechNews;

use App\Newsletter\NewsletterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class NewsletterController extends Controller
{
    /**
     * @Route("/newsletter")
     * Affichage d'un Modal Newsletter
     */
    public function newsletter()
    {

        #Récupération du formulaire
        $form = $this->createForm(NewsletterType::class);

        #affichage de la newsletter
        return $this->render('newsletter/_modal.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
