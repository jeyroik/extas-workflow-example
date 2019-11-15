<?php
namespace extas\components\plugins\workflows\transitions;

use extas\components\plugins\Plugin;
use extas\interfaces\IItem;
use extas\interfaces\workflows\entities\IWorkflowEntity;
use extas\interfaces\workflows\schemas\IWorkflowSchema;
use extas\interfaces\workflows\transitions\IWorkflowTransition;

/**
 * Class TransitionToDone
 *
 * @stage workflow.to.done
 * @package extas\components\plugins\workflows\transitions
 * @author jeyroik@gmail.com
 */
class TransitionToDone extends Plugin
{
    /**
     * @param IWorkflowEntity $entity
     * @param string $toState
     * @param IWorkflowTransition $transition
     * @param IWorkflowSchema $bySchema
     * @param IItem $withContext
     */
    public function __invoke(&$entity, $toState, $transition, $bySchema, &$withContext)
    {
        $withContext['success'] = true;
        $entity['operated'] = true;
    }
}
