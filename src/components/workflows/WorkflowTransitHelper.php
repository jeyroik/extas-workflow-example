<?php
namespace extas\components\workflows;

use extas\components\SystemContainer;
use extas\components\workflows\entities\WorkflowEntityContext;
use extas\interfaces\IItem;
use extas\interfaces\workflows\entities\IWorkflowEntity;
use extas\interfaces\workflows\entities\IWorkflowEntityTemplate;
use extas\interfaces\workflows\entities\IWorkflowEntityTemplateRepository;
use extas\interfaces\workflows\schemas\IWorkflowSchema;
use extas\interfaces\workflows\schemas\IWorkflowSchemaRepository;

/**
 * Class WorkflowTransitHelper
 *
 * @package extas\components\workflows
 * @author jeyroik@gmail.com
 */
class WorkflowTransitHelper
{
    /**
     * @var IWorkflowEntity
     */
    protected $entity = null;

    /**
     * @var IItem
     */
    protected $context = null;

    /**
     * @var IWorkflowSchema
     */
    protected $schema = null;

    /**
     * WorkflowTransitHelper constructor.
     *
     * @param array $entityData
     * @param array $contextData
     */
    public function __construct(array $entityData, array $contextData)
    {
        $this->setEntity($entityData)->setContext(new WorkflowEntityContext($contextData))->setSchema();
    }

    /**
     * @param array $entityData
     *
     * @return $this
     */
    public function setEntity(array $entityData)
    {
        /**
         * @var $entityTemplatesRepo IWorkflowEntityTemplateRepository
         * @var $template IWorkflowEntityTemplate
         * @var $testEntity \extas\interfaces\workflows\entities\IWorkflowEntity
         */

        $entityTemplatesRepo = SystemContainer::getItem(IWorkflowEntityTemplateRepository::class);
        $template = $entityTemplatesRepo->one([
            IWorkflowEntityTemplate::FIELD__NAME => 'message'
        ]);
        $this->entity = $template->buildClassWithParameters($entityData);

        echo 'Current state = ' . $this->entity->getStateName() . '<br/><br/>';

        return $this;
    }

    /**
     * @param IItem $context
     *
     * @return $this
     */
    public function setContext(IItem $context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @param string $stateName
     */
    public function transitToState(string $stateName)
    {
        $this->printBeforeTransition();
        $this->printOnRunToState($stateName);

        $transited = Workflow::transitByState(
            $this->entity,
            $stateName,
            $this->schema,
            $this->context
        );

        $this->printAfterRun($transited);
        $this->printAvailableTransitions();
    }

    /**
     * @param string $transitionName
     */
    public function transitWithTransition(string $transitionName)
    {
        $this->printBeforeTransition();
        $this->printOnRunToTransition($transitionName);

        $transited = Workflow::transitByTransition(
            $this->entity,
            $transitionName,
            $this->schema,
            $this->context
        );

        $this->printAfterRun($transited);
        $this->printAvailableTransitions();
    }

    /**
     * @return $this
     */
    protected function setSchema()
    {
        $schemaRepo = SystemContainer::getItem(IWorkflowSchemaRepository::class);
        $this->schema = $schemaRepo->one([IWorkflowSchema::FIELD__NAME => 'demo']);

        return $this;
    }

    /**
     * Print current entity state
     */
    protected function printBeforeTransition()
    {
        echo '<br/>Current state = ' . $this->entity->getStateName();
    }

    /**
     * @param string $stateName
     */
    protected function printOnRunToState(string $stateName)
    {
        echo '<br/>Run transition to "' . $stateName . '":<br/>Transition runtime data:<br/>=====================<br/>';
    }

    /**
     * @param string $transitionName
     */
    protected function printOnRunToTransition(string $transitionName)
    {
        echo '<br/>Run transition "' . $transitionName
            . '":<br/>Transition runtime data:<br/>=====================<br/>';
    }

    /**
     * @param $transited
     */
    protected function printAfterRun($transited)
    {
        echo '<br/>=====================<br/>Transition result: '. ($transited ? 'success' : 'failed') . '<br/>';
    }

    /**
     * Print list of available transitions
     */
    protected function printAvailableTransitions()
    {
        $mapState = ['todo' => 0, 'in_work' => 1, 'done' => 2, 'not_actual' => 3];
        $mapTransit = ['get_in_work' => 0, 'done' => 1, 'not_actual__from_todo' => 2, 'not_actual__from_in_work' => 3];
        $curState = $mapState[$this->entity->getStateName()] ?? 0;

        $available = $this->schema->getAvailableTransitionsByFromState($this->entity, new WorkflowEntityContext([]));
        echo '<br/>Available actions (transitions):<br><ul>';
        foreach ($available as $transition) {
            $transitIndex = $mapTransit[$transition->getName()] ?? 0;
            echo '<li><form action="/" method="post">'
                .' <input type="hidden" name="state[from]" value="'.$curState.'"> '
                .' <input type="hidden" name="transition" value="'.$transitIndex.'"> '
                .' <input type="submit" value="'.$transition->getName().'"> '
                . ' (change status to "' . $transition->getStateToName() . '")</form></li>';
        }
        echo '</ul>';
    }
}
