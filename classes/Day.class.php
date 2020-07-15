<?php

class Day extends Database {
    public function getAllDays() {
        $sql = "SELECT * FROM days WHERE deleted_at IS ?";
        $query = $this->connect()->prepare($sql);
        $query->execute([NULL]);
        return $query->fetchAll();
    }

    public function getDay($id) {
        $sql = "SELECT * FROM days WHERE id = ? AND deleted_at IS ?";
        $query = $this->connect()->prepare($sql);
        $query->execute([$id, NULL]);
        return $query->fetch();
    }
}