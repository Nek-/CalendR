<?php

/*
 * This file is part of CalendR, a Fréquence web project.
 *
 * (c) 2012 Fréquence web
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CalendR\Renderer;

use CalendR\Period\PeriodInterface,
    CalendR\Period\Day,
    CalendR\Period\Week,
    CalendR\Period\Month,
    CalendR\Period\Year;

/**
 * Interface far all calendar renderers
 *
 * @author Yohan Giarelli <yohan@giarelli.org>
 */
interface RendererInterface
{
    /**
     * Render method. Returns rendered calendar
     *
     * @param CalendR\Period\PeriodInterface $period
     * @param array $options
     * @return string
     */
    public function render(PeriodInterface $period, array $options = array());

    /**
     * Returns the name of the renderer
     *  ex: html_table
     *
     * @return string
     */
    public function getName();
}
