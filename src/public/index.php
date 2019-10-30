<?php
require(__DIR__ . '/../../vendor/autoload.php');

if (is_file(__DIR__ . '/../../.env')) {
    $dotenv = \Dotenv\Dotenv::create(__DIR__ . '/../../');
    $dotenv->load();
}

$transitManager = new \extas\components\workflows\WorkflowTransitHelper([
    'state' => 'todo',
    'operated' => false
], [
    'name' => 'jeyroik'
]);

$transitManager->transitToState('done');
$transitManager->transitToState('in_work');
$transitManager->transitWithTransition('done');
$transitManager->transitToState('not_actual');
