<?php
namespace extas\components\plugins\workflows\validators;

use extas\components\plugins\Plugin;
use extas\interfaces\IItem;
use extas\interfaces\workflows\entities\IWorkflowEntity;
use extas\interfaces\workflows\schemas\IWorkflowSchema;
use extas\interfaces\workflows\transitions\dispatchers\ITransitionDispatcher;
use extas\interfaces\workflows\transitions\dispatchers\ITransitionDispatcherExecutor;
use extas\interfaces\workflows\transitions\IWorkflowTransition;
use extas\interfaces\workflows\transitions\results\ITransitionResult;

/**
 * Class ValidatorHelloJeyroik
 *
 * @package extas\components\plugins\workflows\validators
 * @author jeyroik@gmail.com
 */
class ValidatorHelloJeyroik extends Plugin implements ITransitionDispatcherExecutor
{
    /**
     * @param ITransitionDispatcher $dispatcher
     * @param IWorkflowTransition $transition
     * @param IWorkflowEntity $entitySource
     * @param IWorkflowSchema $schema
     * @param IItem $context
     * @param ITransitionResult $result
     * @param IWorkflowEntity $entityEdited
     *
     * @return bool
     */
    public function __invoke(
        ITransitionDispatcher $dispatcher,
        IWorkflowTransition $transition,
        IWorkflowEntity $entitySource,
        IWorkflowSchema $schema,
        IItem $context,
        ITransitionResult &$result,
        IWorkflowEntity &$entityEdited
    ): bool
    {
        if (!isset($context['name'])) {
            echo '(!) Missed "name" in a context<br/>';
            return false;
        }

        if (!$dispatcher->getParameter('name')) {
            echo '(!) Missed "name" in a validator<br/>';
            return false;
        }

        if ($context['name'] != $dispatcher->getParameter('name')->getValue('')) {
            echo '(!) Incorrect name in a context<br/>';
            return false;
        }

        return true;
    }
}
