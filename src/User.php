<?php

	class User
	{
		private $user_name;
		private $password;
		private $longitude;
		private $latitude;
		private $signed_in;
		private $id;

		function __construct($user_name, $password, $longitude, $latitude, $signed_in, $id = null)
		{
			$this->user_name = $user_name;
			$passwordHash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
			$this->password = $passwordHash;
			$this->longitude = $longitude;
			$this->latitude = $latitude;
			$this->signed_in = $signed_in;
			$this->id = $id;
		}

		function setUserName($new_user_name)
		{
			$this->user_name = $new_user_name;
		}

		function getUserName()
		{
			return $this->user_name;
		}

		function setPassword($new_password)
		{
			$this->password = $new_password;
		}

		function getPassword()
		{
			return $this->password;
		}

		function setLongitude($new_longitude)
		{
			$this->longitude = $new_longitude;
		}

		function getLongitude()
		{
			return $this->longitude;
		}

		function setLatitude($new_latitude)
		{
			$this->latitude = $new_latitude;
		}

		function getLatitude()
		{
			return $this->latitude;
		}

		function setSignedIn($new_signed_in)
		{
			$this->signed_in = $new_signed_in;
		}

		function getSignedIn()
		{
			return $this->signed_in;
		}

		function setId($new_id)
		{
			$this->id = $new_id;
		}

		function getId()
		{
			return $this->id;
		}

		function save()
		{
			$GLOBALS['DB']->exec("INSERT INTO users (user_name, password, longitude, latitude, signed_in)
				VALUES ('{$this->getUserName()}',
						'{$this->getPassword()}',
						{$this->getLongitude()},
						{$this->getLatitude()},
						{$this->getSignedIn()});");
			$this->setId($GLOBALS['DB']->lastInsertId());
		}

		function updateLocation($new_longitude, $new_latitude)
		{
			$GLOBALS['DB']->exec("UPDATE users SET longitude = {$new_longitude} WHERE id = {$this->getId()};");
			$GLOBALS['DB']->exec("UPDATE users SET latitude = {$new_latitude} WHERE id = {$this->getId()};");
			$this->setLongitude($new_longitude);
			$this->setLatitude($new_latitude);
		}

		function updatePassword($new_password)
		{
			$GLOBALS['DB']->exec("UPDATE users SET password = '{$new_password}' WHERE id = {$this->getId()};");
			$this->setPassword($new_password);
		}

		function updateUserName($new_user_name)
		{
			$GLOBALS['DB']->exec("UPDATE users SET user_name = '{$new_user_name}' WHERE id = {$this->getId()};");
			$this->setUserName($new_user);
		}

		function updateSignedIn($new_signed_in)
		{
			$GLOBALS['DB']->exec("UPDATE users SET signed_in = {$new_signed_in} WHERE id = {$this->getId()};");
			$this->setSignedIn($new_signed_in);
		}

		function update($new_user_name, $new_password, $new_longitude, $new_latitude, $new_signed_in)
		{
			$this->updateUserName($new_user_name);
			$this->updatePassword($new_password);
			$this->updateLocation($new_longitude, $new_latitude);
			$this->update($new_signed_in);
		}

		function delete()
		{
			$GLOBALS['DB']->exec("DELETE FROM users WHERE id = {$this->getId()};");
		}

		static function getAll()
		{
			$returned_users = $GLOBALS['DB']->query("SELECT * FROM users;");
			$users = array();
			foreach($returned_users as $user)
			{
				$new_user_name = $user['user_name'];
				$new_password = $user['password'];
				$new_longitude = (float) $user['longitude'];
				$new_latitude = (float) $user['latitude'];
				$new_signed_in = (int) $user['signed_in'];
				$new_id = $user['id'];

				$new_user = new User($new_user_name, $new_password, $new_longitude, $new_latitude, $new_signed_in, $new_id);
				$new_user->setPassword($new_password);
				array_push($users, $new_user);
			}
			return $users;
		}

		static function deleteAll()
		{
			$GLOBALS['DB']->exec("DELETE FROM users;");
		}

		static function find($search_id)
		{
			$found_user = null;
			$users = User::getAll();
			foreach($users as $user) {
				$user_id = $user->getId();
				if($user_id == $search_id) {
					$found_user = $user;
				}
			}
			return $found_user;
		}
		static function findByUserName($user_name)
		{
			$query = $GLOBALS['DB']->query("SELECT * FROM users WHERE user_name = '{$user_name}';");

			foreach($query as $user) {
				$found_user_name = $user['user_name'];
				$found_password = $user['password'];
				$found_lng = (float) $user['longitude'];
				$found_lat = (float) $user['latitude'];
				$found_signedin = (int) $user['signed_in'];
				$found_id = $user['id'];
				$found_user = new User($found_user_name, $found_password, $found_lng, $found_lat, $found_signedin, $found_id);
				$found_user->setPassword($found_password);
			}
			return $found_user;
		}

		function distanceBetweenUsers($user_two)
		{
			$radius_of_earth = 6371000; //in meters
			$user_one_lat = deg2rad($this->getLatitude());
			$user_one_lng = deg2rad($this->getLongitude());
			$user_two_lat = deg2rad($user_two->getLatitude());
			$user_two_lng = deg2rad($user_two->getLongitude());

			$difference_lat = $user_two_lat - $user_one_lat;
			$difference_lng = $user_two_lng - $user_one_lng;

			$a = (sin($difference_lat/2) * sin($difference_lat/2)) + (cos($user_one_lat) * cos($user_two_lat) * (sin($difference_lng/2) * sin($difference_lng/2)));
			$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
			$distance_between_two_points = $radius_of_earth * $c;

			return $distance_between_two_points;
		}

		function findUsersNear()
		{
			$users = User::getAll();
			$users_near = array();

			foreach($users as $user) {
				if($user->getId() != $this->getId()) {
					$distance = $this->distanceBetweenUsers($user);
					$user_online = $user->getSignedIn();
					if(($distance <= 5000) && ($user_online == true)) {
						array_push($users_near, $user);
					}
				}
			}
			return $users_near;
		}

		static function logIn($user_name, $user_password)
		{
			$user = User::findByUserName($user_name);
			if(password_verify($user_password, $user->getPassword()) === false) {
				return "Wrong Password";
			} else {
				$user->updateSignedIn(1);
				return $user;
			}
		}
		
		function logOut()
		{
			$this->updateSignedIn(0);
		}
	}
?>