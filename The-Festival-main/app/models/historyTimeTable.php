<?php
require_once __DIR__ . '/HistoryEvent/language.php';
require_once __DIR__ . '/timetable.php';
require_once __DIR__ . '/eventDate.php';


class historyTimeTable
{

    private language $name;
    private timetable $time;
    private DateTime $date;

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     */
    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }

    public function __construct()
    {
        $this->name = new language();
        $this->time = new timetable();
//        $this->date = new eventDate();
    }

    /**
     * @return language
     */
    public function getName(): language
    {
        return $this->name;
    }

    /**
     * @param language $name
     */
    public function setName(language $name): void
    {
        $this->name = $name;
    }

    /**
     * @return timetable
     */
    public function getTime(): timetable
    {
        return $this->time;
    }

    /**
     * @param timetable $time
     */
    public function setTime(timetable $time): void
    {
        $this->time = $time;
    }
}