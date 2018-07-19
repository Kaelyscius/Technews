<?php

namespace App\Controller\TechNews;

use App\Article\ArticleRequest;
use App\Article\ArticleRequestHandler;
use App\Article\ArticleRequestUpdateHandler;
use App\Article\ArticleType;
use App\Article\ArticleWorkflowHandler;
use App\Controller\HelperTrait;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\Exception\LogicException;

class ArticleController extends Controller
{
    use HelperTrait;

    /**
     * Démonstration de l'ajout d'un article
     * avec Doctrine (Data Mapper).
     *
     * @Route("/article", name="article")
     */
    public function index()
    {
        //Création de ma catégorie
        $category = new Category();
        $category->setName('Engineering');
        $category->setSlug('Engineering');

        //Création d'un utilisateur
        $user = new User();
        $user->setFirstname('Alex');
        $user->setLastname('Kael');
        $user->setEmail('alexandre.canivez@sfr.fr');
        $user->setPassword('MonMotDePasse');
        $user->setRoles(['ROLE_AUTEUR']);

        $article = new Article();
        $article->setTitle('Mon nouvel article');
        $article->setContent('<p>Le contenue de mon article est vraiment super</p>');
        $article->setFeaturedimage('3.jpg');
        $article->setSpecial(0);
        $article->setSpotlight(1);

        //Associer une catégorie et un auteur à notre article

        $article->setCategory($category);
        $article->setUser($user);

        //On sauvegarde en BDD avec doctrine
        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->persist($user);
        $em->persist($article);
        $em->flush();

        return new Response('Nouvel Article ajouté avec pour id :'.$article->getId()
            .'et la nouvelle catégorie : '.$category->getName()
            .'Et notre auteur : '.$user->getFirstname());
    }

    /**
     * Formulaire d'ajout d'un article.
     *
     * @Route("/creer-un-article", name="article_add")
     * @Security("has_role('ROLE_AUTHOR')")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Article\ArticleRequestHandler        $articleRequestHandler
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addArticle(Request $request, ArticleRequestHandler $articleRequestHandler)
    {
        //Récupération de l'auteur
//        $auteur = $this->getDoctrine()->getRepository(User::class)->find(3); remplacé par $this->getUser

        //Création d'un nouvel article
//        $article = new Article(); version de base
//        $article->setUser($auteur);

        $article = new ArticleRequest($this->getUser()); //Pour laisser notre service faire le boulot.

        //Créer un Formulaire permettant l'ajout d'un Article
        $form = $this->createForm(ArticleType::class, $article)->handleRequest($request);
        //Traitement des données POST
//        $form->handleRequest($request);

        //Verification des données du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            //Persister les données
            /**
             * pour avoir l'auto complétion.
             *
             * @var Article
             */
//            $article = $form->getData();

            /**
             * Une fois le formulaire soumis et valide on passe nos données directement au service
             * qui se chargera du trainement.
             */
            $article = $articleRequestHandler->handle($article);

            //Message flash
            $this->addFlash('notice', 'Article bien crée !');

