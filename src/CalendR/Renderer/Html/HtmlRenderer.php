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
}
