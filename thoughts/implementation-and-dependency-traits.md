#Dependencies
We want our system to be as independent as possible, which is why we use
dependency injection. The dependency injection comes in two forms, namely
implementations handled by the configuration and dependency traits handled
by the factory.

##Implementations in configuration
By querying the configuration with a class name we can get the relevant
implementation. This allows our code to load classes while the ability
to swap the files lies in the configuration. This should be use for class
instantiation, where no pre-configured options are required and the
ability to change the dependency is only required system-wide, eg. changing
a presentation-related class to serve RTL language.

The configuration is used like so:

In the configurator:
```php
$config->implementations->put(
    "\\FlyFoundation\\Core\\Router",
    "\\MyApp\\Core\\Router"
);
```
When using
```php
$routerClass = $this->getAppConfig->getImplementation("\\FlyFoundation\\Core\\Router");
$router = Factory::load($routerClass);
```

##Dependency traits
The dependency traits allows for richer dependencies. They implement getters and
setters for the dependency directly in the class, and the factory makes sure
to set them to the defaults defined in the configuration automatically. This
should be used for dependencies which have complex instantiation and/or needs
the ability to be set on a per instance basis. This could be an e-mail library,
where one part of the system might send e-mail in one way, while another part
should do it in another (different providers / techniques).

The dependency traits are used simply applying the "use" statement in the
implementing class, and stating the following rule in the configuration:

```php
$emailer = new SomeEmailLib();
$emailer->setUser('Test');
$emailer->setPassword('Demo');
$config->dependencies->putDependency(
    "\\FlyFoundation\\Dependcies\\AppEmailer",
    $emailer,
    true
);
```

The trait must be implemented following naming conventions.