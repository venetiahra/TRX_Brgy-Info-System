<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/Validator.php';

class Resident
{
    private $conn;

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    private function calculateAge(?string $birthDate): int
    {
        if (empty($birthDate)) {
            return 0;
        }
        try {
            $birth = new DateTime($birthDate);
            return (int) $birth->diff(new DateTime('today'))->y;
        } catch (Exception $e) {
            return 0;
        }
    }

    private function normalize(array $data): array
    {
        $data = Validator::sanitizeArray($data);
        $payload = [
            'resident_no' => trim((string) ($data['resident_no'] ?? '')),
            'first_name' => trim((string) ($data['first_name'] ?? '')),
            'middle_name' => Validator::nullableString($data['middle_name'] ?? null),
            'last_name' => trim((string) ($data['last_name'] ?? '')),
            'suffix' => Validator::nullableString($data['suffix'] ?? null),
            'sex' => trim((string) ($data['sex'] ?? '')),
            'civil_status' => Validator::nullableString($data['civil_status'] ?? null),
            'birth_date' => trim((string) ($data['birth_date'] ?? '')),
            'address' => trim((string) ($data['address'] ?? '')),
            'contact_number' => Validator::nullableString($data['contact_number'] ?? null),
            'occupation' => Validator::nullableString($data['occupation'] ?? null),
            'citizenship' => Validator::nullableString($data['citizenship'] ?? null),
            'years_of_residency' => max(0, Validator::integer($data['years_of_residency'] ?? 0)),
            'voter_status' => Validator::nullableString($data['voter_status'] ?? null),
            'resident_status' => trim((string) ($data['resident_status'] ?? 'Active')),
        ];
        $payload['age'] = $this->calculateAge($payload['birth_date']);
        return $payload;
    }

    public function create(array $data): bool
    {
        $data = $this->normalize($data);
        $sql = 'INSERT INTO residents (resident_no, first_name, middle_name, last_name, suffix, sex, civil_status, birth_date, age, address, contact_number, occupation, citizenship, years_of_residency, voter_status, resident_status)
                VALUES (:resident_no, :first_name, :middle_name, :last_name, :suffix, :sex, :civil_status, :birth_date, :age, :address, :contact_number, :occupation, :citizenship, :years_of_residency, :voter_status, :resident_status)';
        return $this->conn->prepare($sql)->execute($data);
    }

    public function getAll(string $search = ''): array
    {
        $sql = 'SELECT * FROM residents';
        $params = [];
        if ($search !== '') {
            $sql .= ' WHERE resident_no LIKE :search OR first_name LIKE :search OR middle_name LIKE :search OR last_name LIKE :search OR address LIKE :search';
            $params['search'] = '%' . trim($search) . '%';
        }
        $sql .= ' ORDER BY last_name ASC, first_name ASC';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function search(string $term): array
    {
        return $this->getAll($term);
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->conn->prepare('SELECT * FROM residents WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function update(int $id, array $data): bool
    {
        $data = $this->normalize($data);
        $data['id'] = $id;
        $sql = 'UPDATE residents SET resident_no = :resident_no, first_name = :first_name, middle_name = :middle_name, last_name = :last_name, suffix = :suffix, sex = :sex, civil_status = :civil_status, birth_date = :birth_date, age = :age, address = :address, contact_number = :contact_number, occupation = :occupation, citizenship = :citizenship, years_of_residency = :years_of_residency, voter_status = :voter_status, resident_status = :resident_status WHERE id = :id';
        return $this->conn->prepare($sql)->execute($data);
    }

    public function delete(int $id): bool
    {
        return $this->conn->prepare('DELETE FROM residents WHERE id = :id')->execute(['id' => $id]);
    }

    public function count(): int
    {
        return (int) $this->conn->query('SELECT COUNT(*) FROM residents')->fetchColumn();
    }

    public function getDropdownOptions(): array
    {
        return $this->conn->query('SELECT id, resident_no, first_name, middle_name, last_name, suffix, resident_status FROM residents ORDER BY last_name ASC, first_name ASC')->fetchAll();
    }
}
?>
