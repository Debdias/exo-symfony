<?php
namespace OC\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
      return $this->render('OCCoreBundle:Home:index.html.twig');
    }

    public function contactAction(Request $request){
      $session = $request->getSession();

      $session->getFlashBag()->add('info', 'La page de contact n’est pas encore disponible');

      return $this->redirectToRoute('oc_core_homepage');
    }
    public function editImageAction($advertId){
      $em = $this->getDoctrine()->getManager();
      $advert = $em->getRepository('OCPlatformBundle:Advert')->find($advertId);

      $advert->getImage()->setUrl('test.png');

      $em->flush();

      return new Response('OK');
    }
}
