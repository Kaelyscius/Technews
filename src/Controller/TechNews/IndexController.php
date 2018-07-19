<?php

namespace App\Controller\TechNews;

use App\Article\ArticleCatalogue;
use App\Entity\Article;
use App\Entity\Category;
use App\Exception\DuplicateCatalogueArticleException;
use App\Service\Article\YamlProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class IndexController
 * @package App\Controller\TechNews
 */
class IndexController extends Controller
{
    /**
     * @Route(
     *     "/{_locale}",
     *     name="index",
     *     methods={"GET"}
     * )
     * Le paramètre method HTTP
     *
     * @param \App\Service\Article\YamlProvider $yamlProvider
     * @param \App\Article\ArticleCatalogue     $catalogue
     *
     * @return Response
     */
    public function index(YamlProvider $yamlProvider, ArticleCatalogue $catalogue): Response
    {

        # Récupération des Articles depuis YamlProvider
        # $articles = $yamlProvider->getArticles();
        # dump($articles);
        # Connexion au Repository
        $repository = $this->getDoctrine()
            ->getRepository(Article::class);
        # Récupération des articles depuis la BDD
        # $articles = $repository->findAll();
        $articles = $catalogue->findAll();
        $spotlight = $repository->findSpotlightArticles();
        # return new Response("<html><body><h1>PAGE D'ACCUEIL</h1></body></html>");
        return $this->render('index/index.html.twig', [
            'articles' => $articles,
            'spotlight' => $spotlight
        ]);
    }


    /**
     * Afficher les Articles d'une Catégorie
     * @Route("/{_locale}/category/{category}",
     *  name="index_category",
     *     methods={"GET"},
     *     defaults={"category":"computing"},
     *     requirements={"category":"\w+"})
     *
     * @param $category
     *
     * @return Response
     */
    public function category($category)
    {
        # Récupération de la catégorie
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['slug' => $category]);

        //La on trouve pas de catégorie
        if ($category === null) {
            return $this->redirectToRoute('index', [], Response::HTTP_MOVED_PERMANENTLY);
        }
        # Récupérer les articles de la catégorie
        $articles = $category->getArticles();

        # return new Response("<html><body><h1>PAGE CATEGORIE : $category</h1></body></html>");
        return $this->render('index/category.html.twig', [
            'category' => $category,
            'articles' => $articles
        ]);
    }

    /**
     * Affiche un article
     * @Route(
     *     "/{_locale}/{category}/{slug}_{id}.html",
     *     name="index_article",
     *     requirements={"id":"\d+"}
     * )
     * Le paramètre method HTTP
     * requirements permet de spécifier une facon de traiter un élément
     *
     * @param \App\Entity\Article $article
     *
     * @param                     $id
     * @param                     $slug
     *
     * @return Response
     */
    public function article(ArticleCatalogue $catalogue, $id): Response
    {
//        if (null === $article){
        ////            On génère une exception
        ////            throw $this->createNotFoundException(
        ////                'Nous n\'avons pas trouvé votre article ID : ' .$id
        ////            );
//            // Redirection permanente plus pro car on évite d'afficher une 404
//
//            return $this->redirectToRoute('index', [], Response::HTTP_MOVED_PERMANENTLY);
//
//        }
        # récupérer les suggestions d'articles

        try {
            $article = $catalogue->find($id);
        } catch (DuplicateCatalogueArticleException $catalogueArticleException) {
            return $this->redirectToRoute('index', [], Response::HTTP_MOVED_PERMANENTLY);
        }

        $suggestions = $this->getDoctrine()->getRepository(Article::class)
            ->findLastFiveArticles($article->getId(), $article->getCategory()->getId());
//        $article = $this->getDoctrine()
//            ->getRepository(Article::class)
//            ->find($id);
        #Business/une-formation-symfony-a-paris_494894.html
        return $this->render('index/article.html.twig', ['article' => $article, 'suggestions' => $suggestions]);
    }

    public function sidebar(?Article $article = null, ArticleCatalogue $catalogue)
    {
        #recupération du Repository

        $repository = $this->getDoctrine()->getRepository(Article::class);
        #Récupération des 5 derniers articles
        $specials = $repository->findSpecialArticles();
        #Récupération des articles a la position spécial
        $articles = $catalogue->findLastFiveArticles();

        #Rendu
        return $this->render('components/_sidebar.html.twig', [
            'articles' => $articles,
            'specials' => $specials,
            'article' => $article
        ]);
    }
}
