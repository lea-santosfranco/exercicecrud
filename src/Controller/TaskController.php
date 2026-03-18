<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function create(): Response
    {

    }

    #[Route('/tasks/{id}/edit', name: 'app_task_edit', methods: ['GET', 'POST'])]
    public function edit(int $id): Response
    {
}
}