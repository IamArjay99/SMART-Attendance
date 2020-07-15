<?php

class Role extends Database {
    public function getRole($id) {
        $sql = "SELECT * FROM roles WHERE id = ? AND deleted_at IS ?";
        $query = $this->connect()->prepare($sql);
        $query->execute([$id, NULL]);
        return $query->fetch();
    }

    public function getAllRoles() {
        $sql = "SELECT * FROM roles WHERE deleted_at IS ?";
        $query = $this->connect()->prepare($sql);
        $query->execute([NULL]);
        return $query->fetchAll();
    }
}