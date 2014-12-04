#System Definitions position in the system
All systems should be able to function independently of the System Definitions, meaning
one should be able to make use of their full feature-set through an API, perhaps even
with an internal DSL'ish syntax. This makes it possible to test all parts, independent
of the system definitions.

The System definitions are then used for centrally defining aspects, so that they are not repeated
over several sub-systems (eg. database, validation, model, forms, etc.). The centralisation also
groups naturally related aspects better, giving a better overview of the system in a
vertical, rather than horizontal fashion.

The system definitions informs the system through factories and configurators,
that read the definition and applies the relevant aspects to the given model, form,
validator, database class or whatever might be relevant. The system definition
can also be used to inform derived models, such as models for language and/or
version control.

#System Definition Loading
This section describes how the system definition is derived from the directive
files and validated in the various stages of the process.

##Overall description
 - The core tells the directive reader, which files to scan
 - The directive reader creates a tree of directive objects
 - The interpreter builds the entity, and applies inheritance and includes in correct order, to build complete entities
 - The interpreter loads completed entities into the system definition structures, including abstract entities, which are marked accordingly
 - The system definition structures validates the content, relations and inheritance in the supplied entities
 - When the system uses the system definitions, relevant parts reacts if relevant settings are malconfigured

CORE:
- All files to be included are declared and added to the directive reader

DIRECTIVE READER:
- The files are read one by one and parsed into an intermediate list of directive
  statements. No validation of content is done, but an error will be raised if a
  directive is obviously malformed (eg. unknown type, or no label).
  The directive statement list entries consists of:
    * Indent number
    * Type
    * Label
    * Value
    * Origin file
    * Line number
- Based on the indents, the directive statements are turned into a tree structure
- The directive reader returns the tree structure of directive statements, where
  each node contains information about:
    * Type
    * Label
    * Value
    * Origin (File and line number)
    * Parent node
    * Child nodes
- The produced tree of directives is the output

DIRECTIVE INTERPRETER
- The directive tree array is the input for the interpreter
- The interpreter builds entities, by recursively converting directives into
  definition components.
- It starts by going over all dependencies (inheritance and inclusion), and
  produces a mapping of dependency sets to their respective entity names
- It starts with the entities that does not have dependencies, and then moves
  on to those that have only dependencies that are already processed, including
  these in the correct order. The interpreter does not concern itself with
  the compatibility of the relations, but only verifies the existence of such
  relations, through the dependency mapping.
- If it is not possible to interpret all entities, due to missing dependencies,
  it means that an entity is either not defined, or that a circular reference
  exists.

SYSTEM DEFINITIONS
- The system definition does a recursive validation of all components, which:
    * Validates both presence and format of all relevant values
    * Validates settings and sub-components configuration
    * Validates relations (existence of related components)
    * Validates dependencies (inheritance and inclusion), including
      compatibility of overrides
- The system definition is locked, and can not be changed

RUNNING SYSTEM
- When the various components uses the system definition, for their individual
  purposes, they should give an error, if the system is configured in a non-
  compatible way. Eg. this could be the AccessControlHandler, which discovers
  the reference to a non-existing right

- The model is build, honoring includes and extensions (the relevant content is loaded from the AST AND a note is added on the model)
    * In terms of inclusion / extension circular dependencies are not allowed but could occur and should result in an error
- The model is checked after it's build, ensuring that all relations, etc. are sound
    * In terms of types, Circular dependencies can occur and should not be a problem, as the it is only the existence that is checked at this point

#Deriving the system model
- The system definitions are initialized with a textual/array (text-tree) set of data
- The system definitions are finalized before use
- Parsing from DSL to text-tree should be done separately

##System definition class design
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
- Generic classes, validators, forms, etc. are constructed by the factory with
  system definition classes as the recipe and hence do not need worry about the
  configuration or execution context
- Generic classes are configured by a factory, according to the system definition
- Validators, forms, etc. are configured according to the system definitions, independent of the generic class, which could have been changed
- Think of the system definition as a contract, that should always be honoured by generic classes, unless the specific class is explicitly changed
    o Meaning changes should be made in the system definitions as much as possible

