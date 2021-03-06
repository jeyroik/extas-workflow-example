<?php
namespace extas\components\plugins\workflows\triggers;

use extas\components\plugins\Plugin;
use extas\interfaces\IItem;
use extas\interfaces\workflows\entities\IWorkflowEntity;
use extas\interfaces\workflows\schemas\IWorkflowSchema;
use extas\interfaces\workflows\transitions\dispatchers\ITransitionDispatcher;
use extas\interfaces\workflows\transitions\dispatchers\ITransitionDispatcherExecutor;
use extas\interfaces\workflows\transitions\IWorkflowTransition;
use extas\interfaces\workflows\transitions\results\ITransitionResult;

/**
 * Class TriggerHelloWorld
 *
 * @package extas\components\plugins\workflows\triggers
 * @author jeyroik@gmail.com
 */
class TriggerHelloWorld extends Plugin implements ITransitionDispatcherExecutor
{
    /**
     * @param ITransitionDispatcher $dispatcher
     * @param IWorkflowTransition $transition
     * @param IWorkflowEntity $entitySource
     * @param IWorkflowSchema $schema
     * @param IItem $context
     * @param ITransitionResult &$result
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
        $map = [
            'ru' => 'Привет мир<br/>',
            'en' => 'Hello world<br/>'
        ];

        $defaultLang = $dispatcher->getParameter('lang')->getValue('en');
        $lang = isset($context['lang']) ? $context['lang'] : $defaultLang;

        echo $map[$lang] ?? $map[$defaultLang];

        return true;
    }
}
