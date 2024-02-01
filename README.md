# Cart Module

File has been created with `module/create` command. 

## Installation

In order to add the modules to your project go into the modules section of your config:

```php
return [
    'modules' => [
        // ...
        'forms' => [
            'class' => 'luya\forms\Module',
            'useAppViewPath' => true, 
        ],
        
        // ...
    ],
    'components' => [
        'class' => 'siripravi\forms\components\Forms'
    ],
      // ...
];
```
