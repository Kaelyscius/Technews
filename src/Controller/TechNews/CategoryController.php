<?php
/**
 * Created by PhpStorm.
 * User: Etudiant0
 * Date: 29/06/2018
 * Time: 13:02
 */

namespace App\Controller\TechNews;

use App\Category\CategoryRequest;
use App\Category\CategoryRequestHandler;
use App\Category\CategoryType;
use App\Controller\HelperTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    use HelperTrait;

    /**
     * Formulaire d'ajout d'un article
     * @Route("/{_locale}/creer-une-categorie", name="category_add")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @param \App\Category\CategoryRequestHandler      $categoryRequestHandler
     *
     * @return void
     */
    public function addCategory(Request $request, CategoryRequestHandler $categoryRequestHandler)
    {
        $category = new CategoryRequest(); #Pour laisser notre service faire le boulot.

        #Créer un Formulaire permettant l'ajout d'un Article
        $form = $this->createForm(CategoryType::class, $category)->handleRequest($request);

        #Verification des données du formulaire
        if ($form->isSubmitted() && $form->isValid()) {

            /**
             * Une fois le formulaire soumis et valide on passe nos données directement au service
             * qui se chargera du trainement.
             */
            $category = $categoryRequestHandler->handle($category);

            #Message flash
            $this->addFlash('notice', 'Article bien crée !');

            #Redirection sur l'article qui vient d'être crée.
            return $this->redirectToRoute('index_category', [
                'category' => $category->getSlug()
            ]);
        }

        #Affichage du formulaire dans la vue
        return $this->render('category/addcategory.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
