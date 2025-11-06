<?php
  require "config.php";

  try {
    $connection = new PDO("mysql:host=$host", $username, $password, $options);
    $sql = file_get_contents("data/init.sql");
    $connection->exec($sql);

    echo "Database and table demo created successfully";
  } catch(PDOEXCEPTION $error) {
    echo $sql . "<br>" . $error->getMessage();
  }