<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\Film;
use App\Form\Type\FilmType;

class FilmController extends AbstractController
{
    public function listeFilm(Request $request)
    {
        $film = new Film;
        $films = $this->getDoctrine()
            ->getRepository(Film::class)->findAll();
        $form = $this->createForm(FilmType::class, $film);
        $form->add('Apply', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $eM = $this->getDoctrine()->getManager();
            $eM->persist($film);
            $eM->flush();
            return $this->redirectToRoute('liste_film');
        }
        return $this->render(
            'film/liste_film.html.twig',
            array('films' => $films, 'formulaire' => $form->createView())
        );
    }
    public function detailFilm($id)
    {
        $film = $this->getDoctrine()
            ->getRepository(Film::class)
            ->find($id);
        if (!$film) {
            throw $this->createNotFoundException(
                'Film[id=' . $id . '] doesn\'t exist'
            );
        }
        return $this->render(
            'film/detail_film.html.twig',
            [
                'titre' => $film->getTitre(),
                'duree' => $film->getDuree(),
                'dateSortie' => $film->getDateSortie(),
                'note' => $film->getNote(),
                'ageMinimal' => $film->getAgeMinimal(),
                'acteurs' => $film->getActeurs(),
                'film' => $film
            ]
        );
    }
}