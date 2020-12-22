<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

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
            'film/liste_film_et_form.html.twig',
            [
                'films' => $films,
                'formulaire' => $form->createView(),
                'titre_liste' => "Liste des films",
                'titre_form' => "Ajouter un film !"
            ]
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
                'dateSortie' => $film->getDateSortie()->format('d-m-Y'),
                'note' => $film->getNote(),
                'ageMinimal' => $film->getAgeMinimal(),
                'acteurs' => $film->getActeurs(),
                'film' => $film
            ]
        );
    }
    public function modifierFilm($id)
    {
        $film = $this->getDoctrine()->getRepository(film::class)->find($id);
        if (!$film) {
            throw $this->createNotFoundException('L\'film[id=' . $id . '] n\'existe pas');
        }
        $form = $this->createForm(
            FilmType::class,
            $film,
            ['action' => $this->generateUrl(
                'modifier2_film',
                ['id' => $film->getId()]
            )]
        );
        $form->add('submit', SubmitType::class, array('label' => 'Modifier'));
        return $this->render(
            'film/modifier_film.html.twig',
            ['formulaire' => $form->createView()]
        );
    }
    public function modifier2Film(Request $request, $id)
    {
        $film = $this->getDoctrine()->getRepository(film::class)->find($id);
        if (!$film)
            throw $this->createNotFoundException('film[id=' . $id . '] inexistante');
        $form = $this->createForm(
            FilmType::class,
            $film,
            ['action' => $this->generateUrl(
                'film/modifier2_film',
                ['id' => $film->getId()]
            )]
        );
        $form->add('submit', SubmitType::class, ['label' => 'Modifier']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $eM = $this->getDoctrine()->getManager();
            $eM->persist($film);
            $eM->flush();
            $url = $this->generateUrl(
                'detail_film',
                ['id' => $film->getId()]
            );
            return $this->redirect($url);
        }
        return $this->render(
            'modifier_film.html.twig',
            ['formulaire' => $form->createView()]
        );
    }
    public function supprimerFilm($id, Session $session)
    {
        $film = $this->getDoctrine()->getRepository(Film::class)->find($id);
        if (!$film) {
            throw $this->createNotFoundException('L\'film[id=' . $id . '] n\'existe pas');
        }
        $session->getFlashBag()
            ->add('filmSupprime', 'Le film :' . $film->getTitre() . ' a été supprimé avec succès.');
        $eM = $this->getDoctrine()->getManager();
        $eM->remove($film);
        $eM->flush();

        return $this->redirectToRoute('liste_film');
    }

    # * * * ACTION 13 * * * 
    public function entreDeuxAnnees(Request $request)
    {

        $films =  [];
        $defaultData = ['message' => 'Type your message here'];
        $form = $this->createFormBuilder($defaultData)
            ->add('annee_1', IntegerType::class, [
                'attr' => ['min' => 1900, 'max' => 2020]
            ])
            ->add('annee_2', IntegerType::class, [
                'attr' => ['min' => 1900, 'max' => 2020]
            ])
            ->add('Rechercher', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $year1 = $form['annee_1']->getData();
            $year2 = $form['annee_2']->getData();
            $films = $this->getDoctrine()
                ->getRepository(Film::class)
                ->findBetweenTwoYears($year1, $year2);
            return $this->render(
                'film/liste_film_et_form.html.twig',
                [
                    'formulaire' => $form->createView(),
                    'films' => $films,
                    'titre_liste' => "Liste des films",
                    'titre_form' => "Rechercher les films entre deux années :"
                ]
            );
        }
        return $this->render(
            'film/liste_film_et_form.html.twig',
            [
                'formulaire' => $form->createView(),
                'films' => $films,
                'titre_liste' => "",
                'titre_form' => "Rechercher les films entre deux années :"
            ]
        );
    }

    # * * * ACTION 14 * * * 
    public function avantUneAnnee(Request $request)
    {
        $films =  [];
        $defaultData = ['message' => 'Type your message here'];
        $form = $this->createFormBuilder($defaultData)
            ->add('annee_1', IntegerType::class, [
                'attr' => ['min' => 1900, 'max' => 2020]
            ])
            ->add('Rechercher', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $year = $form['annee_1']->getData();
            $films = $this->getDoctrine()
                ->getRepository(Film::class)
                ->findBeforeOneYear($year);
            return $this->render(
                'film/liste_film_et_form.html.twig',
                [
                    'formulaire' => $form->createView(),
                    'films' => $films,
                    'titre_liste' => "Liste des films",
                    'titre_form' => "Rechercher les films avant une année :"
                ]
            );
        }
        return $this->render(
            'film/liste_film_et_form.html.twig',
            [
                'formulaire' => $form->createView(),
                'films' => $films,
                'titre_liste' => "",
                'titre_form' => "Rechercher les films avant une année :"
            ]
        );
    }
}
