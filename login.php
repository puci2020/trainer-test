<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors_errors', 1);
error_reporting(E_ALL);
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once('vendor/autoload.php');

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $api_url = 'http://localhost/knight/api/auth';
    $data = array(
        'email' => $email,
        'password' => $password
    );
    $json_data = json_encode($data);

    $curl = curl_init();

    curl_setopt_array(
        $curl,
        array(
            CURLOPT_URL => 'http://localhost/knight/api/auth',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $json_data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        )
    );

    $response = curl_exec($curl);

    curl_close($curl);
    $result = json_decode($response);
    $error = null;
    if (isset($result->email)) {

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
    } else {
        $error = 'Błędne dane';
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Knight - Free Bootstrap 4
        Product Landing Page Template
    </title>
    <meta name="description"
        content="Knight is a beautiful Bootstrap 4 template for product landing pages." />

    <!--Inter UI font-->
    <link
        href="https://rsms.me/inter/inter-ui.css"
        rel="stylesheet">

    <!--vendors styles-->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">

    <!-- Bootstrap CSS / Color Scheme -->
    <link rel="stylesheet"
        href="css/default.css"
        id="theme-color">
</head>

<body>
    <section
        class="smart-scroll h-100 ">

        <div
            class="container-fluid w-50 h-100 d-flex justify-content-center align-items-center">
            <form
                class="form-signin w-50"
                method="post">
                <div
                    class="text-center mb-4">
                    <?php if (isset($error)) {
                        ?>
                        <div class="alert alert-danger"
                            role="alert">
                            <?php echo $error; ?>
                        </div>
                    <?php } ?>
                    <img class="mb-4"
                        src="https://getbootstrap.com/docs/4.0/assets/brand/bootstrap-solid.svg"
                        alt=""
                        width="72"
                        height="72">
                    <h1
                        class="h3 mb-3 font-weight-normal">
                        Zaloguj się</h1>
                </div>

                <div
                    class="form-label-group mb-4">
                    <input type="email"
                        name="email"
                        id="inputEmail"
                        class="form-control"
                        placeholder="Email"
                        required=""
                        autofocus="">
                </div>

                <div
                    class="form-label-group mb-4">
                    <input
                        type="password"
                        name="password"
                        id="inputPassword"
                        class="form-control"
                        placeholder="Hasło"
                        required="">
                </div>

                <input
                    class="btn btn-lg btn-primary btn-block"
                    type="submit"
                    value="Zaloguj" />

                <!--            </input>-->
                <p
                    class="mt-5 mb-3 text-muted text-center">
                    ©
                    <?php echo date('Y'); ?>
                </p>
            </form>
        </div>
    </section>
</body>

</html>