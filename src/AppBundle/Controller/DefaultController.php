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
        $charactersManager = new Characters();
        $allCharacters = $charactersManager->getAll();

        if (!empty($_POST['species'])) {
            $characters = $charactersManager->getExtractByParameterAndInverseOfValue($allCharacters,'species',$_POST['species']);
            if (!empty($_POST['gender'])) {
                $characters = $charactersManager->getExtractByParameterAndExactValue($characters,'gender',$_POST['gender']);
            }
            if (!empty($_POST['lastHomeworld'])) {
                $characters = $charactersManager->getExtractByParameterAndValue($characters,'lastHomeworld',$_POST['lastHomeworld']);
            }
            return $this->render('default/choices.html.twig', [
                'characters' => $characters,
            ]);
        }

        $species = $charactersManager->getValuesByParameter($allCharacters,'species');
        $homeworlds = $charactersManager->getValuesByParameter($allCharacters,'lastHomeworld');

        return $this->render('default/xenophiliac.html.twig', [
            'species' => $species,
            'homeworlds' => $homeworlds,
        ]);
    }
    /**
     * @Route("/objectum_sexual ", name="objectum_sexual")
     * @Method({"GET","POST"})
     */
    public function objectumSexualAction(Request $request)
    {
        $charactersManager = new Characters();
        $allCharacters = $charactersManager->getAll();

        if (!empty($_POST['affiliations'])) {
            $characters = $charactersManager->getExtractByAffiliation($allCharacters,$_POST['affiliations']);
            if (!empty($_POST['height'])) {
                $characters = $charactersManager->getAllDifferentByHeight($characters,$_POST['height']);
            }
            if (!empty($_POST['mass'])) {
                $characters = $charactersManager->getAllDifferentByMass($characters, $_POST['mass']);
            }
            return $this->render('default/choices.html.twig', [
                'characters' => $characters,
            ]);
        }

        $species = $charactersManager->getValuesByParameter($allCharacters,'species');

        return $this->render('default/objectum_sexual.html.twig', [
            'species' => $species,
        ]);
    }
    /**
     * @Route("/forbidden", name="forbidden")
     * @Method({"GET","POST"})
     */
    public function forbiddenAction(Request $request)
    {
        $charactersManager = new Characters();
        $allCharacters = $charactersManager->getAll();

        if (!empty($_POST['affiliations'])) {
            $characters = $charactersManager->getExtractByAffiliation($allCharacters,$_POST['affiliations']);
            if (!empty($_POST['height'])) {
                $characters = $charactersManager->getAllDifferentByHeight($characters,$_POST['height']);
            }
            if (!empty($_POST['mass'])) {
                $characters = $charactersManager->getAllDifferentByMass($characters, $_POST['mass']);
            }
            return $this->render('default/choices.html.twig', [
                'characters' => $characters,
            ]);
        }

        $species = $charactersManager->getValuesByParameter($allCharacters,'species');

        return $this->render('default/forbidden.html.twig', [
            'species' => $species,
        ]);
    }
    /**
     * @Route("/soulmate", name="soulmate")
     */
    public function soulmateAction(Request $request)
    {

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
        $homeworld ='';

        if (!empty($character['homeworld'])) {
            if (is_array($character['homeworld'])) {
                $homeworld = end($character['homeworld']);//$character['homeworld'] = strtolower($character['homeworld'][count($character['homeworld'])-1]);
            } else {
                $homeworld = $character['homeworld'] = strtolower($character['homeworld']);
            }
        }

        $q1 = ['https://www.google.fr/imgres?imgurl=https%3A%2F%2Fupload.wikimedia.org%2Fwikipedia%2Fcommons%2Fthumb%2Fc%2Fc2%2FCoeur.svg%2F1276px-Coeur.svg.png&imgrefurl=https%3A%2F%2Ffr.wikipedia.org%2Fwiki%2FFichier%3ACoeur.svg&docid=EqwMN3kwUBM2uM&tbnid=gLPN1BjAecUtYM%3A&vet=10ahUKEwj-jdr38Y7bAhVDcRQKHVUUDAgQMwg9KAAwAA..i&w=1276&h=1024&bih=785&biw=1535&q=coeur%20svg&ved=0ahUKEwj-jdr38Y7bAhVDcRQKHVUUDAgQMwg9KAAwAA&iact=mrc&uact=8',
            'https://www.google.fr/imgres?imgurl=http%3A%2F%2Fdata.over-blog-kiwi.com%2F0%2F74%2F83%2F45%2F20160613%2Fob_8e6ce9_coeur-fleuri.svg&imgrefurl=http%3A%2F%2Flegeckobleu.over-blog.com%2Ftag%2Fdecoupes%2F&docid=OjVCPsQkSLL1nM&tbnid=7MYgqgYFu01wJM%3A&vet=10ahUKEwj-jdr38Y7bAhVDcRQKHVUUDAgQMwhBKAQwBA..i&w=457&h=408&bih=785&biw=1535&q=coeur%20svg&ved=0ahUKEwj-jdr38Y7bAhVDcRQKHVUUDAgQMwhBKAQwBA&iact=mrc&uact=8'
        ];
        shuffle($q1);
        $like = ['https://i2.wp.com/biofuelsinternationalexpo.com/wp-content/uploads/2018/02/star-wars-gifts-for-him-starwars-love-gift.jpg?w=720&ssl=1',
            'http://www.sparklyprettybriiiight.com/wp-content/uploads/2013/08/Galactic-Love-true-love-HERO.jpg',
            'https://i.pinimg.com/originals/04/8d/58/048d58b83fc711111f347a69fd9f7a4c.jpg',
            'https://www.amscan-europe.com/graphics_cache/7/9/16241-3430401-3-600.jpg',
            'https://www.tuxboard.com/photos/2015/12/livre-star-wars-kama-sutra-non-officiel-erotique-positions-sexuelles.jpg',
            'https://technabob.com/blog/wp-content/uploads/2015/12/star_wars_kama_sutra_3.jpg'
        ];
        shuffle($like);
        $dislike = ['https://conceitedcrusade.files.wordpress.com/2015/09/star-wars.jpg?w=940',
            'https://media1.tenor.com/images/3fb8c3f4b562f15cc8462f4cf11f84e0/tenor.gif?itemid=8367915',
            'http://farm6.static.flickr.com/5475/10726150653_dc2d09dcc2.jpg',
            'https://i.ytimg.com/vi/FL7V8gnvKE0/maxresdefault.jpg',
            ];
        shuffle($dislike);
        $random = [$like[0],$dislike[0]];
        shuffle($random);
        $affichageResult = $random[0];

        $afficheQ1='';
        // replace this example code with whatever you need
        return $this->render('default/matches.html.twig',[
            'character' => $character,
            'affiliations' => $affiliations,
            'homeworld' => $homeworld,
            'afficheQ1' =>$afficheQ1,
            'result' => $affichageResult,
        ]);
    }

    /**
     * @Route("/test", name="test")
     */
    public function testAction(Request $request)
    {
        $charactersManager = new Characters();
        $allCharacters = $charactersManager->getAll();
        $characters = $charactersManager->getAllDifferentByMass($allCharacters,'2');

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
