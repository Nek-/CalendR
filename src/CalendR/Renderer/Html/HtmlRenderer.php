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

use CalendR\Renderer\RendererInterface;

/**
 * Base class for HTML renderers
 */
abstract class HtmlRenderer implements RendererInterface
{
    /**
     * Renders an HTML tag
     *
     * @param $tag
     * @param $content
     * @return string
     */
    protected function renderTag($tag, $content, array $attributes = array())
    {
        return strtr(
            '<%tag%%attributes%>%content%</%tag%>',
            array('%tag%' => $tag, '%content%' => $content, '%attributes%' => $this->renderAttributes($attributes))
        );
    }

    /**
     * Render an attributes array
     *
     * @param array $attributes
     * @return string
     */
    protected function renderAttributes(array $attributes)
    {
        $attributesStr = '';
        foreach ($attributes as $attribute => $value) {
            $attributesStr = sprintf(' %s="%s"', $attribute, $value);
        }

        return $attributesStr;
    }

    /**
     * Return an array of localized day names
     *
     * @param string $locale
     * @param null $timezone
     * @return array|string
     */
    protected function getDayNames($locale = 'en', $timezone = null)
    {
        $names = array();
        $day = new \DateTime('2012-W01');
        for ($i = 0 ; $i < 7 ; $i++) {
            $names[] = $this->getDayName($day, $locale, $timezone);
            $day->add(new \DateInterval('P1D'));
        }

        return $names;
    }

    /**
     * Return an array of localized month names
     *
     * @param string $locale
     * @param null $timezone
     * @return array|string
     */
    protected function getMonthNames($locale = 'en', $timezone = null)
    {
        $formatter = \IntlDateFormatter::create(
            $locale,
            \IntlDateFormatter::NONE,
            \IntlDateFormatter::NONE,
            $timezone,
            \IntlDateFormatter::GREGORIAN,
            'LLLL'
        );

        $names = array();
        $month = new \DateTime('2012-01-01');
        for ($i = 0 ; $i < 12 ; $i++) {
            $names[] = $formatter->format((int)$month->format('U'));
            $month->add(new \DateInterval('P1M'));
        }

        return $names;
    }

    protected function getDayName(\DateTime $day, $locale = 'en', $timezone = null)
    {
        $formatter = \IntlDateFormatter::create(
            $locale,
            \IntlDateFormatter::NONE,
            \IntlDateFormatter::NONE,
            $timezone,
            \IntlDateFormatter::GREGORIAN,
            'EEEE'
        );

        return $formatter->format((int)$day->format('U'));
    }
}
