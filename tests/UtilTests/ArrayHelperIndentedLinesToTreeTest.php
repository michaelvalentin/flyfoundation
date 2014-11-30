<?php

require_once __DIR__ . '/../test-init.php';

class ArrayHelperIndentedLinesToTreeTest extends PHPUnit_Framework_TestCase {

    public function testSimpleExample()
    {
        $input = [
            [
                "name" => "A",
                "indent" => 0
            ],
            [
                "name" => "B",
                "indent" => 2
            ],
            [
                "name" => "C",
                "indent" => 2
            ],
            [
                "name" => "D",
                "indent" => 4
            ],
            [
                "name" => "E",
                "indent" => 2
            ]
        ];
        $expectedOutput = [
            [
                "name" => "A",
                "indent" => 0,
                "children" => [
                    [
                        "name" => "B",
                        "indent" => 2,
                        "children" => []
                    ],
                    [
                        "name" => "C",
                        "indent" => 2,
                        "children" => [
                            [
                                "name" => "D",
                                "indent" => 4,
                                "children" => []
                            ]
                        ]
                    ],
                    [
                        "name" => "E",
                        "indent" => 2,
                        "children" => []
                    ],
                ]
            ]
        ];

        $output = \FlyFoundation\Util\ArrayHelper::IndentedLinesToTreeArray($input);
        $this->assertEquals($expectedOutput,$output);
    }


    public function testSimpleComplex()
    {
        $input = [
            [
                "name" => "A",
                "indent" => 0
            ],
            [
                "name" => "B",
                "indent" => 2
            ],
            [
                "name" => "C",
                "indent" => 2
            ],
            [
                "name" => "D",
                "indent" => 4
            ],
            [
                "name" => "E",
                "indent" => 6
            ],
            [
                "name" => "F",
                "indent" => 6
            ],
            [
                "name" => "G",
                "indent" => 2
            ],
            [
                "name" => "H",
                "indent" => 0
            ],
            [
                "name" => "I",
                "indent" => 4
            ],
            [
                "name" => "J",
                "indent" => 6
            ],
            [
                "name" => "K",
                "indent" => 0
            ]
        ];
        $expectedOutput = [
            [
                "name" => "A",
                "indent" => 0,
                "children" => [
                    [
                        "name" => "B",
                        "indent" => 2,
                        "children" => []
                    ],
                    [
                        "name" => "C",
                        "indent" => 2,
                        "children" => [
                            [
                                "name" => "D",
                                "indent" => 4,
                                "children" => [
                                    [
                                        "name" => "E",
                                        "indent" => 6,
                                        "children" => []
                                    ],
                                    [
                                        "name" => "F",
                                        "indent" => 6,
                                        "children" => []
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        "name" => "G",
                        "indent" => 2,
                        "children" => []
                    ],
                ]
            ],
            [
                "name" => "H",
                "indent" => 0,
                "children" => [
                    [
                        "name" => "I",
                        "indent" => 4,
                        "children" => [
                            [
                                "name" => "J",
                                "indent" => 6,
                                "children" => [
                                ]
                            ]
                        ]
                    ]
                ]
            ],

            [
                "name" => "K",
                "indent" => 0,
                "children" => []
            ],
        ];

        $output = \FlyFoundation\Util\ArrayHelper::IndentedLinesToTreeArray($input);
        $this->assertEquals($expectedOutput,$output);
    }
}
 