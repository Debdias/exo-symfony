<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
// use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AdvertController extends Controller
{
    public function indexAction()
    {
        // $content = $this->get('templating')->render('OCPlatformBundle:Advert:index.html.twig');

        // $content = $this
        // ->get('templating')
        // ->render('OCPlatformBundle:Advert:index.html.twig', array('nom' => 'winzou', 'advert_id' => 5));
        // return new Response($content);

        //         // On veut avoir l'URL de l'annonce d'id 5.
        // $url = $this->get('router')->generate(
        //     'oc_platform_view', // 1er argument : le nom de la route
        //     array('id' => 5)    // 2e argument : les valeurs des paramètres
        // );
        // // $url vaut « /platform/advert/5 »
        //
        // return new Response("L'URL de l'annonce d'id 5 est : ".$url);

        // $url = $this->get('router')->generate('oc_platform_home', array(),
        // UrlGeneratorInterface::ABSOLUTE_URL);

        // $url = $this->get('router')->generate('oc_platform_home');

        // $url = $this->generateUrl('oc_platform_home');
        //
        //  return new Response("L'URL home est : ".$url);


        $content = $this
        ->get('templating')
        ->render('OCPlatformBundle:Advert:index.html.twig', array('nom' => 'winzou', 'advert_id' => 5));
        return new Response($content);
    }

    public function viewAction($id)
    {
      // $id vaut 5 si l'on a appelé l'URL /platform/advert/5

      // Ici, on récupèrera depuis la base de données
      // l'annonce correspondant à l'id $id.
      // Puis on passera l'annonce à la vue pour
      // qu'elle puisse l'afficher

      // $tag = $request->query->get('tag');
      //
      // return new Response(
      //   "Affichage de l'annonce d'id : ".$id. ", avec le tag : " .$tag
      // );

      // $tag = $request->query->get('tag');
      // return $this->get('templating')
      // ->renderResponse(
      //   'OCPlatformBundle:Advert:view.html.twig',
      //   array(
      //     'id' => $id,
      //     'tag' => $tag
      //   )
      // );


      // $url = $this->get('router')->generate('oc_platform_home');
      // return $this->redirect($url);

      //return $this->redirectToRoute('oc_platform_home');


      // $response = new Response (json_encode(array('id' => $id)));
      // $response->headers->set('Content-Type', 'application/json');
      // return $response;

      // return new JsonResponse(array('id' => $id));

      // $session = $request->getSession();
      //
      // $userId = $session->get('user_id');
      //
      // $session->set('user_id', 91);
      //
      // return new Response(
      //   "<body>Je suis une page test, je n'ai rien à  dire</body>"
      // );
      return $this->render(
        'OCPlatformBundle:Advert:view.html.twig',
        array('id' => $id
      ));
    }

    public function addAction(Request $request){
        $session = $request->getSession();

        $session->getFlashBag()->add('info', 'Annonce bien enregistrée');

        $session->getFlashBag()->add('info', 'Oui oui, elle est bien enregistrée !');

        return $this->redirectToRoute('oc_platform_view', array('id' => 5));
    }

    // On récupère tous les paramètres en arguments de la méthode
    public function viewSlugAction($slug, $year, $_format)
    {
        return new Response(
            "On pourrait afficher l'annonce correspondant au
            slug '".$slug."', créée en ".$year." et au format ".$_format."."
        );
    }
}
