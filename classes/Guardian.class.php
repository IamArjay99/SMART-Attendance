<?php

class Guardian extends Database {
    public function saveStudentGuardianInformation($student_id, $guardian_name, $guardian_number, $guardian_relationship, $guardian_name2, $guardian_number2, $guardian_relationship2) {
        $sql = "INSERT INTO guardians
                (student_id, name, contact, relationship, name2, contact2, relationship2)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $query = $this->connect()->prepare($sql);
        $query->execute([$student_id, $guardian_name, $guardian_number, $guardian_relationship, $guardian_name2, $guardian_number2, $guardian_relationship2]);
        return $query;
    }
}