<?php

namespace Louvre\TicketBundle\Controller;


use Louvre\TicketBundle\Entity\Booking;
use Louvre\TicketBundle\Entity\Ticket;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Louvre\TicketBundle\Form\BookingType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Louvre\TicketBundle\Form\TicketType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Constraints\DateTime;


class TicketController extends Controller
{
    public function homeAction(Request $request)
    {
        //Vérification du nombre de ticket disponible pour une date précise




        /*        $nbTicket = $repository->getNbTicket(new DateTime());*/
        $booking = new Booking();
        $ticket = new Ticket();
        $booking->addTicket($ticket);

        $formBooking = $this->createForm(BookingType::class, $booking);
        $formBooking->handleRequest($request);

        //Traitement du formulaire
        if ($formBooking->isSubmitted() && $formBooking->isValid()) {

            /*TODO
             *
             * //Après 14h les billets passent directement en demi journée pour la date d'aujourd'hui
            $today = date('Y-m-d');
            $time = intval(date('h'));
            $visitingDay = $booking->getVisitingDay();
            $visitingDay->format('Y-m-d');
            echo($today);
            echo($visitingDay);
            if(($visitingDay == $today)&&($time>9))
            {
                $booking->setHalfday(true);
            }*/

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

                return $this->redirectToRoute('louve_ticket_stripe');
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

        var_dump($bookingSession);
        $booking = new Booking();

        //Création du formulaire à partir de l'objet $booking instance de Booking
        $formBooking = $this->createForm(BookingType::class, $booking);

        $formBooking->handleRequest($request);

        //Récupération des tickets
        $ticketsBookingSession = $bookingSession->getTickets();

        //Calcul du prix de chaque ticket en fonction des entrées client
        $totalPriceBookingSession = 0;

        foreach ($ticketsBookingSession as $ticketBookingSession) {
            $ticketDate = $bookingSession->getVisitingDay();

            //Les tickets prennent la date de bookingSession
            $ticketBookingSession->setDate($ticketDate);

            //Calcul de l'âge du client
            $birthdayBookingSession = $ticketBookingSession->getBirthday();
            $today = new \Datetime();
            $interval = date_diff($birthdayBookingSession, $today);
            $age = $interval->y;

            //Calcul du prix grâce à l'âge et aux réductions
            if ($age < 4) {
                $ticketBookingSession->setPrice(0);
            } else if ($age >= 4 && $age <= 12) {
                $ticketBookingSession->setPrice(8);
            } else if ($age > 59) {
                $ticketBookingSession->setPrice(12);
            } else {
                if ($ticketBookingSession->getDiscount() == true) {
                    $ticketBookingSession->setPrice(10);
                } else {
                    $ticketBookingSession->setPrice(16);
                }
            }


            //Incrémentation de $totalprice pour le calcul du prix total
            $ticketPriceBookingSession = $ticketBookingSession->getPrice();
            $totalPriceBookingSession = $totalPriceBookingSession + $ticketPriceBookingSession;

        }


        $halfdayBookingSession = $bookingSession->getHalfday();
        //Le prix est divisé par 2 si la case pour la demi journée est pas cochée
        if ($halfdayBookingSession == true)
        {
            $totalPriceBookingSession = $totalPriceBookingSession / 2;
        }


        $bookingSession->setTotalPrice($totalPriceBookingSession);




        //Traitement du formulaire
        if ($formBooking->isSubmitted() && $formBooking->isValid())
        {
            //Récupération de stripe token pour récupérer l'email
            $token = $_POST['stripeToken'];
            Stripe::setApiKey('sk_test_i8AYP4qMtuAMTkYOBH3uLhZR');
            $stripeinfo = \Stripe\Token::retrieve($token);
            $email = $stripeinfo->email;

            //Hydratation manuelle de $booking avec booking session
            $booking->setUrl($email);
            $booking->setVisitingDay($bookingSession->getVisitingDay());
            $booking->setTotalPrice($bookingSession->getTotalPrice());
            $booking->setFullDay($bookingSession->getFullDay());
            $allTickets = $bookingSession->getTickets();
            foreach ($allTickets as $ticket)
            {
                $booking->addTicket($ticket);
            }


            $em = $this->getDoctrine()->getManager();
            $em->persist($booking);
            $em->flush();
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

    public function ticketsRemainingAction($date)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('LouvreTicketBundle:Ticket');
        $ticketsRemaining = $repository->findNbTicketsAtDate($date);

        return new Response($ticketsRemaining);
    }
}
