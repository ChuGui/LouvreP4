<?php

namespace Louvre\TicketBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Booking
 *
 * @ORM\Table(name="booking")
 * @ORM\Entity(repositoryClass="Louvre\TicketBundle\Repository\BookingRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Booking
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var string $lastnameBooking
     *
     * @ORM\Column(name="lastnameBooking", type="string", length=255)
     */
    private $lastnameBooking;

    /**
     * @var string $firstnameBooking
     *
     * @ORM\Column(name="firstnameBooking", type="string", length=255)
     */
    private $firstnameBooking;

    /**
     * @var int
     *
     * @ORM\Column(name="totalPrice", type="integer")
     */
    private $totalPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="bookingCode", type="string", length=255)
     */
    private $bookingCode;

    /**
     * @var bool
     *
     * @ORM\Column(name="fullday", type="boolean")
     */
    private $fullday;

    /**
     * @var \date
     *
     * @ORM\Column(name="visitingDay", type="date")
     */
    private $visitingDay;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="buyingDay", type="datetime")
     */
    private $buyingDay;

    /**
     * @var int
     *
     * @ORM\Column(name="$nbTickets", type="integer")
     */
    private $nbTickets;

    /**
     * @ORM\OneToMany(targetEntity="Louvre\TicketBundle\Entity\Ticket", mappedBy="booking", cascade={"persist"}))
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\Valid
     *
     */
    private $tickets;



    /**
     * @ORM\PrePersist
     */
    public function updateAt()
    {
        $this->setBuyingDay(new \DateTime());
    }

    public function increaseApplication()
    {
        $this->nbTickets++;
    }



    /**
     * @ORM\PrePersist
     */
    public function createBookingCode()
    {
        $random_hash = substr(md5(uniqid(rand(), true)), 16, 16);
        $this->setBookingCode($random_hash);
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Booking
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set totalPrice
     *
     * @param integer $totalPrice
     *
     * @return Booking
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    /**
     * Get totalPrice
     *
     * @return integer
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * Set bookingCode
     *
     * @param string $bookingCode
     *
     * @return Booking
     */
    public function setBookingCode($bookingCode)
    {
        $this->bookingCode = $bookingCode;

        return $this;
    }

    /**
     * Get bookingCode
     *
     * @return string
     */
    public function getBookingCode()
    {
        return $this->bookingCode;
    }

    /**
     * Set fullday
     *
     * @param boolean $fullday
     *
     * @return Booking
     */
    public function setFullday($fullday)
    {
        $this->fullday = $fullday;

        return $this;
    }

    /**
     * Get fullday
     *
     * @return boolean
     */
    public function getFullday()
    {
        return $this->fullday;
    }

    /**
     * Set visitingDay
     *
     * @param \DateTime $visitingDay
     *
     * @return Booking
     */
    public function setVisitingDay($visitingDay)
    {
        $this->visitingDay = $visitingDay;

        return $this;
    }

    /**
     * Get visitingDay
     *
     * @return \DateTime
     */
    public function getVisitingDay()
    {
        return $this->visitingDay;
    }

    /**
     * Set buyingDay
     *
     * @param \DateTime $buyingDay
     *
     * @return Booking
     */
    public function setBuyingDay($buyingDay)
    {
        $this->buyingDay = $buyingDay;

        return $this;
    }

    /**
     * Get buyingDay
     *
     * @return \DateTime
     */
    public function getBuyingDay()
    {
        return $this->buyingDay;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tickets = new ArrayCollection();
        $this->visitingDay = new \Datetime();
        $this->quantity = 1;
    }

    /**
     * Add ticket
     *
     * @param \Louvre\TicketBundle\Entity\Ticket $ticket
     *
     * @return Booking
     */
    public function addTicket(\Louvre\TicketBundle\Entity\Ticket $ticket)
    {
        $this->tickets[] = $ticket;

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param \Louvre\TicketBundle\Entity\Ticket $ticket
     */
    public function removeTicket(\Louvre\TicketBundle\Entity\Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }



    /**
     * Get lastnameBooking
     *
     * @return string
     */
    public function getLastnameBooking()
    {
        return $this->lastnameBooking;
    }

    /**
     * Set $lastnameBooking
     *
     * @param string $lastnameBooking
     *
     * @return Booking
     */
    public function setLastnameBooking($lastnameBooking)
    {
        $this->lastnameBooking = $lastnameBooking;

        return $this;
    }

    /**
     * Set firstnameBooking
     *
     * @param string $firstnameBooking
     *
     * @return Booking
     */
    public function setFirstnameBooking($firstnameBooking)
    {
        $this->firstnameBooking = $firstnameBooking;

        return $this;
    }

    /**
     * Get firstnameBooking
     *
     * @return string
     */
    public function getFirstnameBooking()
    {
        return $this->firstnameBooking;
    }

    /**
     * Set nbTickets
     *
     * @param integer $nbTickets
     *
     * @return Booking
     */
    public function setNbTickets($nbTickets)
    {
        $this->nbTickets = $nbTickets;

        return $this;
    }

    /**
     * Get nbTickets
     *
     * @return integer
     */
    public function getNbTickets()
    {
        return $this->nbTickets;
    }
}
