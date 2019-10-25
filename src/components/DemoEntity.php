<?php
namespace extas\components;

use extas\interfaces\workflows\entities\IWorkflowEntity;

/**
 * Class DemoEntity
 *
 * @package extas\components
 * @author jeyroik@gmail.com
 */
class DemoEntity implements IWorkflowEntity
{
    protected $state = '';
    protected $data = [];

    /**
     * Entity constructor.
     *
     * @param $data
     * @param $state
     */
    public function __construct($data, $state)
    {
        $this->data = $data;
        $this->state = $state;
    }

    /**
     * @return $this
     */
    public function setOperated()
    {
        $this->data['operated'] = true;

        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getStateName(): string
    {
        return $this->state;
    }

    /**
     * @param string $stateName
     *
     * @return IWorkflowEntity
     */
    public function setStateName(string $stateName): IWorkflowEntity
    {
        $this->state = $stateName;

        return $this;
    }
}
