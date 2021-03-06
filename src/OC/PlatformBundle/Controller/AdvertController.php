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
          throw new NotFoundHttpException('Page '.$page.' inexistante.');
        }

        $listAdverts = $this->getDoctrine()
        ->getRepository('OCPlatformBundle:Advert')
        ->findAll();

        if(null == $listAdverts){
          throw new NotFoundHttpException('Aucune annonce disponible');
        }
          $mailer = $this->container->get('mailer');
           return $this->render('OCPlatformBundle:Advert:index.html.twig', array(
             'listAdverts' => $listAdverts
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
        array('advert' => $advert,
        'listApplications' => $listApplications,
        'listAdvertSkills' => $listAdvertSkills
      ));
    }
    public function addAction(Request $request){
    //Création de l'entité Advert
       $advert = new Advert();
       $advert->setTitle('Recherche pro designer.');
       $advert->setAuthor('Cyrielle');
       $advert->setContent("Nous recherchons un pro du design en freelance. Blabla…");

       // Création d'une première candidature

       $image = new Image();
       $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
       $image->setAlt('Job de rêve');

       $advert->setImage($image);

       $application1 = new Application();
       $application1->setAuthor('Marine');
       $application1->setContent("J'ai toutes les qualités requises.");

       // Création d'une deuxième candidature par exemple
       $application2 = new Application();
       $application2->setAuthor('Pierre');
       $application2->setContent("Je suis très motivé.");

       // On lie les candidatures à l'annonce
       $application1->setAdvert($advert);
       $application2->setAdvert($advert);

       // On récupère l'EntityManager
       $em = $this->getDoctrine()->getManager();

       // Étape 1 : On « persiste » l'entité
       $em->persist($advert);

       // Étape 1 ter : pour cette relation pas de cascade lorsqu'on persiste Advert, car la relation est
       // définie dans l'entité Application et non Advert. On doit donc tout persister à la main ici.
       $em->persist($application1);
       $em->persist($application2);

       // Étape 2 : On « flush » tout ce qui a été persisté avant
       $em->flush();

       // $em = $this->getDoctrine()->getManager();
       //
       // $advert = new Advert();
       // $advert->setTitle('Recherche développeur Symfony.');
       // $advert->setAuthor('Alexandre');
       // $advert->setContent('Nousrecherchons un développeur Symfony débutant sur Lyon. Blabla...');
       //
       // $image = new Image();
       // $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
       // $image->setAlt('Job de rêve');
       //
       // $advert->setImage($image);
       //
       // $listSkills = $em->getRepository('OCPlatformBundle:Skill')->findAll();
       //
       // foreach($listSkills as $skill)
       // {
       //    $advertSkill = new AdvertSkill();
       //
       //    $advertSkill->setAdvert($advert);
       //    $advertSkill->setSkill($skill);
       //
       //    $advertSkill->setLevel('Expert');
       //
       //    $em->persist($advertSkill);
       // }
       //
       // $em->persist($advert);
       //
       // $em->flush();
       //
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
      return $this->render('OCPlatformBundle:Advert:add.html.twig', array(
        'advert' => $advert
      ));

    }
    public function editAction($id, Request $request)
    {
      $em = $this->getDoctrine()->getManager();

      $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);
      $advert->updateDate();

      if(null === $advert){
        throw new NotFoundHttpException("L'annonce d'id ". $id ." n'existe pas.");
      }


      // $listCategories = $em->getRepository('OCPlatformBundle:Category')->findAll();
      //
      // foreach($listCategories as $category){
      //   $advert->addCategory($category);
      // }

      $em->flush();

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
