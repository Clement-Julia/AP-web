<?php
class Month {
    /**
     * Month constructor
     * @param int $month Le mois compris entre 1 et 12
     * @param int $year L'année
     * @throws Exception
     */

    public $days = ['L', 'M', 'M', 'J', 'V', 'S', 'D'];
    public $month;
    public $year;
    
    private $months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

    public function __construct(?int $month = null, ?int $year = null)
    {
        if ($month === null)
        {
            $month = intval(date('m'));
        }
        if ($year === null)
        {
            $year = intval(date('Y'));
        }
        if ($month < 1 || $month > 12)
        {
            throw new Exception("Le mois $month n'est pas valide");
        }
        $this->month = $month;
        $this->year = $year;
    }

    /**
     * Renvoie le premier jour du mois
     * @return \DateTime
     */
    public function getStartingDay (): \DateTime {
        return new \DateTime("{$this->year}-{$this->month}-01");
    }

    public function toString () : string {
        return $this->months[$this->month - 1] . ' ' . $this->year;
    }

    public function getWeeks (): int {
        $start = $this->getStartingDay();
        $end = (clone $start)->modify('+1 month -1 day');
        $weeks = intval($end->format('W')) - intval($start->format('W')) + 1;
        if ($weeks < 0)
        {
            $weeks = intval($end->format('W'));
        }
        return $weeks;
    }

    public function withinMonth (\DateTime $date): bool {
        return $this->getStartingDay()->format('Y-m') === $date->format('Y-m');
    }

    /**
     * Renvoie le mois suivant
     * @return Month
     */
    public function nextMonth(): Month{
        $month = $this->month + 1;
        $year = $this->year;
        if ($month > 12){
            $month = 1;
            $year += 1;
        }
        return new Month($month, $year);
    }
    
    /**
     * Renvoie le mois précédent
     * @return Month
     */
    public function prevMonth(): Month{
        $month = $this->month - 1;
        $year = $this->year;
        if ($month < 1){
            $month = 12;
            $year -= 1;
        }
        return new Month($month, $year);
    }

}