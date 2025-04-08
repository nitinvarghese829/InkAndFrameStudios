<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
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
        return $this->render('home/team.html.twig', []);
    }

    #[Route('/our-services', name: 'app_our_services')]
    public function ourServices()
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
}
