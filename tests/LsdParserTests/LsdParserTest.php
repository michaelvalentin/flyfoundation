<?php

use FlyFoundation\LsdParser\LsdParser;
use FlyFoundation\SystemDefinitions\FieldType;
use FlyFoundation\SystemDefinitions\ValidationType;

require_once __DIR__.'/../test-init.php';

class LsdParserTest extends \PHPUnit_Framework_TestCase {

    public function testExample1()
    {
        $lsdParser = new LsdParser();
        $lsdParser->addFile(__DIR__."/test1.lsd");
        $systemDefinition = $lsdParser->getSystemDefinition();

        $res1 = $systemDefinition->getEntityDefinition("Comment")->getValidationDefinitions()[0]->getType();
        $res2 = $systemDefinition->getEntityDefinitions()[1]->getName();

        $this->assertEquals(ValidationType::Required,$res1);
        $this->assertEquals("AnotherEntity",$res2);
    }

    public function testExample2()
    {
        $lsdParser = new LsdParser();
        $lsdParser->addFile(__DIR__."/test2.lsd");
        $systemDefinition = $lsdParser->getSystemDefinition();

        $res1 = $systemDefinition->getEntityDefinition("Post")->getSettings();
        $res2 = $systemDefinition->getEntityDefinition("User")->getFieldDefinition("MyField")->getType();

        $this->assertEquals([
            "Setting" => "Value of setting",
            "OtherSetting" => "Value"
        ],$res1);
        $this->assertEquals(FieldType::String, $res2);
    }

    public function testExample1And2()
    {
        $lsdParser = new LsdParser();
        $lsdParser->addFile(__DIR__."/test1.lsd");
        $lsdParser->addFile(__DIR__."/test2.lsd");
        $systemDefinition = $lsdParser->getSystemDefinition();

        $numberOfEntities = count($systemDefinition->getEntityDefinitions());
        $this->assertEquals(4, $numberOfEntities);

        $comment = $systemDefinition->getEntityDefinition("Comment");

        $settingsInComment = $comment->getSettings();
        $this->assertEquals(["Setting" => "Value of setting"], $settingsInComment);

        $numberOfFieldsInComment = count($comment->getFieldDefinitions());
        $this->assertEquals(1,$numberOfFieldsInComment);

        $validationsInComment = $comment->getValidationDefinitions();
        $this->assertEquals(1,count($validationsInComment));

        $comment_requiredValidation = $validationsInComment[0];

        $this->assertEquals(ValidationType::Required, $comment_requiredValidation->getType());

        $settingForValidation = $comment_requiredValidation->getSetting("SettingForValidation");
        $this->assertEquals("setting",$settingForValidation);

        $comment_FieldTest = $comment->getFieldDefinition("FieldTest");

        $settingsInFieldTest = $comment_FieldTest->getSettings();
        $this->assertEmpty($settingsInFieldTest);

        $anotherEntity = $systemDefinition->getEntityDefinition("AnotherEntity");
        $settingForAnotherEntity = $anotherEntity->getSettings();
        $this->assertEquals(["Setting"=>"Test"],$settingForAnotherEntity);

        $post = $systemDefinition->getEntityDefinition("Post");
        $postSettings = $post->getSettings();
        $this->assertEquals([
            "Setting" => "Value of setting",
            "OtherSetting" => "Value"
        ],$postSettings);

        $postFields = $post->getFieldDefinitions();
        $this->assertEquals(1,count($postFields));

        $post_myField = $post->getFieldDefinition("MyField");

        $this->assertEquals(FieldType::DateTime, $post_myField->getType());

        $user = $systemDefinition->getEntityDefinition("User");
        $userSettings = $user->getSettings();
        $this->assertEquals(["Setting" => "Test"],$userSettings);

        $userFields = $user->getFieldDefinitions();
        $this->assertEquals(2, count($userFields));

        $userValidations = $user->getValidationDefinitions();
        $this->assertEquals(2, count($userValidations));
        $validationTypes = [];
        foreach($userValidations as $validation)
        {
            $validationTypes[] = $validation->getType();
            if($validation->getType() == ValidationType::GreaterThan){
                $this->assertEquals([
                    "FieldNames" => "OtherField",
                    "Value" => 5
                ],$validation->getSettings());
            }elseif($validation->getType() == ValidationType::Required){
                $this->assertEquals([
                    "MyField"
                ],$validation->getFieldNames());
            }
        }
        $this->assertEquals([
            ValidationType::GreaterThan,
            ValidationType::Required
        ],$validationTypes);

        $user_myField = $user->getFieldDefinition("MyField");
        $this->assertEquals(FieldType::String, $user_myField->getType());

        $user_otherField = $user->getFieldDefinition("OtherField");
        $this->assertEquals(FieldType::Integer, $user_otherField->getType());

    }
}
 