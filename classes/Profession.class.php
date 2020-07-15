<?php

class Profession extends Database {
    public function getProfession($id) {
        $sql = "SELECT * FROM professions WHERE id = ? AND deleted_at IS ?";
        $query = $this->connect()->prepare($sql);
        $query->execute([$id, NULL]);
        return $query->fetch();
    }

    public function getAllProfessions() {
        $sql = "SELECT * FROM professions WHERE deleted_at IS ?";
        $query = $this->connect()->prepare($sql);
        $query->execute([NULL]);
        return $query->fetchAll();
    }
}