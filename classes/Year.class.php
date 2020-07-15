<?php

class Year extends Database {
    public function getYear($id) {
        $sql = "SELECT * FROM years WHERE id = ? AND deleted_at IS ?";
        $query = $this->connect()->prepare($sql);
        $query->execute([$id, NULL]);
        return $query->fetch();
    }

    public function getAllYears() {
        $sql = "SELECT * FROM years WHERE deleted_at IS ?";
        $query = $this->connect()->prepare($sql);
        $query->execute([NULL]);
        return $query->fetchAll();
    }
}