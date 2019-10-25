<?php
require(__DIR__ . '/../../vendor/autoload.php');

use extas\components\workflows\Workflow;
use extas\components\SystemContainer;
use extas\interfaces\workflows\schemas\IWorkflowSchema;
use extas\interfaces\workflows\schemas\IWorkflowSchemaRepository;
use extas\components\DemoEntity;
use extas\components\DemoContext;

if (is_file(__DIR__ . '/../../.env')) {
$dotenv = \Dotenv\Dotenv::create(__DIR__ . '/../../');
$dotenv->load();
}

$schemaRepo = SystemContainer::getItem(IWorkflowSchemaRepository::class);
$schema = $schemaRepo->one([IWorkflowSchema::FIELD__NAME => 'demo']);

$testEntity = new DemoEntity([], 'todo');
$transited = Workflow::transit($testEntity, 'done', $schema, new DemoContext(['name' => 'jeyroik']));

$transited = Workflow::transit(
    $testEntity,
    'in_work', $schema,
    new DemoContext([
            'name' => 'jeyroik',
            'lang' => 'ru'
        ]
    )
);

$transited = Workflow::transit(
    $testEntity,
    'done', $schema,
    new DemoContext([
            'name' => 'jeyroik',
            'lang' => 'ru'
        ]
    )
);

$transited = Workflow::transit(
    $testEntity,
    'not_actual', $schema,
    new DemoContext([
            'name' => 'jeyroik',
            'lang' => 'ru'
        ]
    )
);
