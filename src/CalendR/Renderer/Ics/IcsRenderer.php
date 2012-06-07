<?php

namespace CalendR\Renderer\Ics;

use \CalendR\Renderer\RendererInterface,
    CalendR\CalendR;

/**
 * Renderer in iCal format
 *
 * See documentation here:
 * http://www.kanzaki.com/docs/ical/ or http://en.wikipedia.org/wiki/ICalendar
 */
class IcsRenderer implements RendererInterface
{

    /**
     * PRODID field
     * @var string
     */
    protected $prodid;

    /**
     * Take information for the final generated file
     * @param string $organization='CalendR'
     * @param string $name
     * @param string $version='1.0'
     * @param string $culture='EN'
     */
    public function __construct(CalendR $factory, $prodid = '-//CalendR//NONSGML CalendR calendar v0.1//EN')
    {
        $this->factory = $factory;
    }

    /**
     * Render method. Returns rendered calendar
     *
     * @param CalendR\Period\PeriodInterface $period
     * @param array $options
     * @return string
     */
    public function render(PeriodInterface $period, array $options = array()) {
        $factoryOptions = array();
        if (!empty($options['factory_options'])) {
            if (is_array($options['factory_options']) {
                $factoryOptions = $options['factory_options'];

            } else {
                throw new \InvalidArgumentException('My "factory_options" option must be an array !');
            }
        }


        $calendar = new qCal(array(
            'prodid' => $this->prodid
        ));

        foreach ($this->factory->getEvents($period, $factoryOptions) as $event) {
            $event = new \qCal_Component_Vevent(array(
                'dtend' => $event->getBegin()->format('Ymd\TH:iZ'),
                'dtstart' => $event->getStart()->format('Ymd\TH:iZ'),
                'duration' => (new DateTime)->format('Ymd\TH:iZ'),
                'summary' => $event->getTitle()
            ));
            $calendar->attach($event);
        }

        return $calendar->render();
    }

    /**
     * Returns the name of the renderer
     *  ex: html_table
     *
     * @return string
     */
    public function getName() {
        return 'ics';
    }
}
