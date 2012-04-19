<?php
class DBConn extends PDO
{
	protected static $conn;
	
	/**
	 * @return DBConn
	 */
	public static function get()
	{
		if (!isset(self::$conn))
		{
			self::$conn = new DBConn('pgsql:dbname=' . conf::$DBName . ';host=' . conf::$DBHost .';port=' . conf::$DBPort, conf::$DBUser, conf::$DBPass);
			self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		return self::$conn;
	}
	
	public function prepare($statement, $driver_options = array())
	{
		//echo $statement . '<br/>';
		return parent::prepare($statement, $driver_options);
	}
}
?>