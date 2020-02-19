<?php

/**
 * Simple PHP-SQL library 
 * 
 * @version 0.1
 * 
 * @author Tigran Avagyan <tigranav18/gmail.com>
 * 
 * @package PHP-SQL
 * 
 */


/**
 * Abstract class for connections & modifying databases
 */

abstract class SQL
{
  abstract public function Connect();
  abstract public function Insert($table_name, $fields);
  abstract public function Update();
  abstract public function Delete();
  abstract public function Select($table_name, $selection, $clause);
}


/**
 * Class for MySql database
 */

class MySql extends SQL
{

  private $host;
  private $login;
  private $password;
  private $db;
  private $connection;

  /**
   * Constructor 
   * 
   * @param mixed $host
   * 
   * The host adress for connecting to the server
   * 
   * @param mixed $login
   * 
   * The login from the server
   * 
   * @param mixed $password
   * 
   * The password from the server
   * 
   * @param mixed $db
   * 
   * The database to connect to
   */

  public function __construct($host, $login, $password, $db)
  {
    $this->host     = $host;
    $this->login    = $login;
    $this->password = $password;
    $this->db       = $db;
  }

  /**
   * Connects to the server
   * 
   * @return $connection
   */

  public function Connect()
  {
    $this->connection = new mysqli($this->host, $this->login, $this->password, $this->db, $port = 3308);
    if ($this->connection->connect_error) {
      die("Connection failed: " . $this->connection->connect_error);
    }
  }

  /**
   * Inserts data to table
   * 
   * @param mixed $table_name 
   * 
   * Table name
   * 
   * @param assoc-array $fields
   * 
   * Fields and data to send to database
   */

  public function Insert($table_name, $fields)
  {

    $field_names = '';
    $field_data = '';

    foreach ($fields as $key => $value) {
      $field_names .= $key . ',';
      $field_data .= "'" . $value . "'" . ',';
    }

    $field_names = rtrim($field_names, ',');
    $field_data = rtrim($field_data, ',');
    $sql = "INSERT INTO $table_name($field_names) VALUES($field_data)";

    if ($this->connection->query($sql) === TRUE) {
      echo "New record created successfully";
    } else {
      echo  $this->connection->error . "<br>";
    }
  }
  public function Update()
  {
  }
  public function Delete()
  {
  }

  /**
   * Selects data from table
   * 
   * @param mixed $table_name
   * 
   * Table name
   * 
   * @param array $selection
   * 
   * By default - false , if want true , insert array
   * 
   * @param assoc-array $clause
   * 
   * Enables clauses if inserts assoc-array , by default false
   * 
   * @return data
   */

  public function Select($table_name, $selection = false, $clause = false)
  {
    if ($selection) {
      $selections = '';
      foreach ($selection as $key) {
        $selections .= $key . ',';
      }
      $selections = rtrim($selections, ',');
      if ($clause) {
        $clauses = '';
        foreach ($clause as $key => $value) {
          $key = strtoupper($key);
          if (!strcasecmp($key, "where")) {
            $clauses .= $key . ' ';
            foreach ($value as $keyw => $valw) {
              $clauses .= $keyw . '=' . "'" . $valw . "' ";
            }
          }
          else{
            $clauses .= $key . ' ' . $value.' ';
          }
        }
        $sql = "SELECT $selections FROM $table_name $clauses";
        var_dump($sql);
        $result = $this->connection->query($sql);
      } else {
        $sql = "SELECT $selections FROM $table_name";
        $result = $this->connection->query($sql);
      }
    } else {
      $sql = "SELECT * FROM $table_name";
      $result = $this->connection->query($sql);
    }
    return $result;
  }
}
