<?php
namespace extas\components;

/**
 * Class DemoContext
 *
 * @package extas\components
 * @author jeyroik@gmail.com
 */
class DemoContext extends Item
{
    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return 'demo.context';
    }
}
