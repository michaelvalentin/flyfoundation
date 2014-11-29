.... This ended up being pretty much a waste of time, so I skipped it ;-)

#System Definition Model Specification
The intention of this document, is to describe how the System Definition Model
should behave. Since the System Definition does not relate to either objects in
the real world, nor has similar counterparts in other systems, its features
and specifications are less obvious, which is why we consider them here.

##Structure
The system definition is a tree structure, but with restrictions to which nodes
can inherit which nodes. The structure is like so:

 - SystemDefinition
    - EntityDefinitions
        - FieldDefinitions
            - FilterDefinitions
            - AccessControlDefinitions
        - ValidationDefinitions
        - IndexDefinitions
        - AccessControlDefinitions
    - AccessControls

##Definition component
All definition components should adhere to some basic specifications, described
by the following properties:
 -

##System definition
This specific component can be described with the following properties:
 -

##Entity definition
This specific component can be described with the following properties:
 -

##Field definition
This specific component can be described with the following properties:
 -

##Persistent field definition
This specific component can be described with the following properties:
 -

##Calculated field definition
This specific component can be described with the following properties:
 -

##Relation field definition
This specific component can be described with the following properties:
 -

##Validation definition
This specific component can be described with the following properties:
 -

##Index definition
This specific component can be described with the following properties:
 -

##Access control definition
This specific component can be described with the following properties:
 -

##Filter Definition
This specific component can be described with the following properties:
 -
