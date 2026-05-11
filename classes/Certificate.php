<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/Validator.php';

class Certificate
{
    private $conn;

    private $types = [
        'Barangay Clearance', 'Barangay Certification', 'Certificate of Residency', 'Certificate of Indigency',
        'Certificate of Low Income', 'Certificate of Good Moral / Good Standing', 'Business Clearance',
        'Certificate of First Time Jobseeker', 'Certificate of Solo Parent', 'Certificate of Unemployment',
        'Certificate of Non-Residency', 'Certificate of Transfer', 'Certificate of Appearance',
        'Certificate of Family Membership', 'Lot Certification', 'Certificate for School Requirement',
        'Certificate for Scholarship', 'Certificate for Medical Assistance', 'Certificate for Hospital Assistance',
        'Certificate for Financial Assistance', 'Certificate for Burial Assistance', 'Certificate for Legal Purpose',
        'Certificate of Attestation', 'Death-Related Barangay Certification', 'Other Custom Certification'
    ];

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public function getCertificateTypes(): array
    {
        return $this->types;
    }

    public function countRequests(): int
    {
        return (int) $this->conn->query('SELECT COUNT(*) FROM certificate_requests')->fetchColumn();
    }

    private function generateControlNo(): string
    {
        $count = (int) $this->conn
            ->query('SELECT COUNT(*) FROM certificate_requests WHERE YEAR(created_at) = YEAR(CURDATE())')
            ->fetchColumn() + 1;

        return 'TRX-' . date('Y') . '-' . str_pad((string) $count, 4, '0', STR_PAD_LEFT);
    }

    public function createRequest(array $data): int
    {
        $data = Validator::sanitizeArray($data);

        $controlNo = trim((string) ($data['control_no'] ?? ''));
        if ($controlNo === '') {
            $controlNo = $this->generateControlNo();
        }

        $stmt = $this->conn->prepare(
            'INSERT INTO certificate_requests
                (resident_id, certificate_type, purpose, control_no, or_no, date_issued, issued_by, remarks)
             VALUES
                (:resident_id, :certificate_type, :purpose, :control_no, :or_no, :date_issued, :issued_by, :remarks)'
        );

        $stmt->execute([
            'resident_id'      => (int) ($data['resident_id'] ?? 0),
            'certificate_type' => trim((string) ($data['certificate_type'] ?? '')),
            'purpose'          => trim((string) ($data['purpose'] ?? '')),
            'control_no'       => $controlNo,
            'or_no'            => Validator::nullableString($data['or_no'] ?? null),
            'date_issued'      => trim((string) ($data['date_issued'] ?? date('Y-m-d'))),
            'issued_by'        => trim((string) ($data['issued_by'] ?? 'Barangay Staff')),
            'remarks'          => Validator::nullableString($data['remarks'] ?? null),
        ]);

        return (int) $this->conn->lastInsertId();
    }

    public function getAllRequests(string $search = ''): array
    {
        $sql = 'SELECT cr.*, r.resident_no, r.first_name, r.middle_name, r.last_name, r.suffix
                FROM certificate_requests cr
                INNER JOIN residents r ON r.id = cr.resident_id';

        $params = [];

        if ($search !== '') {
            $sql .= ' WHERE
                        cr.certificate_type LIKE :search_type
                        OR cr.purpose LIKE :search_purpose
                        OR cr.issued_by LIKE :search_issued_by
                        OR r.first_name LIKE :search_first_name
                        OR r.middle_name LIKE :search_middle_name
                        OR r.last_name LIKE :search_last_name
                        OR cr.control_no LIKE :search_control_no';

            $wild = '%' . trim($search) . '%';
            $params = [
                'search_type'        => $wild,
                'search_purpose'     => $wild,
                'search_issued_by'   => $wild,
                'search_first_name'  => $wild,
                'search_middle_name' => $wild,
                'search_last_name'   => $wild,
                'search_control_no'  => $wild,
            ];
        }

        $sql .= ' ORDER BY cr.date_issued DESC, cr.id DESC';

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();

        foreach ($rows as &$row) {
            $row['resident_full_name'] = format_full_name($row);
        }

        return $rows;
    }

    public function getRequestById(int $id): ?array
    {
        $sql = 'SELECT cr.*, r.resident_no, r.first_name, r.middle_name, r.last_name, r.suffix,
                       r.sex, r.civil_status, r.birth_date, r.age, r.address, r.contact_number,
                       r.occupation, r.citizenship, r.years_of_residency, r.voter_status, r.resident_status
                FROM certificate_requests cr
                INNER JOIN residents r ON r.id = cr.resident_id
                WHERE cr.id = :id
                LIMIT 1';

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch();
        if (!$row) {
            return null;
        }

        $row['resident_full_name'] = format_full_name($row);
        $row['officials'] = $this->getOfficials();

        return $row;
    }

    public function getOfficials(): array
    {
        $row = $this->conn->query('SELECT * FROM barangay_officials ORDER BY id ASC LIMIT 1')->fetch();

        return $row ?: [
            'captain_name'   => 'Hon. TRX Barangay Captain',
            'secretary_name' => 'TRX Barangay Secretary',
            'treasurer_name' => 'TRX Barangay Treasurer',
            'barangay_name'  => BARANGAY_NAME,
            'municipality'   => DEFAULT_MUNICIPALITY,
            'province'       => DEFAULT_PROVINCE,
        ];
    }

    public function resolveTemplateFile(string $certificateType): string
    {
        $map = [
            'Barangay Clearance'                      => 'clearance.php',
            'Certificate of Residency'               => 'residency.php',
            'Certificate of Indigency'               => 'indigency.php',
            'Business Clearance'                     => 'business.php',
            'Certificate of Unemployment'            => 'unemployment.php',
            'Certificate of Solo Parent'             => 'solo_parent.php',
            'Certificate of Good Moral / Good Standing' => 'good_moral.php',
            'Certificate of First Time Jobseeker'    => 'jobseeker.php',
        ];

        return $map[$certificateType] ?? 'generic.php';
    }
}
?>