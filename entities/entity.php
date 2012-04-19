<?php
require_once 'db/dbconn.php';

class entity
{
	public static function query($query, $params, $fetch_style = PDO::FETCH_ASSOC)
	{
		$stmt = DBConn::get()->prepare($query);
		$stmt->execute($params);
		return $stmt->fetchAll($fetch_style);
	}
	
	public static function getBy($filters)
	{
		$table = get_called_class();
		$stmt = self::buildSelectQueryByFilters($table, $filters);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($row !== false)
		{
			return self::rowToEntity($row, $table);
		}
		else
		{
			return null;
		}
	}
	
	public static function count()
	{
		return self::countBy(array());
	}
	
	public static function countBy($filters)
	{
		$table = get_called_class();
		$stmt = self::buildSelectQueryByFilters($table, $filters, null, null, null, 'count(*)');
		return $stmt->fetch(PDO::FETCH_COLUMN);
	}
	
	public static function getById($id)
	{
		return self::getBy(array('id' => $id));
	}
	
	public static function listBy($filters, $orderBy = null, $limit = null, $offset = null)
	{
		$table = get_called_class();
		$stmt = self::buildSelectQueryByFilters($table, $filters, $orderBy, $limit, $offset);
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$entities = array();
		foreach ($rows as $row)
		{
			$entities[] = self::rowToEntity($row, $table);
		}
		return $entities;
	}
	
	public static function listAll()
	{
		return self::listBy(array());
	}
	
	public function save()
	{
		$table = get_called_class();
		$insert = false;
		$auto_id = false;
		if (!isset($this->id))
		{
			$auto_id = true;
			$insert = true;
		}
		else
		{
			$stmt = DBConn::get()->prepare("select count(*) from $table where id = ?");
			$stmt->execute(array($this->id));
			$insert = $stmt->fetchColumn(0) == 0;
		}
		if ($insert)
		{
			$fields = array();
			$placeholders = array();
			$values = array();
			foreach ($this as $property => $value)
			{
				if ($property != 'id' || !$auto_id)
				{
					$fields[] = $property;
					$placeholders[] = '?';
					$values[] = is_bool($value) ? ($value ? '1' : '0') : $value;
				}
			}
			$fields = implode(', ', $fields);
			$placeholders = implode(', ', $placeholders);
			$stmt = DBConn::get()->prepare("insert into $table ($fields) values ($placeholders)");
			$stmt->execute($values);
			if ($auto_id && property_exists($this, 'id'))
			{
				$this->id = DBConn::get()->lastInsertId("{$table}_id_seq");
			}
		}
		else
		{
			$fields = array();
			$values = array();
			foreach ($this as $property => $value)
			{
				if ($property != 'id')
				{
					$fields[] = "$property = ?";
					$values[] = is_bool($value) ? ($value ? '1' : '0') : $value;
				}
			}
			$values[] = $this->id;
			$fields = implode(', ', $fields);
			$stmt = DBConn::get()->prepare("update $table set $fields where id = ?");
			$stmt->execute($values);
		}
	}
	
	//@todo: delete
	
	protected static function buildSelectQueryByFilters($table, $filters, $orderBy = null, $limit = null, $offset = null, $fields = "*")
	{
		$query = "select $fields from $table";
		if ($filters)
		{
			$filters = self::buildFiltersQuery($filters);
			$query .= " where $filters[0]";
			$filters = $filters[1];
		}
		if ($orderBy !== null)
		{
			if (!is_array($orderBy))
			{
				$orderBy = array($orderBy);
			}
			$order = array();
			foreach ($orderBy as $orderField => $orderSense)
			{
				if (is_int($orderField))
				{
					$order[] = $orderSense;
				}
				else
				{
					$order[] = $orderField . ' ' . $orderSense;
				}
			}
			$query .= ' order by ' . implode(', ', $order);
		}
		if ($limit !== null)
		{
			$query .= " limit $limit";
		}
		if ($offset !== null)
		{
			$query .= " offset $offset";
		}
		$stmt = DBConn::get()->prepare($query);
		$stmt->execute($filters);
		return $stmt;
	}
	
	protected static function buildFiltersQuery($filters)
	{
		$filtersString = array();
		$values = array();
		foreach ($filters as $field => $value)
		{
			if (is_array($value))
			{
				$oper = $value[0];
				$values[] = is_bool($value[1]) ? ($value[1] ? '1' : '0') : $value[1];
			}
			else
			{
				$oper = '=';
				$values[] = is_bool($value) ? ($value ? '1' : '0') : $value;
			}
			$filtersString[] = "$field $oper ?";
		}
		return array(implode(' and ', $filtersString), $values);
	}
	
	protected static function rowToEntity($row, $entityType)
	{
		$entity = new $entityType();
		foreach ($row as $field => $value)
		{
			$entity->$field = $value;
		}
		return $entity;
	}
}
?>