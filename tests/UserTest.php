<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Place.php";
    require_once "src/User.php";

    $server = 'mysql:host=localhost;dbname=face_to_face_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class UserTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            User::deleteAll();
            Place::deleteAll();
        }

        function test_setUserName()
        {
            //Arrange
            $user_name = "Nathan";
            $password = "xxx60606";
            $longitude = 45.516231;
            $latitude = -122.682519;
            $signed_in = true;
            $id = 1;
            $test_user = new User($user_name, $password, $longitude, $latitude, $signed_in, $id);

            //Act
            $test_user->setUserName($user_name);
            $result = $test_user->getUserName();

            //Assert
            $this->assertEquals($user_name, $result);

        }

        function test_getUserName()
        {
            //Arrange
            $user_name = "Nathan";
            $password = "xxx60606";
            $longitude = 45.516231;
            $latitude = -122.682519;
            $signed_in = true;
            $id = 1;
            $test_user = new User($user_name, $password, $longitude, $latitude, $signed_in, $id);

            //Act
            $result = $test_user->getUserName();

            //Assert
            $this->assertEquals("Nathan", $result);
        }

        function test_setLongitude()
        {
            //Arrange
            $user_name = "Nathan";
            $password = "xxx60606";
            $longitude = 45.516231;
            $latitude = -122.682519;
            $signed_in = true;
            $id = 1;
            $test_user = new User($user_name, $password, $longitude, $latitude, $signed_in, $id);

            //Act
            $result = $test_user->getLongitude();

            //Assert
            $this->assertEquals($longitude, $result);
        }

        function test_getLongitude()
        {
            //Arrange
            $user_name = "Nathan";
            $password = "xxx60606";
            $longitude = 45.516231;
            $latitude = -122.682519;
            $signed_in = true;
            $id = 1;
            $test_user = new User($user_name, $password, $longitude, $latitude, $signed_in, $id);

            //Act
            $result = $test_user->getLongitude();

            //Assert
            $this->assertEquals(45.516231, $result);
        }

        function test_setLatitude()
        {
            //Arrange
            $user_name = "Nathan";
            $password = "xxx60606";
            $longitude = 45.516231;
            $latitude = -122.682519;
            $signed_in = true;
            $id = 1;
            $test_user = new User($user_name, $password, $longitude, $latitude, $signed_in, $id);

            //Act
            $result = $test_user->getLatitude();

            //Assert
            $this->assertEquals(-122.682519, $result);
        }

        function test_getLatitude()
        {
            //Arrange
            $user_name = "Nathan";
            $password = "xxx60606";
            $longitude = 45.516231;
            $latitude = -122.682519;
            $signed_in = true;
            $id = 1;
            $test_user = new User($user_name, $password, $longitude, $latitude, $signed_in, $id);

            //Act
            $result = $test_user->getLatitude();

            //Assert
            $this->assertEquals(-122.682519, $result);
        }

        function test_setSignedIn()
        {
            //Arrange
            $user_name = "Nathan";
            $password = "xxx60606";
            $longitude = 45.516231;
            $latitude = -122.682519;
            $signed_in = true;
            $id = 1;
            $test_user = new User($user_name, $password, $longitude, $latitude, $signed_in, $id);

            //Act
            $result = $test_user->getSignedIn();

            //Assert
            $this->assertEquals(true, $result);
        }

        function test_getSignedIn()
        {
            //Arrange
            $user_name = "Nathan";
            $password = "xxx60606";
            $longitude = 45.516231;
            $latitude = -122.682519;
            $signed_in = true;
            $id = 1;
            $test_user = new User($user_name, $password, $longitude, $latitude, $signed_in, $id);

            //Act
            $result = $test_user->getSignedIn();

            //Assert
            $this->assertEquals(true, $result);
        }

        function test_getId()
        {
            //Arrange
            $user_name = "Nathan";
            $password = "xxx60606";
            $longitude = 45.516231;
            $latitude = -122.682519;
            $signed_in = 1;
            $id = 1;
            $test_user = new User($user_name, $password, $longitude, $latitude, $signed_in, $id);

            //Act
            $result = $test_user->getId();

            //Assert
            $this->assertEquals($id, $result);
        }

        function test_save()
        {
            //Arrange
            $user_name = "Nathan";
            $password = "xxx60606";
            $longitude = 45.516231;
            $latitude = -122.682519;
            $signed_in = 1;
            $id = 1;
            $test_user = new User($user_name, $password, $longitude, $latitude, $signed_in, $id);
            $test_user->save();

            //Act
            $result = User::getAll();

            //Assert
            $this->assertEquals($test_user, $result[0]);
        }

        function test_LogIn()
        {
            //Arrange
            $user_name = "Nathan";
            $password = "xxx60606";
            $longitude = 45.516231;
            $latitude = -122.682519;
            $signed_in = 1;
            $id = 1;
            $test_user = new User($user_name, $password, $longitude, $latitude, $signed_in, $id);
            $test_user->save();

            //Act
            $result = User::LogIn("Nathan", "xxx60606");

            //Assert
            $this->assertEquals($test_user, $result);
        }
        
        function testFindNear()
        {
            //Arrange
            $user_name = "Nathan";
            $password = "xxx60606";
            $latitude = 45.520969;
            $longitude = -122.679953;
            $signed_in = 1;
            $id = 1;
            $test_user = new User($user_name, $password, $longitude, $latitude, $signed_in, $id);
            $test_user->save();
            
            $user_name2 = "John";
            $password2 = "xxx";
            $latitude2 = 45.515852;
            $longitude2 = -122.674644;
            $signed_in2 = 1;
            $id2 = 1;
            $test_user2 = new User($user_name2, $password2, $longitude2, $latitude2, $signed_in2, $id2);
            $test_user2->save();
            
            $user_name3 = "Jim";
            $password3 = "xxxxxx";
            $latitude3 = 47.603734;
            $longitude3 = -122.333813;
            $signed_in3 = 1;
            $id3 = 1;
            $test_user3 = new User($user_name3, $password3, $longitude3, $latitude3, $signed_in3, $id3);
            $test_user3->save();

            //Act
            $result = $test_user->findUsersNear();

            //Assert
            $this->assertEquals([$test_user2], $result);
        }

    }

    ?>
