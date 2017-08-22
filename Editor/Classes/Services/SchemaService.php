<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class SchemaService {

  static function buildSqlSetters($obj, $schema, $exclude = []) {
    $sql = '';
    $fields = $schema;
    if (isset($schema['properties'])) {
      $fields = $schema['properties'];
    }
    if (!is_array($fields)) {
      Log::debug('No fields found...');
      Log::debug($schema);
    }
    foreach ($fields as $field => $info) {
      $column = SchemaService::getColumn($field, $info);
      if (in_array($column,$exclude)) {
        continue;
      }
      if (strlen($sql) > 0) {
        $sql .= ',';
      }
      $sql .= "`" . $column . "`=";
      $getter = "get" . ucfirst($field);
      if (!method_exists($obj,$getter)) {
        Log::warn($getter . ' does not exist');
      }
      $value = $obj->$getter();
      $sql .= SchemaService::_formatValue($info['type'], $value);
    }
    return $sql;
  }

  static function _formatValue($type, $value) {
    if ($type == 'int') {
      return Database::int($value);
    } else if ($type == 'float') {
      return Database::float($value);
    } else if ($type == 'boolean') {
      return Database::boolean($value);
    } else if ($type == 'datetime') {
      return Database::datetime($value);
    }
    return Database::text($value);
  }

  static function getRowValue($type, $value) {
    if ($type == 'int') {
      return intval($value);
    } else if ($type == 'float') {
      return floatval($row[$column]);
    } else if ($type == 'datetime') {
      return $value ? intval($value) : null;
    } else if ($type == 'boolean') {
      return $value == 1 ? true : false;
    }
    return $value;
  }

  static function getColumn($property, $info) {
    if (isset($info['column'])) {
      return $info['column'];
    }
    return $property;
  }

  static function buildSqlColumns($schema, $exclude = []) {
    $sql = '';
    foreach ($schema['properties'] as $field => $info) {
      $column = $field;
      if (isset($info['column'])) {
        $column = $info['column'];
      }
      if (in_array($column, $exclude)) {
        continue;
      }
      if (strlen($sql) > 0) {
        $sql .= ',';
      }
      $sql .= '`' . $column . '`';
    }
    return $sql;
  }

  static function buildSqlValues($obj, $schema, $exclude = []) {
    $sql = '';
    foreach ($schema['properties'] as $field => $info) {
      $column = $field;
      if (isset($info['column'])) {
        $column = $info['column'];
      }
      if (in_array($column, $exclude)) {
        continue;
      }
      if (strlen($sql) > 0) {
        $sql .= ',';
      }
      $getter = "get" . ucfirst($field);
      if (!method_exists($obj, $getter)) {
        Log::warn($getter . ' does not exist');
      }
      $value = $obj->$getter();
      $sql .= SchemaService::_formatValue($info['type'], $value);
    }
    return $sql;
  }

  static function getDatabaseSchema() {
    $schema = ['tables' => []];

    $tables = DatabaseUtil::getTables();

    foreach ($tables as $table) {
      $tableInfo = [];
      $tableInfo['name'] = $table;
      $columns = DatabaseUtil::getTableColumns($table);
      $columnsInfo = [];
      foreach ($columns as $column) {
        $columnsInfo[$column['Field']] = [
          'type' => $column['Type'],
          'collation' => $column['Collation'],
          'null' => $column['Null'],
          'default' => $column['Default'],
          'key' => $column['Key'],
          'extra' => $column['Extra'],
        ];
      }

      $tableInfo['columns'] = $columnsInfo;
      $schema['tables'][] = $tableInfo;

    }

    return $schema;
  }
}