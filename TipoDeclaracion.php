<?php
//TipoDeclaracion.php
class TipoDeclaracion {
    private $conn;
    private $table_name = "tbl_tipodeclaracion";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Leer Tipo de declaracion
    public function read($id = null) {
        if ($id) {
            $stmt = $this->conn->prepare("SELECT * FROM $this->table_name WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $stmt = $this->conn->query("SELECT * FROM $this->table_name");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    // Crear Tipo de declaracion
    public function create($data) {
        $keys = array_keys($data);
        $fields = implode(", ", $keys);
        $placeholders = ":" . implode(", :", $keys);

        $sql = "INSERT INTO $this->table_name ($fields) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        return $stmt->execute();
    }

    // Actualizar Tipo de declaracion
    public function update($data) {
        $id = $data['id'];
        unset($data['id']);

        $setters = array_map(function ($key) {
            return "$key=:$key";
        }, array_keys($data));

        $sql = "UPDATE $this->table_name SET " . implode(', ', $setters) . " WHERE id=:id";
        $stmt = $this->conn->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->bindValue(':id', $id);

        return $stmt->execute();
    }

    // Eliminar Tipo de declaracion
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM $this->table_name WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
?>
