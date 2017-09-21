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


class TicketController extends Controller
{
    public function homeAction(Request $request)
    {
        $booking = new Booking();

        $booking->setUrl('test@test.com');
        $booking->setTotalPrice(20);
        $booking->setBookingCode('65fq4sd65f4q6');

        $ticket = new Ticket();
        $ticket->setPrice(5);


        $booking->addTicket($ticket);
        $formBooking = $this->createForm(BookingType::class, $booking);
        $formBooking->handleRequest($request);
        if ($formBooking->isSubmitted() && $formBooking->isValid()) {
            $bookingSession = new Session();
            $bookingSession-> set('booking', $booking);

            return $this->redirectToRoute('louvre_ticket_stripe');
        }

        return $this->render('LouvreTicketBundle:Ticket:home.html.twig', array(
            'formBooking' => $formBooking->createView()
        ));
    }


    public function stripeAction(Request $request)
    {
        /*$bookingSession = $this->get('session')->get('booking');
        $formBooking = $this->createForm(BookingType::class, $bookingSession);
        $formBooking->handleRequest($request);*/

        /*if ($formBooking->isSubmitted() && $formBooking->isValid()) {

            var_dump($formBooking);
            return $this->redirectToRoute('louvre_ticket_recap');
        }*/
        return $this->render('LouvreTicketBundle:Ticket:stripe.html.twig')
        ;
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
        var_dump($bookingSession);
        $bookingSession->set

        return $this->render("LouvreTicketBundle:Ticket:recap.html.twig", array(
            'token' => $token
        ));
    }
}
