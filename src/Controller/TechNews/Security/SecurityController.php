<?php

namespace App\Controller\TechNews\Security;

use App\Form\LoginType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * Connexion d'un utilisateur
     * @Route("/{_locale}/connexion", name="security_login")
     *
     * @param \Symfony\Component\HttpFoundation\Request                           $request
     * @param \Symfony\Component\Security\Http\Authentication\AuthenticationUtils $authenticationUtils
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {

        #SI notre user est authentifié on le redirige sur la page d'accueil
        if ($this->getUser()) {
            return $this->redirectToRoute('index');
        }

        #Récupération du Formulaire :
        $form = $this->createForm(LoginType::class, [
           'email' =>  $authenticationUtils->getLastUsername()
        ]);

        #Recupération du message d'erreur si il y en a un
        $error = $authenticationUtils->getLastAuthenticationError();

        #Dernier email saisie par l'utilisateur :
//        $email = $authenticationUtils->getLastUsername(); #Car on le passe par défaut a notre création de formulaire

        #Transmission a la vue :

        return $this->render('security/login.html.twig', [
           'form' => $form->createView(),
           'error' => $error
        ]);
    }

    /**
     * @Route({
     *     "fr": "/deconnexion",
     *     "en": "/logout"
     * }, name="security_logout")
     */
    public function logout()
    {
        return;
    }

    /**
     * Vous pourriez définir ici :
     * La logique : Mot de passe oublié, réinitialisation du mot de passe etc...
     *
     */
}