            //Redirection sur l'article qui vient d'être crée.
            return $this->redirectToRoute('index_article', [
                'category' => $article->getCategory()->getSlug(),
                'slug' => $article->getSlug(),
                'id' => $article->getId(),
            ]);
        }

        //Affichage du formulaire dans la vue
        return $this->render('article/addarticle.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Editer un article.
     *
     * @Route("/editer-un-article/{id}", name="article_edit", requirements={"id":"\d+"})
     * @Security("has_role('ROLE_AUTHOR')")
     *
     * @param \App\Entity\Article                       $article
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Asset\Packages         $packages
     * @param \App\Article\ArticleRequestUpdateHandler  $updateHandler
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editArticle(
        Article $article,
        Request $request,
        Packages $packages,
        ArticleRequestUpdateHandler $updateHandler
    ): Response {
        //Recuperation de notre articleresquest depuis Article
//        $ar = $articleRequestHandler->prepareArticleFromRequest($article);
        $ar = ArticleRequest::createFromArticle($article, $packages, $this->getParameter('featuredimage_directory'));

        //Passer des options
        $options = [
            'image_url' => $ar->getImageURL(),
            'slug' => $ar->getSlug(),
        ];

        //Creation du formulaire
        $form = $this->createForm(ArticleType::class, $ar, $options)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $updateHandler->handle($ar, $article);

            $this->addFlash('notice', 'Modification éffectuée !');

            return $this->redirectToRoute('article_edit', [
                'id' => $article->getId(),
            ]);
        }

        return $this->render('article/addarticle.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route({
    "fr": "/mes-articles",
    "en": "/my-articles"
    }, name="article_published")
     * @Security("has_role('ROLE_AUTHOR')")
     */
    public function publishedArtcles()
    {
        //recupération de l'auteur
        $author = $this->getUser();

        //Récupération d'articles
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAuthorArticlesByStatus(
            $author->getId(),
            'published'
        );

        return $this->render('article/article.html.twig', [
            'articles' => $articles,
            'title' => 'Mes articles publiées',
        ]);
    }

    /**
     * @Route({
    "fr": "/mes-articles/en-attente",
    "en": "/my-articles/pending"
    }, name="article_pending"
     * )
     * @Security("has_role('ROLE_AUTHOR')")
     */
    public function pendingArticles(): Response
    {
        //recupération de l'auteur
        $author = $this->getUser();

        //Récupération d'articles
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAuthorArticlesByStatus(
            $author->getId(),
            'review'
        );

        return $this->render('article/article.html.twig', [
            'articles' => $articles,
            'title' => 'Mes articles en attente',
        ]);
    }

    public function test()
    {
        if (true) {
            // test
        }
    }

    /**
     * Permet de changer le statut d'un article.
     *
     * @Route("workflow/{status}/{id}", name="article_workflow")
     * @Security("has_role('ROLE_AUTHOR')")
     *
     * @param                                           $status
     * @param \App\Entity\Article                       $article
     * @param \App\Article\ArticleWorkflowHandler       $awh
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function workflow($status, Article $article, ArticleWorkflowHandler $awh, Request $request)
    {
        try {
            $awh->handle($article, $status);
            //Notification
            $this->addFlash('notice', 'Votre article à bien été transmis. Merci');
        } catch (LogicException $exception) {
            $this->addFlash('error', 'Changement de statut impossible');
        }

        //Récupération du Redirect
        $redirect = $request->get('redirect') ?? 'index';

        //on redirige
        return $this->redirectToRoute($redirect);
    }

    /**
     * @Route({
    "fr": "/les-articles/en-attente-de-validation",
    "en": "/articles/pending-approval"
    }, name="article_approval"
     * )
     * @Security("has_role('ROLE_EDITOR')")
     */
    public function approvalArticles()
    {
        //Récupération d'articles
        $articles = $this->getDoctrine()->getRepository(Article::class)->findArticlesByStatus('editor');

        return $this->render('article/article.html.twig', [
            'articles' => $articles,
            'title' => 'Les articles en attente de validation',
        ]);
    }

    /**
     * @Route({
    "fr": "/les-articles/en-attente-de-correction",
    "en": "/articles/pending-correction"
    }, name="article_corrector"
     * )
     * @Security("has_role('ROLE_CORRECTOR')")
     */
    public function correctorArticles()
    {
        //Récupération d'articles
        $articles = $this->getDoctrine()->getRepository(Article::class)->findArticlesByStatus('corrector');

        return $this->render('article/article.html.twig', [
            'articles' => $articles,
            'title' => 'Les articles en attente de correction',
        ]);
    }

    /**
     * @Route({
    "fr": "/les-articles/en-attente-de-soumission",
    "en": "/articles/pending-publisher"
    }, name="article_publisher"
     * )
     * @Security("has_role('ROLE_PUBLISHER')")
     */
    public function publisherArtcles()
    {
        //recupération de l'auteur
        $author = $this->getUser();

        //Récupération d'articles
        $articles = $this->getDoctrine()->getRepository(Article::class)->findArticlesByStatus('publisher');

        return $this->render('article/article.html.twig', [
            'articles' => $articles,
            'title' => 'Les articles en attente de soumission',
        ]);
    }
}
