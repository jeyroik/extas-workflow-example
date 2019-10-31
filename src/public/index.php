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

if (!isset($_REQUEST['state'], $_REQUEST['transition'])) {
    $transitManager->transitToState('in_work');
} else {
    $map = [
        'todo', 'in_work', 'done', 'not_actual'
    ];
    $curStateIndex = (int) ($_REQUEST['state']['from'] ?? 0);
    $curState = $map[$curStateIndex] ?? 'todo';

    $transitManager = new \extas\components\workflows\WorkflowTransitHelper([
        'state' => $curState,
        'operated' => false
    ], [
        'name' => 'jeyroik'
    ]);

    $toState = (int) ($_REQUEST['state']['to'] ?? null);
    if (!$toState) {
        $map = [
            'get_in_work', 'done', 'not_actual__from_todo', 'not_actual__from_in_work'
        ];
        $transitionIndex = (int) ($_REQUEST['transition'] ?? 0);
        $transition = $map[$transitionIndex] ?? 'get_in_work';
        $transitManager->transitWithTransition($transition);
    } else {
        $state = $map[$toState] ?? 'todo';
        $transitManager->transitToState($state);
    }
}
?>
<form action="/">
    <input type="submit" value="Сбросить">
</form>
