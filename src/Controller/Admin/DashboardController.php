<?php

namespace App\Controller\Admin;

use App\Entity\Admin;
use App\Entity\Blog;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

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

        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('InkAndFrameStudios');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),
            MenuItem::subMenu('Users', 'fa fa-tags')->setSubItems([
                MenuItem::linkToCrud('Admin', 'fa fa-user-tie', Admin::class),
            ]),
            MenuItem::linkToCrud('Blogs', 'fa fa-blog', Blog::class),
            // MenuItem::subMenu('Product', 'fa fa-tags')->setSubItems([
            //     MenuItem::linkToCrud('Product', 'fa-cart-shopping', Product::class),
            //     MenuItem::linkToCrud('Category', 'fa-cart-shopping', ProductCategory::class),
            // ]),
            // MenuItem::subMenu('Service', 'fa fa-tags')->setSubItems([
            //     MenuItem::linkToCrud('Services', 'fa-cart-shopping', Services::class),
            // ]),
            // MenuItem::linkToCrud('Application', 'fa-cart-shopping', Application::class),
            // MenuItem::subMenu('Knowledge Hub', 'fa fa-tags')->setSubItems([
            //     MenuItem::linkToCrud('Blogs', 'fa-blogs', Blogs::class),
            //     MenuItem::linkToCrud('Blog Post', 'fa-blogs', BlogPost::class),
            // ]),
            // MenuItem::linkToCrud('Enquiry', 'fa-cart-shopping', Enquiry::class),
            // MenuItem::linkToCrud('Pages', 'fa-cart-shopping', Pages::class),
            MenuItem::linkToLogout('Logout', 'fa fa-sign-out'),

        ];
    }
}
