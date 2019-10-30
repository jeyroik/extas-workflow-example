# extas-workflow-example

Пример использования пакета jeyroik/extas-workflow

# установка workflow

`# vednor/bin/extas i`

# запуск

`extas-workflow-example# php -S localhost:8080 -t src/public`

После этого на страничке `http://localhost:8080` вы должны увидеть следующее:

```text
Current state = todo
Available actions (transitions):
- get_in_work (change status to "in_work")
- not_actual__from_todo (change status to "not_actual")

Run transition to "done":
Transition runtime data:
=====================

=====================
Transition result: failed

Current state = todo
Available actions (transitions):
- get_in_work (change status to "in_work")
- not_actual__from_todo (change status to "not_actual")

Run transition to "in_work":
Transition runtime data:
=====================
Привет мир
Entity new state is "in_work"

=====================
Transition result: success

Current state = in_work
Available actions (transitions):
- done (change status to "done")
- not_actual__from_in_work (change status to "not_actual")

Run transition to "done":
Transition runtime data:
=====================
Entity new state is "done"

=====================
Transition result: success

Current state = done
Available actions (transitions):

Run transition to "not_actual":
Transition runtime data:
=====================

=====================
Transition result: failed
```

# Объяснение

- В рамках примера устанавливается 4 состояния: `todo`, `in_work`, `done`, `not_actual`.
- Для этих состояний устанавливаются следующие переходы: `get_in_work`, `done`, `not_actual`.
- Также устаналивается несколько шаблонов для обработки переходов.
  - `trigger__hello_world` - после перехода выводит сообщение "Hello world" на языке, установленным в контексте.
  - `trigger__transition_finished` - вывод сообщение, что сущность переведена в состояние с указанием названия нового состояния.
  - `validator__hello_jeyroik` - проверяет наличие в контексте параметра `name` и его равенство строке `jeyroik`.
  - `validator__context_params` - проверяет наличие в контексте параметров, указанных в обработчике.
  - `validator__entity_params` - проверяет наличие в сущности, меняющей состояние, параметров, указанных в обработчике.
- Кроме этого устанавливается схема, содержащая все указанные ранее состояния и переходы.
- Для установленной схемы workflow сразу устанавливаются и конкретные триггеры и валидаторы.

## Описание задачи, которую решает пример

- Имеется сущность `DemoEntity` в состоянии `todo`.
- Необходимо прогнать данную сущность по всем переходам схемы.
- Требуется вывести на русском языке фразу "Привет мир".
- Требуется после каждой смены состояния вывести соответствующее сообщение.
- Требуется гаранитровать, что в переходы осуществляются с именем 'jeyroik'.
- Требуется гарантировать, что в контексте после перевода сущности в состояние done будет параметр `success`, равный `true`.
- Требуется гарантировать, что в сущности после перевода сущности в состояние done будет параметр `operated`, равный `true`.

## Решение задачи

- Для того, чтобы решить поставленную задачу, в контекст помещаем следующие данные:
 ```php
$context = new DemoContext([
    'name' => 'jeyroik',
    'lang' => 'ru'
]);
```

- Далее, для всех переходов устанавливаем триггер по шаблону `trigger__transition_finished`.
- Кроме этого, на переход в статус `done` вешаем валидаторы по параметрам контекста и сущности по шаблонам `validator__context_params` и `validator__entity_params` соответственно.
- Для удовлетворения требования по поводу `success` и `operated` создадим плагин, который будет реагировать на перевод в состояние `done`. 
  - Для этого плагин должен реагировать на стадию `workflow.to.done`.
  
Всё вместе решает поставленную задачу, удовлетворяя всем требованиям.