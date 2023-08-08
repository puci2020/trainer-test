<?php

class UserController
{
    public function __construct(private UserGateway $gateway)
    {
    }

    public function processRequest(string $method, ?string $id): void
    {
        if ($id) {

            $this->processResourceRequest($method, $id);

        } else {

            $this->processCollectionRequest($method);

        }
    }

    private function processResourceRequest(string $method, string $id): void
    {
        $user = $this->gateway->get($id);

        if (!$user) {
            http_response_code(404);
            echo json_encode(["message" => "User not found"]);
            return;
        }

        switch ($method) {
            case "GET":
                echo json_encode($user);
                break;

            case "PATCH":
                $data = (array) json_decode(file_get_contents("php://input"), true);

                $errors = $this->getValidationErrors($data, false);

                if (!empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }

                $rows = $this->gateway->update($user, $data);

                echo json_encode([
                    "message" => "Product $id updated",
                    "rows" => $rows
                ]);
                break;

            case "DELETE":
                $rows = $this->gateway->delete($id);

                echo json_encode([
                    "message" => "Product $id deleted",
                    "rows" => $rows
                ]);
                break;

            default:
                http_response_code(405);
                header("Allow: GET, PATCH, DELETE");
        }
    }

    private function processCollectionRequest(string $method)
    {
        switch ($method) {
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                if (isset($data['email']) && isset($data['password'])) {
                    $user = $this->gateway->getLogin($data['email'], $data['password']);
                }
                echo json_encode($user);
                break;
            // $user = json_encode($user);
            // return $user;
            // var_dump($user);
            // if ($user['email']) {
            //     $this->gateway->createJwt($user['email']);
            // }


            // case "POST2":
            //     $data = (array) json_decode(file_get_contents("php://input"), true);

            //     $errors = $this->getValidationErrors($data);

            //     if (!empty($errors)) {
            //         http_response_code(422);
            //         echo json_encode(["errors" => $errors]);
            //         break;
            //     }

            //     $id = $this->gateway->create($data);

            //     http_response_code(201);
            //     echo json_encode([
            //         "message" => "User created",
            //         "id" => $id
            //     ]);
            //     break;

            default:
                http_response_code(405);
                header("Allow: GET, POST");
        }
    }

    private function getValidationErrors(array $data, bool $is_new = true): array
    {
        $errors = [];

        if ($is_new && empty($data["first_name"])) {
            $errors[] = "First_name is required";
        }

        if (array_key_exists("is_active", $data)) {
            if (filter_var($data["is_active"], FILTER_VALIDATE_BOOLEAN) === false) {
                $errors[] = "is_active must be boolean";
            }
        }

        return $errors;
    }
}