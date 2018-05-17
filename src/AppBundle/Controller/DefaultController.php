<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\Characters;
use GuzzleHttp\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function profileAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/profile.html.twig');
    }

    /**
     * @Route("/xenophiliac", name="xenophiliac")
     */
    public function xenophiliacAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/xenophiliac.html.twig');
    }
    /**
     * @Route("/objectum_sexual ", name="objectum_sexual")
     */
    public function objectumSexualAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/objectum_sexual.html.twig');
    }
    /**
     * @Route("/forbidden", name="forbidden")
     * @Method({"GET","POST"})
     */
    public function forbiddenAction(Request $request)
    {
        if (!empty($_POST['affiliations'])) {
            $charactersManager = new Characters();
            $allCharacters = $charactersManager->getAll();
            $characters = $charactersManager->getExtractByAffiliation($allCharacters,$_POST['affiliations']);

            return $this->render('default/choices.html.twig', [
                'characters' => $characters,
            ]);
        }

        return $this->render('default/forbidden.html.twig');
    }
    /**
     * @Route("/soulmate", name="soulmate")
     */
    public function soulmateAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/soulmate.html.twig');
    }

    /**
     * @Route("/choices", name="choices")
     */
    public function choicesAction(Request $request)
    {
        $allCharacters = new Characters();
        $characters = $allCharacters->getAll();
    
        return $this->render('default/choices.html.twig', [
            'characters' => $characters,
        ]);
    }


    /**
     * @Route("/matches/{id}", name="matches", requirements={"\d+"})
     */
    public function matchesAction($id, Request $request)
    {
        $charactersManager = new Characters();
        $character = $charactersManager->getOneByID($id);
        $affiliations = end($character['affiliations']);


        // replace this example code with whatever you need
        return $this->render('default/matches.html.twig',[
            'character' => $character,
            'affiliations' => $affiliations
        ]);
    }

    /**
     * @Route("/test", name="test")
     */
    public function testAction(Request $request)
    {
        $charactersManager = new Characters();
        $allCharacters = $charactersManager->getAll();
        $characters = $charactersManager->getExtractByParametersAndValues($allCharacters, 'species',['human','droid']);

        return $this->render('default/test.html.twig', [
            'characters' => $characters,
        ]);
    }

    /**
     * @Route("/credit", name="credit")
     */
    public function creditAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/credit.html.twig');
    }



}
