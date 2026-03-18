<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TaskController extends AbstractController
{
    #[Route('/task', name: 'app_task', methods: ['GET'])]
    public function index(TaskRepository $taskRepository): Response
    {
            // 1. Récupère toutes les tâches via le TaskRepository
            // La méthode findAll() du TaskRepository est utilisée pour récupérer toutes les tâches de la base de données. Le résultat est stocké dans la variable $tasks.
            $tasks = $taskRepository->findAll();

            // 2. Passe les tâches à la vue Twig pour les afficher
            // La méthode render() est utilisée pour rendre la vue Twig située à 'task/index.html.twig'. Les données à afficher sont passées à la vue sous forme de tableau associatif, où 'tasks' est la clé et $tasks est la valeur contenant les tâches récupérées.
        return $this->render('task/index.html.twig', [
            'controller_name' => 'TaskController',
            'tasks' => $tasks,
        ]);
    }

    #[Route('/tasks/create', name: 'app_task_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        // On peut créer une nouvelle tâche en instanciant la classe Task. Cela crée un nouvel objet Task qui peut être utilisé pour définir les propriétés de la tâche avant de l'enregistrer dans la base de données.
        $task = new Task();
        // On crée le formulaire (lié à l'objet vide)
        $form = $this->createForm(TaskType::class, $task);
        // On demande au formulaire de lire la requête
        $form->handleRequest($request);
        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // On demande à l'EntityManager de préparer l'objet
            $entityManager->persist($task);
            // On exécute réellement la reqûete
            $entityManager->flush();
            // On redirige vers la liste des tâches
            return $this->redirectToRoute('app_task');
    }

        // On affiche le formulaire
        return $this->render('task/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/tasks/{id}/edit', name: 'app_task_edit', methods: ['GET', 'POST'])]
    public function edit(Task $task, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_task');
        }
        return $this->render('task/edit.html.twig',[
            'form' => $form->createView(),
            'task' => $task,
        ]);
}

    #[Route('/tasks/todo', name: 'app_task_todo', methods : ['GET'])]
    public function todo(TaskRepository $taskRepository): Response
    {
        return $this->render('task/index.html.twig', [
            'tasks' => $taskRepository->findBy(['isDone' => false]),
        ]);
    }

    #[Route('/tasks/done', name: 'app_task_done', methods : ['GET'])]
    public function done(TaskRepository $taskRepository): Response
    {
        return $this->render('task/index.html.twig', [
            'tasks' => $taskRepository->findBy(['isDone' => true]),
        ]);
    }

}