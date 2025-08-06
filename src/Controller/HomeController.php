<?php

namespace App\Controller;

use App\Entity\ContactUs;
use App\Entity\Services;
use App\Form\ContactUsFormType;
use App\Repository\BlogRepository;
use App\Repository\ContactUsRepository;
use App\Repository\ServicesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\Mime\Email;

final class HomeController extends AbstractController
{
    public function __construct(
        private MailerInterface $mailer
    ) {}
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $projects = [
            1 => [
                'name' => 'Premalu',
                'image' => 'https://bhavanastudios.com/wp-content/uploads/2024/01/538X798.jpg',
                'year' => '2024',
                'description' => 'Sachin pursues romance but finds himself caught between two potential partners, leading to amusing complications.',
                'trailerUrl' => 'https://youtu.be/rR_2ti4l3nM',
                'director' => 'Girish AD',
                'writer' => 'Girish AD and Kiran Josey',
                'cast' => 'Naslen, Mamitha Baiju, Althaf Salim, Akhila Bhargavan, Meenakshi Raveendran, Sangeeth Prathap, Shyam Mohan M',
                'music' => 'Vishnu Vijay',
                'cinematography' => 'Ajmal Sabu',
                'editor' => 'Akash Joseph Varghese',
                'duration' =>  156,
                'releaseDate' => '9 February 2024',
                'language' => 'Malayalam'
            ],
            2 => [
                'name' => 'Thankam',
                'image' => 'https://bhavanastudios.com/wp-content/uploads/2023/10/Thankam-2.jpg'
            ],
            3 => [
                'name' => 'Paltu Janwar',
                'image' => 'https://bhavanastudios.com/wp-content/uploads/2023/10/Paalthu-janvar.jpg'
            ],
            4 => [
                'name' => 'Joji',
                'image' => 'https://bhavanastudios.com/wp-content/uploads/2023/10/JOJI-538-x-798.jpg'
            ],
            5 => [
                'name' => 'Kumbalangi Nights',
                'image' => 'https://bhavanastudios.com/wp-content/uploads/2023/10/Kumbaliangi-Nights.jpg'
            ]
        ];
        return $this->render('home/index.html.twig', [
            'projects' => $projects
        ]);
    }

    #[Route('/about-us', name: 'app_about_us')]
    public function aboutUs()
    {
        return $this->render('home/about.html.twig', []);
    }

    #[Route('/our-team', name: 'app_our_team')]
    public function ourTeam()
    {
        $collaborators = [
            [
                "name" => "Parthan C Ram",
                "role" => "Chief Associate Director",
                "description" => "Brings structure, rhythm, and sharp intuition to every project.",
                "instagram" => "https://www.instagram.com/ram_parthan/",
                "imdb" => "https://www.imdb.com/name/nm14016721/",
                "image" => "ram.webp"
            ],
            [
                "name" => "Samant Christopher Lakra",
                "role" => "Sound Designer",
                "description" => "Creates immersive sonic worlds that elevate the emotional impact.",
                "instagram" => "https://www.instagram.com/noisereaction.in/",
                "imdb" => "https://www.imdb.com/name/nm6379348/",
                "image" => "christopher.webp"
            ],
            [
                "name" => "Sreevalsan R S",
                "role" => "Editor",
                "description" => "Crafting compelling narratives through seamless cuts and cinematic storytelling.",
                "instagram" => "https://www.instagram.com/sreevalsanrs/?hl=en",
                "imdb" => "https://www.imdb.com/name/nm11925989/",
                "imageNeeded" => true,
                "image" => "sreevalsan.webp"
            ],
            [
                "name" => "Arun Rama Varma",
                "role" => "Sound Designer",
                "description" => "Creates immersive sonic worlds that elevate the emotional impact.",
                "instagram" => "https://www.instagram.com/arunramavarmathampuran/",
                "imdb" => "https://www.imdb.com/name/nm3450758/",
                "image" => "arun.webp"
            ],
            [
                "name" => "Asim Kottoor",
                "role" => "Coordinator",
                "description" => "Ensuring smooth execution by managing schedules, logistics, and on-set coordination.",
                "instagram" => "https://www.instagram.com/asimkottoor/",
                "imdb" => "https://www.imdb.com/name/nm11261738/",
                "image" => "asim.webp"
            ],
            [
                "name" => "Shihab Vennala",
                "role" => "Production Controller",
                "description" => "Overseeing budgets, resources, and timelines to ensure efficient and cost-effective production.",
                "instagram" => "https://www.instagram.com/shihabvennala/",
                "imdb" => "https://www.imdb.com/name/nm9389299/",
                "image" => "shihab.webp"
            ],
            [
                "name" => "Vignesh Radhakrishnan",
                "role" => "Sound Designer & Sync Sound Recordist",
                "description" => "Designing immersive audio landscapes and capturing crystal-clear sync sound to elevate every frame.",
                "instagram" => "https://www.instagram.com/rkvicky1990?igsh=MXF0Njk5dDB2dnZzbA==",
                "imdb" => "https://m.imdb.com/name/nm8532938/",
                "imageNeeded" => true,
                "image" => "vignesh.webp"
            ],
            [
                "name" => "Mashar Hamza",
                "role" => "Costume Designer",
                "description" => "Bringing characters to life through thoughtfully crafted costumes that reflect story and style.",
                "instagram" => "https://www.instagram.com/masharhamsa/?hl=en",
                "imdb" => "https://m.imdb.com/name/nm7946719/",
                "image" => "hamsa.webp"
            ],
            [
                "name" => "Rashid Sulaiman",
                "role" => "Dynamic Visual Artist",
                "description" => "Creating striking visual elements that enhance storytelling through motion, design, and imagination.",
                "instagram" => "https://www.instagram.com/merakiartport?igsh=MXg4emEwc3M0cG9kdQ==",
                "imdb" => "https://www.instagram.com/merakiartport?igsh=MXg4emEwc3M0cG9kdQ==",
                "imageNeeded" => true,
                "image" => "rashid.webp"
            ]
        ];

        return $this->render('home/team.html.twig', [
            'collaborators' => $collaborators
        ]);
    }

    #[Route('/our-services', name: 'app_our_services')]
    public function ourServices(ServicesRepository $servicesRepository)
    {
        $services = [
            [
                'title' => 'Script Writing & Development',
                'icon' => 'fas fa-feather-alt', // feather/pen for writing
                'points' => [
                    'Original screenplays for films, series, and ad films',
                    'Script doctoring, dialogue writing, and adaptation',
                    'Pitch decks and story bibles for producers and OTT platforms',
                ],
            ],
            [
                'title' => 'Ad Film Production',
                'icon' => 'fas fa-video', // represents advertising and media
                'points' => [
                    'End-to-end ad film creation (concept to post-production)',
                    'Brand commercials, digital ads, and social media campaigns',
                    'Cinematic storytelling for product launches and promos',
                ],
            ],
            [
                'title' => 'Film Production',
                'icon' => 'fas fa-film', // for filmmaking
                'points' => [
                    'Short & indie feature film development & production',
                    'Co-production with emerging filmmakers',
                    'Budgeting, scheduling, & on-ground execution',
                    'Line production services for third-party films',
                ],
            ],
            [
                'title' => 'Branded Content & Digital Storytelling',
                'icon' => 'fas fa-bullhorn', // fits digital content campaigns
                'points' => [
                    'Story-led branded films and content campaigns',
                    'Digital content for web and social platforms',
                    'Content strategy aligned with brand identity',
                ],
            ],
            [
                'title' => 'Writers’ Room & Creative Development',
                'icon' => 'fas fa-pen-nib', // collaboration / writing
                'points' => [
                    'Collaborative script development for long-form',
                    'Screenplay formatting and structural support',
                    'Ideation for pitch-worthy concepts',
                ],
            ],
            [
                'title' => 'Co-Production & Creative Partnerships',
                'icon' => 'fas fa-handshake', // partnership
                'points' => [
                    'Co-investment in indie or small-budget projects',
                    'Creative collab with writers, directors, producers',
                    'Pitch prep for film festivals or OTT platforms',
                ],
            ],
            [
                'title' => 'Creative Consulting',
                'icon' => 'fas fa-lightbulb', // ideas/consulting
                'points' => [
                    'Story and screenplay guidance',
                    'Visual storytelling and narrative shaping',
                    'Support for creators, brands, and agencies',
                ],
            ],
        ];

        return $this->render('home/services.html.twig', ['services' => $services]);
    }

    #[Route('/services/{slug}', name: 'app_our_services_detail')]
    public function serviceDetail(ServicesRepository $servicesRepository, $slug)
    {
        $service = $servicesRepository->findOneBy(['isActive' => true, 'slug' => $slug]);
        $otherServices = $servicesRepository->findRandomServices();

        if (!$service) {
            throw $this->createNotFoundException('Service not found');
        }

        return $this->render('service-detail.html.twig', ['service' => $service, 'otherServices' => $otherServices]);
    }

    #[Route('/blogs', name: 'app_blogs')]
    public function blogs(BlogRepository $blogRepository)
    {
        $blogs = $blogRepository->findBy(['isActive' => true], ['createdAt' => 'DESC']);

        return $this->render('home/blogs.html.twig', ['blogs' => $blogs]);
    }

    #[Route('/blogs/{slug}', name: 'app_blog_detail')]
    public function blogDetail(BlogRepository $blogRepository, $slug)
    {
        $blog = $blogRepository->findOneBy(['isActive' => true, 'slug' => $slug], ['createdAt' => 'DESC']);

        $randomBlogs = $blogRepository->findRandomBlogs($slug);

        return $this->render('home/blog-detail.html.twig', ['blog' => $blog, 'randomBlogs' => $randomBlogs]);
    }

    #[Route('/admin/contact/us/list', name: 'admin_contact_us_list')]
    public function adminContactUs(ContactUsRepository $contactUsRepository)
    {
        $contacts = $contactUsRepository->findAll();
        return $this->render('admin/contact-listing.html.twig', ['contacts' => $contacts]);
    }
}
