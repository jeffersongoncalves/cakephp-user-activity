# CakePHP 3 UserActivity Logging plugin

[![Buy Me A Coffee](https://img.shields.io/badge/Buy%20Me%20A%20Coffee-support-FFDD00?style=flat-square&logo=buy-me-a-coffee&logoColor=black)](https://buymeacoffee.com/jeffersongoncalves)

Logs every Create, Update and Delete made through the CakePHP ORM: attach `UserActivityBehavior`
to a table and every save/delete on it is recorded in a `logs` row (who, when, which table/row,
which action) plus one `logs_details` row per changed field (old value / new value).

Based on [crabstudio/UserActivity](https://github.com/crabstudio/UserActivity).

## Requirements

- PHP >=7.0
- CakePHP `cakephp/cakephp` ^3.6

## Installation

```bash
composer require jeffersonsimaogoncalves/cakephp-user-activity
```

Load the plugin in `config/bootstrap.php`:

```php
Plugin::load('JeffersonSimaoGoncalves/UserActivity');
```

Create the `logs` and `logs_details` tables in your database (bake a migration for them with
`cakephp/migrations`, or create them manually) — `LogsTable` expects at least: `id`,
`table_name`, `database_name`, `primary_key`, `action`, `description`, `created_by`, `name`,
`recycle`, `created`, `modified`; `LogsDetailsTable` expects: `id`, `log_id`, `field_name`,
`old_value`, `new_value`, `created`, `modified`.

## Usage

Attach the behavior to any table you want to log:

```php
// src/Model/Table/UsersTable.php
public function initialize(array $config)
{
    parent::initialize($config);

    $this->addBehavior('JeffersonSimaoGoncalves/UserActivity.UserActivity');
}
```

From then on, every `save()` (create or update) and `delete()` on that table writes a `Log`
entity (`action` = `C`/`U`/`D`, `table_name`, `database_name`, `primary_key` as JSON, a
Portuguese `description` such as "Criado um registro em users com sucesso") plus one
`LogsDetail` row per changed column, capturing `old_value`/`new_value` (JSON-encoded for
`json`-typed columns). Deletes are recorded as `recycle = true` with every column's current
value logged as `old_value`.

### Attributing the current user

`created_by`/`name` on each log entry come from an app-defined `getUserAuth()` function, if one
exists in the global namespace:

```php
function getUserAuth(string $field)
{
    $user = \Cake\Routing\Router::getRequest()->getAttribute('identity');

    return $user ? $user->get($field) : null;
}
```

Without a `getUserAuth()` function, `created_by`/`name` are left `null`.

### Using a separate database connection

```php
Configure::write('JeffersonSimaoGoncalves/UserActivity.connection', 'log_db');
```

### Querying recent activity

```php
$Logs = TableRegistry::getTableLocator()->get('JeffersonSimaoGoncalves/UserActivity.Logs');
$recent = $Logs->find('latest', ['limit' => 50]);
```

## Credits

- [anhtuank7c](https://github.com/anhtuank7c) — author of the original [crabstudio/UserActivity](https://github.com/crabstudio/UserActivity)
- [Jèfferson Simão Gonçalves](https://github.com/jeffersonsimaogoncalves)

## License

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.
