<?php
session_start();
include_once("includes/config.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Social Login App</title>
  <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
  <link rel="stylesheet" href="style.css" />
</head>

<body>
  <?php
  //Get Login URL Of Facebook site
  require_once __DIR__ . '/vendor/autoload.php';


  if (isset($_SESSION["fb_access_token"])) {
    $fb = new Facebook\Facebook([
      'app_id' => $facebook_credentials["appid"],
      'app_secret' => $facebook_credentials["appsecret"],
      'default_graph_version' => 'v5.0',
    ]);

    try {
      // Returns a `Facebook\FacebookResponse` object
      $response = $fb->get('/me?fields=' . $facebook_credentials["permissions"], $_SESSION['fb_access_token']);
    } catch (Facebook\Exceptions\FacebookResponseException $e) {
      echo 'Graph returned an error: ' . $e->getMessage();
      exit;
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
      echo 'Facebook SDK returned an error: ' . $e->getMessage();
      exit;
    }

    $user = $response->getGraphUser();
    print_r($user);
    // OR
    // echo 'Name: ' . $user->getName();
    try {
      if ($user["picture"]) {
        $picture = $user["picture"]->getUrl();
        print_r($picture);
      }
    } catch (Exception $e) {
      echo 'Message: ' . $e->getMessage();
    }
  }

  $fb = new Facebook\Facebook([
    'app_id' => $facebook_credentials["appid"],
    'app_secret' => $facebook_credentials["appsecret"],
    'default_graph_version' => 'v3.2',
  ]);

  $helper = $fb->getRedirectLoginHelper();
  $permissions = ['email'];
  $loginUrl = $helper->getLoginUrl($facebook_credentials["callbak_url"], $permissions);
  ?>
  <main>
    <div class="container mt-5 mb-4">
      <h1 class="site-heading text-center">Social Login Signup</h1>
    </div>

    <div class="container">
      <div class="social-container">
        <ul class="nav nav-tabs d-flex justify-content-around">
          <li class="social-container__item">
            <a class="social__link social__link--lightblue active" data-toggle="tab" href="#login-tab">Login</a>
          </li>
          <li class="social-container__item">
            <a class="social__link social__link--blue" data-toggle="tab" href="#signup-tab">Signup</a>
          </li>
        </ul>

        <div class="tab-content">
          <div class="tab-pane active" id="login-tab">
            <p class="login-texts">
              Login Account With One of the Below Social Platform.
            </p>

            <div class="social-links d-flex flex-wrap justify-content-around align-items-center">
              <a href="<?php echo htmlspecialchars($loginUrl); ?>" class="social-link social-link--facebook">
                <img src="images/fb.png" alt="" />
              </a>
              <a href="#" class="social-link social-link--google">
                <img src="images/google.png" alt="" />
              </a>
            </div>
          </div>
          <div class="tab-pane" id="signup-tab">
            <p class="login-texts">
              Signup Account With One of the Below Social Platform.
            </p>
            <div class="social-links d-flex flex-wrap justify-content-around align-items-center">
              <a href="<?php echo htmlspecialchars($loginUrl); ?>" class="social-link social-link--facebook">
                <img src="images/fb.png" alt="" />
              </a>
              <a href="#" class="social-link social-link--google">
                <img src="images/google.png" alt="" />
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>