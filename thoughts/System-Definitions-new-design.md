#System Definitions position in the system
All systems should be able to function independently of the System Defintions, meaning
one should be able to make use of their full feature-set through an API, perhaps even
with an internal DSL ish' syntax. This makes it possible to test all parts.

The System definitions are then used for centrally defining aspects, so they are not repeated
over several sub-systems (eg. database, validation & model). The centralisation also
groups naturally related aspects better, giving a better overview of the system in a
vertical, rather than horizontal fashion.

The system definitions informs the system through factories and configurators,
that read the definition and applies the relevant aspects to the given model, form,
validator, database class or whatever might be relevant. The system definition
can also be used to inform derived models, such as models for language and/or
version control.

#System Definition Loading
- All relevant data should be loaded by external means
- All directives are parsed in an AST
- The model is build, honoring includes and extensions (the relevant content is loaded from the ast AND a note is added on the model)
    o In terms of inclusion / extension circular dependencies are not allowed but could occur and should result in an error
- The model is checked after it's build, ensuring that all relations, etc. are sound
    o In terms of types, Circular dependencies can occur and should not be a problem, as the it is only the existence that is checked at this point

#Deriving the system model
- The system definitions are initialized with a textual/array (text-tree) set of data
- The system definitions are finalized before use
- Parsing from DSL to text-tree should be done separately

##System model class design
- A system definition is a collection of definition components, the system definition a component itself
- Every system component contains a number of options and potentially a number of nested definition components
- A definition component is build by supplying one or more arrays of options
- The relevant definition component will validate the options as they receive them
- Before the definition component can be read, it must be finalized
- In finalizing the component, the relevant component will make sure that it's properties are valid

#System model properties and settings
- The core properties (options) with relevant apply methods, are the essential
  parts of the system definition.
- Additional info can be added as settings, but settings should follow this:
   * They are simple string/integer types
   * The system does not rely on them (a default value/behavior exist)
   * They do not come with any guarantees, and the finalization should not
     care about them

#Application architecture in relation to system definitions
- Generic classes, validators, forms, etc. react on system definition classes
  and hence do not need worry about the configuration or execution context
- Generic classes are configured by a factory, according to the system definition
- Validators, forms, etc. are configured according to the system definitions, independent of the generic class, which could have been changed
- Think of the system definition as a contract, that should always be honoured by generic classes, unless the specific class is explicitly changed
    o Meaning changes should be made in the system definitions as much as possible
