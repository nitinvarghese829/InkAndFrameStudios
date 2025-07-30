<?php

namespace App\Controller\Admin;

use App\Entity\Admin;
use App\Entity\Blog;
use App\Entity\ContactUs;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;

#[AdminDashboard(routePath: '/admin/dashboard', routeName: 'admin_dashboard')]
class DashboardController extends AbstractDashboardController
{
    private $connection;
    private $em;
    public function __construct(Connection $connection, EntityManagerInterface $em)
    {
        $this->connection = $connection;
        $this->em = $em;
    }
    public function index(): Response
    {
        $sql = "SELECT count(*) FROM contact_us";
        $count['enquiries'] = $this->connection->fetchOne($sql);

        return $this->render('admin/dashboard.html.twig', ['count' => $count]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('InkAndFrameStudios');
    }

    public function configureMenuItems(): iterable
    {
        $menuItems = [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),
            MenuItem::linkToCrud('Blogs', 'fa fa-blog', \App\Entity\Blog::class),
            MenuItem::linkToLogout('Logout', 'fa fa-sign-out'),
            MenuItem::linkToCrud('Services', 'fa fa-cogs', \App\Entity\Services::class),
            MenuItem::linkToCrud('Enquiries', 'fa-regular fa-address-book', ContactUs::class)
        ];

        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_SUPER_ADMIN')) {
            $menuItems[] = MenuItem::subMenu('Users', 'fa fa-tags')->setSubItems([
                MenuItem::linkToCrud('Admin', 'fa fa-user-tie', \App\Entity\Admin::class),
            ]);
        }


        return $menuItems;
    }

    public function configureAssets(): Assets
    {
        return Assets::new()
            ->addWebpackEncoreEntry('admin'); // This will include assets/js/admin.js
    }
}
