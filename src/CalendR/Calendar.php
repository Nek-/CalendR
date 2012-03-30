<?php

/*
 * This file is part of CalendR, a Fréquence web project.
 *
 * (c) 2012 Fréquence web
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CalendR;

use CalendR\Renderer\RendererInterface,
    CalendR\Period\PeriodInterface,
    CalendR\Event\Manager;

/**
 * Factory class for calendar handling
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class Calendar
{
    /**
     * @var \CalendR\Event\Manager
     */
    private $eventManager;

    /**
     * @var array
     */
    private $renderers = array();

    /**
     * @param \DateTime|int $yearOrStart
     * @return Period\Year
     */
    public function getYear($yearOrStart)
    {
        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-01-01', $yearOrStart));
        }

        return new Period\Year($yearOrStart);
    }

    /**
     * @param \DateTime|int $yearOrStart year if month is filled, month begin datetime otherwise
     * @param null|int $month number (1~12)
     * @return \CalendR\Period\Month
     */
    public function getMonth($yearOrStart, $month = null)
    {
        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-%s-01', $yearOrStart, $month));
        }

        return new Period\Month($yearOrStart);
    }

    /**
     * @param \DateTime|int $yearOrStart
     * @param null|int $week
     * @return Period\Week
     */
    public function getWeek($yearOrStart, $week = null)
    {
        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-W%s', $yearOrStart, str_pad($week, 2, '0', STR_PAD_LEFT)));
        }

        return new Period\Week($yearOrStart);
    }

    /**
     * @param \DateTime|int $yearOrStart
     * @param null|int $month
     * @param null|int $day
     */
    public function getDay($yearOrStart, $month = null, $day = null)
    {
        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-%s-%s', $yearOrStart, $month, $day));
        }

        return new Period\Day($yearOrStart);
    }

    /**
     * Returns an array of events for the given period
     *
     * @param Period\PeriodInterface $period
     * @return array|Event\EventInterface
     */
    public function getEvents(PeriodInterface $period)
    {
        return $this->eventManager->find($period);
    }

    /**
     * @param Period\PeriodInterface $period
     * @param string $renderer
     * @param array $options
     * @return mixed
     */
    public function render(PeriodInterface $period, $renderer, array $options = array())
    {
        if (isset($options['with_events']) && true === $options['with_events']) {
            $this->getEvents($period, isset($options['event_options']) ? $options['event_options'] : array());
            $options['factory'] = $this;
        }

        return $this->getRenderer($renderer)->render($period, $options);
    }

    /**
     * Add a renderer to the available ones.
     *
     * @param Renderer\RendererInterface $renderer
     * @return \CalendR\Calendar
     */
    public function addRenderer(RendererInterface $renderer)
    {
        $this->renderers[$renderer->getName()] = $renderer;

        return $this;
    }

    /**
     * Returns the wanted renderer
     *
     * @param $name
     * @return \CalendR\Renderer\RendererInterface
     * @throws Renderer\Exception\NotFound
     */
    public function getRenderer($name)
    {
        if (!isset($this->renderers[$name])) {
            throw new Renderer\Exception\NotFound;
        }

        return $this->renderers[$name];
    }

    /**
     * @param Manager $eventManager
     */
    public function setEventManager(Manager $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    /**
     * @return Manager
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }
}
