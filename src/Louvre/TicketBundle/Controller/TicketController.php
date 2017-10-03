<?php

namespace Louvre\TicketBundle\Controller;


use Louvre\TicketBundle\Entity\Booking;
use Louvre\TicketBundle\Entity\Ticket;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Louvre\TicketBundle\Form\BookingType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;


class TicketController extends Controller
{
    public function homeAction(Request $request)
    {
        /*$nbTicket = $repository->getNbTicket(new DateTime());*/
        $booking = new Booking();
        $ticket = new Ticket();
        $booking->addTicket($ticket);

        $formBooking = $this->createForm(BookingType::class, $booking);
        $formBooking->handleRequest($request);

        //Traitement du formulaire
        if ($formBooking->isSubmitted() && $formBooking->isValid()) {

            //Après 14h les billets passent directement en demi journée pour la date d'aujourd'hui
            $today = date('Y-m-d');
            $time = intval(date('H'));
            $visitingDay = $booking->getVisitingDay();
            $visitingDate= date_format($visitingDay,('Y-m-d'));
            if(($visitingDate == $today)&&($time>13))
            {
                $booking->setHalfday(true);
            }

            // Vérification de la date et du nombre de tickets disponibles
            $repository = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('LouvreTicketBundle:Ticket');

            $remaining = $repository->findNbTicketsAtDate($booking->getVisitingDay());

            //Récupération du nombre de ticket ajouté par le client
            $newTicketsAdded = count($booking->getTickets());
            $totalTickets = $remaining - $newTicketsAdded;

            //Filtre du nombre de tickets
            if($totalTickets < 0)
            {

                $this->get('session')->getFlashBag()
                    ->add('notice', 'Vous avez saisi un trop grand nombre de billets.');

                return $this->render('LouvreTicketBundle:Ticket:home.html.twig', array(
                    'formBooking' => $formBooking->createView(),
                    'remainingTicket' => $remaining
                ));
            }else{
                $bookingSession = new Session();
                $bookingSession->set('booking', $booking);

                return $this->redirectToRoute('louvre_ticket_stripe');
            }
        };

        return $this->render('LouvreTicketBundle:Ticket:home.html.twig', array(
            'formBooking' => $formBooking->createView()
        ));
    }


    public function stripeAction(Request $request)
    {
        //Appel de la session récupération de l'objet booking de type Booking
        $bookingSession = $this->get('session')->get('booking');
        $booking = new Booking();

        //Création du formulaire à partir de l'objet $booking instance de Booking
        $formBooking = $this->createForm(BookingType::class, $booking);

        $formBooking->handleRequest($request);

        $calculatePrice = $this->container->get('louvre.calculatePrice');
        // $bookingPriced est l'objet booking une fois le prix calculé
        $bookingPriced = $calculatePrice->totalPriceOf($bookingSession);

        //Traitement du formulaire
        if ($formBooking->isSubmitted() && $formBooking->isValid())
        {
            //Récupération de stripe token pour récupérer l'email
            $token = $_POST['stripeToken'];
            Stripe::setApiKey('sk_test_i8AYP4qMtuAMTkYOBH3uLhZR');
            $stripeinfo = \Stripe\Token::retrieve($token);
            $userEmail = $stripeinfo->email;

            //Hydratation manuelle de $booking avec booking session
            $bookingPriced->setUrl($userEmail);
            $bookingPriced->setLastnameBooking($booking->getLastnameBooking());
            $bookingPriced->setFirstnameBooking($booking->getFirstnameBooking());
            $bookingPriced->setVisitingDay($bookingSession->getVisitingDay());
            $bookingPriced->setTotalPrice($bookingSession->getTotalPrice());
            $bookingPriced->setHalfDay($bookingSession->getHalfDay());
            $allTickets = $bookingSession->getTickets();
            foreach ($allTickets as $ticket)
            {
                $bookingPriced->addTicket($ticket);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($bookingPriced);
            $em->flush();

            $message = (new \Swift_Message('Vos billets pour le Louvre'))
                ->setFrom(array('chugustudio@gmail.com'=>"Le Louvre"))
                ->setTo($userEmail)
                ->setCharset('utf-8')
                ->setContentType('text/html')
                ->setBody($this->renderView('LouvreTicketBundle:Emails:email.html.twig'));

            var_dump($message);

            $this->container->get('mailer')->send($message);





            $request->getSession()->getFlashBag()->add('success', 'Booking à bien enregistré.');
            return $this->redirectToRoute('louvre_ticket_recap');
        }


        return $this->render('LouvreTicketBundle:Ticket:stripe.html.twig',array(
            'formBooking' => $formBooking->createView()));
    }

    public function recapAction(Request $request)
    {

        return $this->render("LouvreTicketBundle:Ticket:recap.html.twig", array(

        ));
    }

    public function ticketsRemainingAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $date = htmlspecialchars($request->query->get('date'));
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('LouvreTicketBundle:Ticket');
            $ticketsRemaining = $repository->findNbTicketsAtDate($date);
            $response = new Response(json_encode($ticketsRemaining));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }else{
            return new Response("Erreur");
        }

    }

}
