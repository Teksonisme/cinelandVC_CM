<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\Acteur;
use App\Entity\Genre;

use App\Form\Type\ActeurFormType;
use App\Form\Type\GenreFormType;

class GenreController extends AbstractController
{
    # * * * ACTION 1 * * * 
    #       *   *   *
    public function listeGenre(Request $request)
    {
        $genre = new Genre;
        $genres = $this->getDoctrine()
            ->getRepository(Genre::class)->findAll();
        $form = $this->createFormBuilder($genre)
            ->add('nom', TextType::class)
            ->add('Ajouter', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $eM = $this->getDoctrine()->getManager();
            $eM->persist($genre);
            $eM->flush();
            return $this->redirectToRoute('liste_genre');
        }
        return $this->render(
            'genre/liste_genre_et_form.html.twig',
            array(
                'genres' => $genres,
                'formulaire' => $form->createView(),
                'titre_liste' => "Liste des genres",
                'titre_form' => "Ajouter votre genre"
            )
        );
    }
    # * * * ACTION 18 * * * 
    #       *   *   *
    public function listeGenreActeurDeuxFilms(Request $request)
    {
        $acteur = new Acteur;
        $genres = [];
        $form = $this->createForm(
            ActeurFormType::class,
            $acteur
        )
            ->add('submit', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $acteur = $this->getDoctrine()->getRepository(Acteur::class)
                ->findbyName($acteur->getNomPrenom());
            $films = $acteur->getFilms();
            $tab = [];
            foreach ($films as $film) { // Vérifie si l'acteur a plus de 2 films avec le même genre
                $genre = $film->getgenre();
                if (in_array($genre, $tab)) {
                    array_push($genres, $genre);
                }
                array_push($tab, $genre);
            }
            if (sizeof($genres) == 0) {
                $temp = new Genre;
                $temp->setNom("Aucun");
                $genres[] = $temp;
            }

            return $this->render(
                'genre/liste_genre_et_form.html.twig',
                [
                    'titre_form' => "Donner votre acteur",
                    'formulaire' => $form->createView(),
                    'titre_liste' => "Liste des genre(s :",
                    'genres' => $genres
                ]
            );
        }
        return $this->render(
            'genre/liste_genre_et_form.html.twig',
            [
                'titre_form' => "Donner votre acteur",
                'formulaire' => $form->createView(),
                'titre_liste' => "",
                'genres' => $genres
            ]
        );
    }
    # * * * ACTION 22 * * * 
    #       *   *   *
    public function dureeFilmsPourUnGenre(Request $request)
    {
        $genre = new Genre;
        $duree = 0;
        $form = $this->createForm(
            GenreFormType::class,
            $genre
        )
            ->add('submit', SubmitType::class, ['label' => "Afficher durée"]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $genre = $this->getDoctrine()->getRepository(Genre::class)
                ->findbyName($genre->getNom());
            $films = $genre->getFilms();
            foreach ($films as $film) {
                $duree += $film->getDuree();
            }
            return $this->render(
                'genre/duree_films.html.twig',
                [
                    'titre_form' => "Donner votre genre : ",
                    'formulaire' => $form->createView(),
                    'duree' => $duree.' minutes'
                ]
            );
        }
        return $this->render(
            'genre/duree_films.html.twig',
            [
                'titre_form' => "Donner votre genre : ",
                'formulaire' => $form->createView(),
                'duree' => ''
            ]
        );
    }
}
