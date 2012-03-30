<?php

/*
 * This file is part of CalendR, a Fréquence web project.
 *
 * (c) 2012 Fréquence web
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CalendR\Renderer\Html;

use CalendR\Period\PeriodInterface,
    CalendR\Period\Day,
    CalendR\Period\Week,
    CalendR\Period\Month,
    CalendR\Period\Year;

class TableRenderer extends HtmlRenderer
{
    /**
     * @{inheritDoc}
     */
    public function render(PeriodInterface $period, array $options = array())
    {
        switch (true) {
            case $period instanceof Day:
                return $this->renderDay($period, $options);
            case $period instanceof Week:
                return $this->renderWeek($period, $options);
            case $period instanceof Month:
                return $this->renderMonth($period, $options);
            case $period instanceof Year:
                return $this->renderYear($period, $options);
        }

        throw new \InvalidArgumentException('Invalid period type : '.get_class($period));
    }

    /**
     * Renders a day
     *
     * @abstract
     * @param \CalendR\Period\Day $day
     * @param array $options
     */
    protected function renderDay(Day $day, array $options)
    {
        $attributes = array();
        if (isset($options['month']) && !$options['month']->contains($day->getBegin())){
            $attributes['class'] = 'out-of-month';
        }

        return $this->renderTag('span', $day->getBegin()->format('d'), $attributes);
    }

    /**
     * Renders a week
     *
     * @abstract
     * @param \CalendR\Period\Week $week
     * @param array $options
     */
    protected function renderWeek(Week $week, array $options)
    {
        $html = '';
        foreach ($week as $day) {
            $html .= $this->renderTag('td', $this->renderDay($day, $options));
        }

        if (isset($options['month'])) {
            return $html;
        }

        return $this->renderTag('table', $this->renderTag('tbody', $this->renderTag('tr', $html)));
    }

    /**
     * Renders a month
     *
     * @abstract
     * @param \CalendR\Period\Month $month
     * @param array $options
     */
    protected function renderMonth(Month $month, array $options)
    {
        $html = '';
        foreach ($month as $week) {
            $html .= $this->renderTag(
                'tr',
                $this->renderWeek($week, array_merge($options, array('month' => $month)))
            );
        }

        return $this->renderTag('table', $this->renderTag('tbody', $html));
    }

    /**
     * Renders a year
     *
     * @abstract
     * @param \CalendR\Period\Year $year
     * @param array $options
     */
    protected function renderYear(Year $year, array $options)
    {
        // TODO: Implement renderYear() method.
    }

    /**
     * @{inheritDoc}
     */
    public function getName()
    {
        return 'html_table';
    }
}
