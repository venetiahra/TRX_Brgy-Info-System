<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/Validator.php';

class Blotter
{
    private PDO $conn;

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    private function generateControlNo(): string
    {
        $count = (int) $this->conn
            ->query("SELECT COUNT(*) FROM blotter_reports WHERE YEAR(created_at)=YEAR(CURDATE())")
            ->fetchColumn() + 1;

        return 'BLT-' . date('Y') . '-' . str_pad((string)$count, 4, '0', STR_PAD_LEFT);
    }

    public function create(array $data): int
    {
        $data = Validator::sanitizeArray($data);

        $controlNo = trim((string)($data['control_no'] ?? ''));
        if ($controlNo === '') {
            $controlNo = $this->generateControlNo();
        }

        $stmt = $this->conn->prepare("
            INSERT INTO blotter_reports (
                control_no,
                complainant_name,
                respondent_name,
                contact_number,
                incident_date,
                incident_time,
                incident_location,
                complaint_details,
                submitted_via,
                status,
                schedule_date,
                remarks
            ) VALUES (
                :control_no,
                :complainant_name,
                :respondent_name,
                :contact_number,
                :incident_date,
                :incident_time,
                :incident_location,
                :complaint_details,
                :submitted_via,
                :status,
                :schedule_date,
                :remarks
            )
        ");

        $stmt->execute([
            'control_no'        => $controlNo,
            'complainant_name'  => trim((string)($data['complainant_name'] ?? '')),
            'respondent_name'   => trim((string)($data['respondent_name'] ?? '')),
            'contact_number'    => Validator::nullableString($data['contact_number'] ?? null),
            'incident_date'     => trim((string)($data['incident_date'] ?? date('Y-m-d'))),
            'incident_time'     => Validator::nullableString($data['incident_time'] ?? null),
            'incident_location' => trim((string)($data['incident_location'] ?? '')),
            'complaint_details' => trim((string)($data['complaint_details'] ?? '')),
            'submitted_via'     => trim((string)($data['submitted_via'] ?? 'Client Portal')),
            'status'            => trim((string)($data['status'] ?? 'Pending Review')),
            'schedule_date'     => Validator::nullableString($data['schedule_date'] ?? null),
            'remarks'           => Validator::nullableString($data['remarks'] ?? null),
        ]);

        return (int)$this->conn->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $data = Validator::sanitizeArray($data);

        $stmt = $this->conn->prepare("
            UPDATE blotter_reports
            SET
                complainant_name = :complainant_name,
                respondent_name = :respondent_name,
                contact_number = :contact_number,
                incident_date = :incident_date,
                incident_time = :incident_time,
                incident_location = :incident_location,
                complaint_details = :complaint_details,
                status = :status,
                schedule_date = :schedule_date,
                remarks = :remarks
            WHERE id = :id
        ");

        return $stmt->execute([
            'id'                => $id,
            'complainant_name'  => trim((string)($data['complainant_name'] ?? '')),
            'respondent_name'   => trim((string)($data['respondent_name'] ?? '')),
            'contact_number'    => Validator::nullableString($data['contact_number'] ?? null),
            'incident_date'     => trim((string)($data['incident_date'] ?? date('Y-m-d'))),
            'incident_time'     => Validator::nullableString($data['incident_time'] ?? null),
            'incident_location' => trim((string)($data['incident_location'] ?? '')),
            'complaint_details' => trim((string)($data['complaint_details'] ?? '')),
            'status'            => trim((string)($data['status'] ?? 'Pending Review')),
            'schedule_date'     => Validator::nullableString($data['schedule_date'] ?? null),
            'remarks'           => Validator::nullableString($data['remarks'] ?? null),
        ]);
    }

    /* NEW: quick status update only */
    public function updateStatus(int $id, string $status): bool
    {
        $allowed = ['Pending', 'Complete'];

        if (!in_array($status, $allowed, true)) {
            return false;
        }

        $stmt = $this->conn->prepare("
            UPDATE blotter_reports
            SET status = :status
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'status' => $status
        ]);
    }

    public function all(string $search = ''): array
    {
        $sql = "SELECT * FROM blotter_reports";
        $params = [];

        if ($search !== '') {
            $sql .= " WHERE control_no LIKE :search
                      OR complainant_name LIKE :search
                      OR respondent_name LIKE :search
                      OR incident_location LIKE :search
                      OR status LIKE :search";
            $params['search'] = '%' . trim($search) . '%';
        }

        $sql .= " ORDER BY created_at DESC, id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function get(int $id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM blotter_reports WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function byControl(string $controlNo): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM blotter_reports WHERE control_no = :control_no LIMIT 1");
        $stmt->execute(['control_no' => trim($controlNo)]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function count(): int
    {
        return (int)$this->conn->query("SELECT COUNT(*) FROM blotter_reports")->fetchColumn();
    }
}
?>