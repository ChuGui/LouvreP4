<?php

namespace Louvre\TicketBundle\Controller;


use Louvre\TicketBundle\Entity\Booking;
use Louvre\TicketBundle\Entity\Ticket;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Louvre\TicketBundle\Form\BookingType;
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
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('LouvreTicketBundle:Ticket');

        /*        $nbTicket = $repository->getNbTicket(new DateTime());*/
        $booking = new Booking();

        $booking->setUrl('test@test.com');
        $booking->setTotalPrice(20);
        $booking->setBookingCode('65fq4sd65f4q6');

        $ticket = new Ticket();
        $booking->addTicket($ticket);

        $formBooking = $this->createForm(BookingType::class, $booking);
        $formBooking->handleRequest($request);
        if ($formBooking->isSubmitted() && $formBooking->isValid()) {


            $bookingSession = new Session();
            $bookingSession->set('booking', $booking);

            return $this->redirectToRoute('louvre_ticket_stripe');
        }

        return $this->render('LouvreTicketBundle:Ticket:home.html.twig', array(
            'formBooking' => $formBooking->createView()
        ));
    }


    public function stripeAction(Request $request)
    {

        $bookingSession = $this->get('session')->get('booking');
        $formBooking = $this->createForm(BookingType::class, $bookingSession);
        $tickets = $bookingSession->getTickets();
        $totalPrice = 0;
        foreach ($tickets as $ticket) {
            $ticketDate = $bookingSession->getVisitingDay();
            $ticket->setDate($ticketDate);
            $birthday = $ticket->getBirthday();
            $today = new \Datetime();
            $interval = date_diff($birthday, $today);
            $age = $interval->y;
            if ($age < 4) {
                $ticket->setPrice(0);
            } else if ($age >= 4 && $age <= 12) {
                $ticket->setPrice(8);
            } else if ($age > 59) {
                $ticket->setPrice(12);
            } else {
                if ($ticket->getDiscount() == true) {
                    $ticket->setPrice(10);
                } else {
                    $ticket->setPrice(16);
                }

            }
            var_dump($ticket);
            $ticketPrice = $ticket->getPrice();
            $totalPrice = $totalPrice + $ticketPrice;

        }

        var_dump($totalPrice);
        $fullday = $bookingSession->getFullDay();
        var_dump($fullday);
        if ($fullday = true) {
            $totalPrice = $totalPrice / 2;
        }
        var_dump($totalPrice);


        $formBooking = $this->createForm(BookingType::class, $bookingSession);

        $formBooking->handleRequest($request);

        if ($formBooking->isSubmitted() && $formBooking->isValid())
        {

            $em = $this->getDoctrine()->getManager();
            var_dump($formBooking);
            $em->persist($bookingSession);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Booking à bien enregistré.');
            return $this->redirectToRoute('louvre_ticket_recap');
        }


        return $this->render('LouvreTicketBundle:Ticket:stripe.html.twig',array(
            'formBooking' => $formBooking->createView()));
    }

    public function recapAction(Request $request)
    {
        $token = $_POST['stripeToken'];
        $lastname = $_POST['lastname'];
        $firstname = $_POST['firstname'];
        Stripe::setApiKey('sk_test_i8AYP4qMtuAMTkYOBH3uLhZR');
        $stripeinfo = \Stripe\Token::retrieve($token);
        $email = $stripeinfo->email;
        $bookingSession = $this->get('session')->get('booking');
        $bookingSession->setLastnameBooking($lastname);
        $bookingSession->setFirstnameBooking($firstname);
        $bookingSession->setUrl($email);;


        return $this->render("LouvreTicketBundle:Ticket:recap.html.twig", array(
            'token' => $token
        ));
    }
}
