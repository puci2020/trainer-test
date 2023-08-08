<?php


class UserGateway
{
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAll(): array
    {
        $sql = "SELECT *
                FROM user";

        $stmt = $this->conn->query($sql);

        $data = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $row["is_active"] = (bool) $row["is_active"];

            $data[] = $row;
        }

        return $data;
    }

    public function create(array $data): string
    {
        $sql = "INSERT INTO user (first_name, last_name, email, password, is_active)
                VALUES (:first_name, :last_name, :email, :password, :is_active)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":first_name", $data["first_name"], PDO::PARAM_STR);
        $stmt->bindValue(":last_name", $data["last_name"], PDO::PARAM_INT);
        $stmt->bindValue(":email", $data["email"], PDO::PARAM_INT);
        $stmt->bindValue(":password", password_hash($data["password"], PASSWORD_BCRYPT), PDO::PARAM_INT);
        $stmt->bindValue(":is_active", (bool) ($data["is_active"] ?? false), PDO::PARAM_BOOL);

        $stmt->execute();

        return $this->conn->lastInsertId();
    }

    public function get(string $id): array|false
    {
        $sql = "SELECT *
                FROM user
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data !== false) {
            $data["is_active"] = (bool) $data["is_active"];
        }

        return $data;
    }

    public function getLogin(string $email, string $password): array|false
    {
        $sql = "SELECT *
                FROM user
                WHERE email = :email and is_active = true";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":email", $email, PDO::PARAM_INT);

        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);


        if (password_verify($password, $data['password'])) {
            // Password is correct
            return $data;
        } else {
            // Password is incorrect
            return false;
        }

    }

    public function createJwt(string $email)
    {
        $secret_Key = '68V0zWFrS72GbpPreidkQFLfj4v9m3Ti+DXc8OB0gcM=';
        $date = new DateTimeImmutable();
        $expire_at = $date->modify('+6 minutes')->getTimestamp(); // Add 60 seconds
        $domainName = "localhost";
        $username = $email; // Retrieved from filtered POST data
        $alg = 'HS512';
        $request_data = [
            'iat' => $date->getTimestamp(),
            // Issued at: time when the token was generated
            'iss' => $domainName,
            // Issuer
            'nbf' => $date->getTimestamp(),
            // Not before
            'exp' => $expire_at,
            // Expire
            'userName' => $username, // User name
        ];

        $jwt = JWT::encode($request_data, $secret_Key, $alg);
        setcookie('token', $jwt);
        header('Location: dashboard.php');
    }

    // public function update(array $current, array $new): int
    // {
    //     $sql = "UPDATE product
    //             SET name = :name, size = :size, is_available = :is_available
    //             WHERE id = :id";

    //     $stmt = $this->conn->prepare($sql);

    //     $stmt->bindValue(":name", $new["name"] ?? $current["name"], PDO::PARAM_STR);
    //     $stmt->bindValue(":size", $new["size"] ?? $current["size"], PDO::PARAM_INT);
    //     $stmt->bindValue(":is_available", $new["is_available"] ?? $current["is_available"], PDO::PARAM_BOOL);

    //     $stmt->bindValue(":id", $current["id"], PDO::PARAM_INT);

    //     $stmt->execute();

    //     return $stmt->rowCount();
    // }

    // public function delete(string $id): int
    // {
    //     $sql = "DELETE FROM product
    //             WHERE id = :id";

    //     $stmt = $this->conn->prepare($sql);

    //     $stmt->bindValue(":id", $id, PDO::PARAM_INT);

    //     $stmt->execute();

    //     return $stmt->rowCount();
    // }
}