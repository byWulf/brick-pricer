<?php

namespace App\Controller\Admin;

use App\Entity\Piece;
use App\Entity\PieceList;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{

    #[Route('/', name: 'admin')]
    public function index(): Response
    {
        return $this->render('easy_admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()->setTitle('<i class="fa fa-search-dollar"></i> LEGO pricer');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Piece lists', 'fa fa-cubes', PieceList::class);
        yield MenuItem::linkToCrud('Pieces', 'fa fa-cube', Piece::class);
    }

    public function configureAssets(): Assets
    {
        return Assets::new()
            ->addWebpackEncoreEntry('app');
    }

    public function configureCrud(): Crud
    {
        return Crud::new()
            ->overrideTemplate('layout', 'layout.html.twig')
        ;
    }
}
