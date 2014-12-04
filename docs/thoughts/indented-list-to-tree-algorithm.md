#Indented lines to tree algorithm
This provides a description of the algorithm, along with pseudocode.

##Problem description
Assuming we have a construct Node, which can contain data and have zero or more
children, which are also Nodes. Based on a text input, we want to build trees
of nodes, where the data of each node is the content from a line, and the
position of the node in tree is indicated by the line position and indentation,
so that a line which is indented is the child of the first previous line, which
is less indented.

##Algorithm description
Assuming we have a list of lines, define a function which:

- If the input list has at least two lines:
    * Removes the first line from the list
    * Removes all lines from the list which satisfy all:
        - Has a higher indent than the first line
        - Occurs before the next line with indent less than or equal to the first line
    * Passes these lines recursively to the function, and sets the result as children of the first line
    * If it has remaining lines, passes these recursively to the function, and combines them with the first line, as the result
    * If there are no more remaining lines, returns a list with the first line as the single element
- If the input list has one line:
    * Sets the children of that one line to an empty list
    * Returns the list
- If the input list has no elements
    * Returns an empty list
- Remove the first line from the list

Calling the function with a list of lines, will result in a list of trees,
based on their indent. If the tree has only one root, the resulting tree will
be the first element of the result list.

##Pseudocode

    List<Node> LinesToTree( List<Line> lines )
    {
        if(lines.count >= 2)
        {
            firstLine = lines.shift
            nextLine = lines[0]
            children = List<Line>

            while(nextLine != null && firstLine.indent < nextLine.indent)
            {
                children.add(lines.shift)
                nextLine = lines[0]
            }

            firstLineNode = new Node
            firstLineNode.data = firstLine.data
            firstLineNode.children = LinesToTree(children)

            resultNodes = new List<Node>
            resultNodes.add(firstLineNode)

            if(lines.count > 0)
            {
                siblingNodes = LinesToTree(lines)
                resultNodes.addAll(siblingNodes)
                return resultNodes
            }
            else
            {
                return resultNodes
            }
        }
        elseif()
        {
            nodes = new List<Node>
            node = new Node
            node.data = lines[0].data
            node.children = new List<Node>
            return nodes
        }
        else
        {
            return new List<Node>
        }
    }
