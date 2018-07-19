<?php

namespace App\Controller\TechNews;

use App\User\UserRequest;
use App\User\UserRequestHandler;
use App\User\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

    /**
     * Formulaire d'ajout d'un article
     * @Route({
     *     "fr": "/{_locale}/inscription",
     *     "en": "/{_locale}/register"
     * }, name="security_register")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @param \App\User\UserRequestHandler              $userRequestHandler
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function register(Request $request, UserRequestHandler $userRequestHandler): Response
    {
        $user = new UserRequest(); #Pour laisser notre service faire le boulot.

        #Créer un Formulaire permettant l'ajout d'un Article
        $form = $this->createForm(UserType::class, $user)->handleRequest($request);

        #Verification des données du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            #Persister les données

            $user = $userRequestHandler->handleAsUser($user);

            #Message flash
            $this->addFlash('notice', 'Utilisateur bien crée !');

            #Redirection sur l'article qui vient d'être crée.
            return $this->redirectToRoute('security_login');
        }

        #Affichage du formulaire dans la vue
        return $this->render('user/adduser.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
