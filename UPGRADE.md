# Upgrade Guide - Laravel 4.2 para Laravel 5.0

Este documento descreve as mudanças realizadas no pacote `creolab/laravel-modules` para torná-lo compatível com Laravel 5.0.

## Mudanças Principais

### 1. Composer.json
- Atualizada versão do Laravel de `~4` para `5.0.*`
- Atualizada versão mínima do PHP de `>=5.3.0` para `>=5.4.0`
- Atualizada descrição do pacote para "Laravel 5"

### 2. ServiceProvider.php
- Substituído `$this->package()` por `$this->publishes()` e `$this->mergeConfigFrom()`
- Substituído `$app->share()` por `$app->singleton()` em todos os bindings
- Configurações agora são publicadas usando o método `publishes()` com a tag 'config'

### 3. Module.php
- Removida chamada ao método `$this->package()` no método `register()`
- Atualizadas referências de configuração de `modules::` para `modules.`

### 4. Finder.php
- Atualizadas todas as referências de configuração de `modules::` para `modules.`
- Mantida compatibilidade com o construtor

### 5. Commands
- **AbstractCommand.php**: 
  - Atualizado método `displayModules()` para usar `$this->table()` ao invés do helper de tabela do Symfony
  - Substituído `app()->make('path.base')` por `base_path()`
  
- **ModulesScanCommand.php**: 
  - Removida referência ao helper de tabela (`$this->getHelperSet()->get('table')`)
  
- **ModulesMigrateCommand.php**: 
  - Substituído `app_path('storage')` por `storage_path()`
  - Substituído `app()->make('path.base')` por `base_path()`
  
- **ModulesPublishCommand.php**: 
  - Substituído `app()->make('path.base')` por `base_path()`
  - Substituído `app()->make('path.public')` por `public_path()`
  - Atualizada referência de configuração de `modules::path` para `modules.path`
  
- **ModulesCreateCommand.php**: 
  - Atualizada referência de configuração de `modules::path` para `modules.path`

### 6. Configuração
- Arquivos de configuração agora são acessados usando a notação de ponto (`.`) ao invés de dois pontos duplos (`::`)
- Exemplo: `modules::path` → `modules.path`

## Instruções de Instalação

### Publicar Configuração

No Laravel 5.0, use o seguinte comando para publicar a configuração:

```bash
php artisan vendor:publish
```

O arquivo de configuração será publicado em `config/modules.php`.

### Atualizar Provider

No arquivo `config/app.php`, registre o service provider:

```php
'providers' => [
    // ...
    Creolab\LaravelModules\ServiceProvider::class,
],
```

### Estrutura de Diretórios

A estrutura de módulos permanece a mesma:

```
app/
|-- modules
    |-- auth
        |-- controllers
        |-- models
        |-- views
        |-- routes.php
        |-- module.json
    |-- content
        |-- controllers
        |-- models
        |-- views
        |-- module.json
```

## Comandos Disponíveis

Todos os comandos permanecem os mesmos:

- `php artisan modules` - Lista todos os módulos
- `php artisan modules:scan` - Escaneia e cria manifest de módulos
- `php artisan modules:create [nome]` - Cria um novo módulo
- `php artisan modules:publish [nome]` - Publica assets dos módulos
- `php artisan modules:migrate [nome]` - Executa migrations dos módulos
- `php artisan modules:seed [nome]` - Executa seeders dos módulos
- `php artisan modules:generate [modulo] [tipo] [recurso]` - Gera recursos para módulos

## Compatibilidade

Este pacote agora é compatível com:
- Laravel 5.0.*
- PHP >= 5.4.0

## Notas Importantes

1. O método `$app->share()` foi deprecado no Laravel 5.0 e substituído por `$app->singleton()`
2. O método `$this->package()` foi removido no Laravel 5.0 e substituído por `publishes()` e `mergeConfigFrom()`
3. Helpers de path foram atualizados para usar as funções globais do Laravel 5.0
4. A notação de configuração de pacotes mudou de `package::config` para `config`
5. O método `table()` de comandos foi simplificado no Laravel 5.0

## Troubleshooting

### Erro: "Class config not found"
Execute `php artisan vendor:publish` para publicar a configuração.

### Erro: "Undefined method publishes"
Certifique-se de que está usando Laravel 5.0 ou superior.

### Módulos não são carregados
Verifique se o diretório de módulos está no autoload do `composer.json` e execute `composer dump-autoload`.

