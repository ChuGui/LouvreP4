<?php

namespace Louvre\TicketBundle\Service\Mail;


class SendEmail
{
    public function sendEmailTo($booking, \Swift_Mailer $mailer)
    {
        //Préparation des variables pour la vue twig

            $nomAcheteur = $booking->getLastname();
            $prenomAcheteur = $booking->getFirstname();
            $journee = "";
            if (($booking->getHalfday()) == true) {
                $journee = "Demi-journée";
                return $journee;
            } else {
                $journee = "Journée entière";
                return $journee;
            }

            $nTicket = 1;
            $tickets = $booking->getTickets();
            $billets = [];
            foreach ($tickets as $ticket) {
                //Préparation des variables pour chaque billet
                $nom = $ticket->getLastname();
                $prenom = $ticket->getFirstname();
                $dateVisite = $ticket->getDate();
                $dateVisiteFr = date_format($dateVisite, 'd/m/Y');
                $reduction = "";
                $discount = $ticket->getDiscount();
                if ($discount == true) {
                    $reduction = "Réduction : Oui. A justifier à l'accueil du Louvre";
                    return $reduction;
                } else {
                    $reduction = "Réduction: Pas de réduction.";
                    return $reduction;
                }

                //Calcul de l'âge du client
                $birthday = $ticket->getBirthday();
                $today = new \Datetime();
                $interval = date_diff($birthday, $today);
                $age = $interval->y;
                $prix = $ticket->getPrice();
                $billetHtml = "<h2>Billet n°" . $nTicket . ":</h2>" . "<h3>Billet valable le : " . $dateVisiteFr . " </h3>" . "<h4>Type de billet : " . $journee . "</h4> <br> <p> Nom: " . $nom . ". <br> Prénom: " . $prenom . "<br> Age : " . $age . " an(s) <br>" . "Tarif : " . $prix . "€ </p><p><strong>" . $discountMsg . "</strong></p><br>";
                array_push($billets, $billetHtml);
                $nTicket ++;
            }
                //Pour chaque email on a donc $nomAcheteur, $prenomAcheteur, $journee, $nom, $prenom, $age, $dateVisite, $prix,  $reduction
        $message = (new \Swift_Message('Vos billets pour le Louvre'))
            ->setFrom('chugustudio@gmail.com')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    'Emails/email.html.twig', array(
                        'nomAcheteur'=> $nomAcheteur,
                        'prenomAcheteur'=> $prenomAcheteur,
                        'billets'=>$billet
                    ), 'text/html'
                )
            );
        $mailer->send($message);

    }
}