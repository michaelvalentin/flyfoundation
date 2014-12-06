#Dependencies
We want our system to be as independent as possible, which is why we use
dependency injection. The dependency injection comes in two forms, both
defined in the configuration and handled by the factory.

##Implementations
When loading a class through the factory, the factory checks with the
configuration, if the demanded class has any overriding implementations. Any
implementation must extend originally demanded class and comply with its
interface. This should be used for class instantiation, where no pre-configured
options are required and the ability to change the dependency is only required
system-wide.

Implementations are set in the configuration like so:
```php
$config->implementations->put(
    "\\FlyFoundation\\Core\\Router",
    "\\MyApp\\Core\\Router"
);
```
Classes are loaded like so
```php
/** @var Router $router */
$router = Factory::load("\\FlyFoundation\\Core\\Router");
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

The trait must be implemented following the naming conventions, where
[TraitName] is trait class without namespace directives:
 - getter must be named get[TraitName]
 - setter msut be named set[TraitName]