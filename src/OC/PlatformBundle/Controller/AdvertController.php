<?php
namespace OC\PlatformBundle\Controller;

use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\AdvertSkill;
use OC\PlatformBundle\Entity\Application;
use OC\PlatformBundle\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller
{
    public function indexAction($page)
    {
        if($page < 1)
        {
          throw $this>createNotFoundException(' La page '.$page.' est inexistante.');
        }

        // Ici je fixe le nombre d'annonces par page à 3
        // Mais bien sûr il faudrait utiliser un paramètre, et y accéder via $this->container->getParameter('nb_per_page')
        $nbPerPage = 3;

        $listAdverts = $this->getDoctrine()
        ->getRepository('OCPlatformBundle:Advert')
        ->getAdverts($page, $nbPerPage);

        $nbPages = ceil(count($listAdverts) / $nbPerPage);

        if($age > $nbPages){
          throw $this->createNotFoundException(' La page '.$page.' est inexistante.');
        }
          // $mailer = $this->container->get('mailer');
           return $this->render('OCPlatformBundle:Advert:index.html.twig', array(
             'listAdverts' => $listAdverts,
             'nbPages' => $nbPages,
             'page' => $page
           ));
    }
    public function viewAction($id)
    {
      $em = $this->getDoctrine()->getManager();
      $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);
      // $advert = $this->getDoctrine()
      // ->getManager()
      // ->find('OCPlatformBundle:Advert', $id);

      if(null == $advert){
        throw new NotFoundHttpException("L'annonce d'id ".$id. "n'existe pas.");
      }

      $listApplications = $em
        ->getRepository('OCPlatformBundle:Application')
        ->findBy(array('advert' => $advert));

      $listAdvertSkills = $em
        ->getRepository('OCPlatformBundle:AdvertSkill')
        ->findBy(array('advert' => $advert));

      return $this->render
      (
        'OCPlatformBundle:Advert:view.html.twig',
        array(
        'advert'            => $advert,
        'listApplications'  => $listApplications,
        'listAdvertSkills'  => $listAdvertSkills
      ));
    }
    public function addAction(Request $request){
      $em = $this->getDoctrine()->getManager();

      //Gérer le formulaire
      if($request->isMethod('POST'))
      {
        $request->getSession()
        ->getFlashBag()
        ->add('notice', 'Annonce bien enregistrée.');
        return $this->redirectToRoute
        (
          'oc_platform_view',
          array('id' => $advert->getId()
        ));
      }
      return $this->render('OCPlatformBundle:Advert:add.html.twig');

    }
    public function editAction($id, Request $request)
    {
      $em = $this->getDoctrine()->getManager();
      //Gérer le formulaire
      $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);
      $advert->updateDate();

      if(null === $advert){
        throw new NotFoundHttpException("L'annonce d'id ". $id ." n'existe pas.");
      }

      if($request->isMethod('POST'))
      {
        $request->getSession()
        ->getFlashBag()
        ->add('notice', 'Annonce bien modifiée');
                $id = $advert->getId();
        return $this->redirectToRoute
        (
          'oc_platform_view',
          array('id' => $id
        ));
      }

      return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
        'advert' => $advert
      ));
    }
    public function deleteAction($id)
    {
      $em = $this->getDoctrine()->getManager();

      $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

      if(null === $advert)
      {
        throw new NotFoundHttpException("L'annonce d'id ". $id. "n'existe pas.");
      }

      foreach($advert->getCategories() as $category)
      {
        $advert->removeCategory($category);
      }

      $em->flush();

      return $this->render('OCPlatformBundle:Advert:delete.html.twig');
    }

    public function menuAction($limit)
    {
      $em = $this->getDoctrine()->getManager();

      $listAdverts = $em->getRepository('OCPlatformBundle:Advert')->findBy(
        array(),                 // Pas de critère
        array('date' => 'desc'), // On trie par date décroissante
        $limit,                  // On sélectionne $limit annonces
        0                        // À partir du premier
      );

      return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(
        'listAdverts' => $listAdverts
      ));
    }

    public function testAction()
    {
      $advert = new Advert();
      $advert->setTitle("Recherche développeur !");

      $em = $this->getDoctrine()->getManager();
      $em->persist($advert);
      $em->flush();

      return new Response('Slug généré :' .$advert->getSlug());
      $repository = $this
      ->getDoctrine()
      ->getManager()
      ->getRepository('OCPlatformBundle:Advert');

      $listAdverts = $repository->myFindAll();
    }
    public function listAction()
    {
      $listAdverts = $this
      ->getDoctrine()
      ->getManager()
      ->getRepository('OCPlatformBundle:Advert')
      ->getAdvertWithApplications();

      foreach($listAdverts as $advert)
      {
        $advert->getApplications();
      }
    }
}
