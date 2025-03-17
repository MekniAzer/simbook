<?php

namespace App\Controller;


use App\Entity\Livres;    // 🔹 Ajout de l'importation de l'entité

use App\Repository\LivresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class LivresController extends AbstractController
{
    #[Route('/admin/livres/create', name: 'app_livres_create')]
    public function create(EntityManagerInterface $em): Response // 🔹 Ajout du nom du paramètre
    {
        $livre = new Livres();
        $date = new \DateTime("1954-07-29");

        $livre->setTitre('titre1')
            ->setSlug(1) // 🔹 Suppression des guillemets si slug est un INT
            ->setImage('https://picsum.photos/seed/picsum/200/300')
            ->setResume(1) // 🔹 Même chose pour resume
            ->setDateedition($date)
            ->setIsbn("111-1111-1111-1111")
            ->setPrix(20)
            ->setEditeur('Max');



        // 🔹 On persiste et on enregistre en base
        $em->persist($livre);
        $em->flush(); #insertion dans la base par doctrine les objets persistés

        return new Response("Livre ajouté avec succès avec id {$livre->getId()}");
    }


    #[Route('/admin/livres/show/{id}', name: 'app_livres_show')]
    public function show(LivresRepository $rep,$id): Response // 🔹 Ajout du nom du paramètre
    {
        $livre = $rep->find($id);
        if(!$livre){
            throw $this->createNotFoundException("No book found for id {$id}");
        }
        return $this->render('livres/show.html.twig', ['livre' => $livre ]);

    }
    #[Route('/admin/livres/param/{id}', name: 'app_livres_param')]
    public function paramConverter(Livres $livre,$id): Response // 🔹 Ajout du nom du paramètre
    {

        if(!$livre){
            throw $this->createNotFoundException("No book found for id {$id}");
        }
        dd($livre);

    }


    #[Route('/admin/livres/show2', name: 'app_livres_show2')]
    public function show2(LivresRepository $rep): Response // 🔹 Ajout du nom du paramètre
    {
        $livre = $rep->findOneBy(['titre'=>'titre1','editeur'=>'Max']);

        dd($livre);

    }

    #[Route('/admin/livres/show3', name: 'app_livres_show3')]
    public function show3(LivresRepository $rep): Response // 🔹 Ajout du nom du paramètre
    {
        $livres = $rep->findBy(['titre'=>'titre1','editeur'=>'Max'],['prix'=>'ASC']);

        dd($livres);

    }

    #[Route('/admin/livres/all', name: 'app_livres_all')]
    public function all(LivresRepository $rep, PaginatorInterface $paginator, Request $request): Response
    {
        // You should use a QueryBuilder or a custom query method here.
        $query = $rep->createQueryBuilder('l')->getQuery(); // Example query builder to fetch data

        // Paginate results
        $pagination = $paginator->paginate(
            $query, // Query object, not the result of findAll()
            $request->query->getInt('page', 1), // Current page
            10 // Items per page (this is just an example, you can use your default limit here)
        );

        // Render the template with the pagination data
        return $this->render('livres/all.html.twig', ['pagination' => $pagination]);
    }



    #[Route('/admin/livres/delete/{id}', name: 'app_livres_delete')]
    public function delete(LivresRepository $rep,EntityManagerInterface $em,$id): Response // 🔹 Ajout du nom du paramètre
    {
        $livre = $rep->find($id);
        $em->remove($livre);
        $em->flush();

        dd($livre);

    }

    #[Route('/admin/livres/delete2/{id}', name: 'app_livres_delete2')]
    public function delete2(Livres $livre,EntityManagerInterface $em,$id): Response // 🔹 Ajout du nom du paramètre
    {

        $em->remove($livre);
        $em->flush();

        dd($livre);

    }

    #[Route('/admin/livres/update/{id}', name: 'app_livres_update')]
    public function update(LivresRepository $rep,EntityManagerInterface $em,$id): Response // 🔹 Ajout du nom du paramètre
    {
        $livre = $rep->find($id);
        $nouveauPrix=$livre->getPrix()*1.1;
        $livre->setPrix($nouveauPrix);
        $em->persist($livre);
        $em->flush();

        dd($livre);

    }

    #[Route('/admin/livres/update2/{id}', name: 'app_livres_update2')]
    public function update2(Livres $livre ,EntityManagerInterface $em,$id): Response // 🔹 Ajout du nom du paramètre
    {

        $nouveauPrix=$livre->getPrix()*1.1;
        $livre->setPrix($nouveauPrix);
        $em->persist($livre);
        $em->flush();

        dd($livre);

    }



}
