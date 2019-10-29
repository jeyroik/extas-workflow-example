<?php
require(__DIR__ . '/../../vendor/autoload.php');

use extas\components\workflows\Workflow;
use extas\components\SystemContainer;
use extas\interfaces\workflows\schemas\IWorkflowSchema;
use extas\interfaces\workflows\schemas\IWorkflowSchemaRepository;
use extas\components\DemoContext;
use extas\interfaces\workflows\entities\IWorkflowEntityTemplateRepository;
use extas\interfaces\workflows\entities\IWorkflowEntityTemplate;

/**
 * @var $schemaRepo IWorkflowSchemaRepository
 * @var $entityTemplatesRepo IWorkflowEntityTemplateRepository
 * @var $template IWorkflowEntityTemplate
 * @var $testEntity \extas\interfaces\workflows\entities\IWorkflowEntity
 * @var $schema IWorkflowSchema
 */

if (is_file(__DIR__ . '/../../.env')) {
    $dotenv = \Dotenv\Dotenv::create(__DIR__ . '/../../');
    $dotenv->load();
}

$schemaRepo = SystemContainer::getItem(IWorkflowSchemaRepository::class);
$entityTemplatesRepo = SystemContainer::getItem(IWorkflowEntityTemplateRepository::class);

$schema = $schemaRepo->one([IWorkflowSchema::FIELD__NAME => 'demo']);
$template = $entityTemplatesRepo->one([
    IWorkflowEntityTemplate::FIELD__NAME => 'message'
]);
$testEntity = $template->buildClassWithParameters([
    'state' => 'todo',
    'operated' => false
]);

print_r($schema->getAvailableTransitionsByFromState($testEntity, new DemoContext([])));

$transited = Workflow::transit(
    $testEntity,
    'done',
    $schema,
    new DemoContext([
        'name' => 'jeyroik'
    ])
);
$transited = Workflow::transit(
    $testEntity,
    'in_work', $schema,
    new DemoContext([
        'name' => 'jeyroik',
        'lang' => 'ru'
    ])
);

print_r($schema->getAvailableTransitionsByFromState($testEntity, new DemoContext([])));

$transited = Workflow::transit(
    $testEntity,
    'done', $schema,
    new DemoContext([
        'name' => 'jeyroik',
        'lang' => 'ru'
    ])
);

print_r($schema->getAvailableTransitionsByFromState($testEntity, new DemoContext([])));

$transited = Workflow::transit(
    $testEntity,
    'not_actual', $schema,
    new DemoContext([
        'name' => 'jeyroik',
        'lang' => 'ru'
    ])
);

print_r($schema->getAvailableTransitionsByFromState($testEntity, new DemoContext([])));