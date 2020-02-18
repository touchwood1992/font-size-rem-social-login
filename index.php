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
  $login = false;
  //Get Login URL Of Facebook site
  require_once __DIR__ . '/vendor/autoload.php';

  /**==================================================== */
  /*FACEBOOK LOGIN*/
  /**==================================================== */
  if (isset($_SESSION["fb_access_token"]) && $_GET["type"] == "fb") {

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
    $login = true;
  } else {
    $fb = new Facebook\Facebook([
      'app_id' => $facebook_credentials["appid"],
      'app_secret' => $facebook_credentials["appsecret"],
      'default_graph_version' => 'v3.2',
    ]);

    $helper = $fb->getRedirectLoginHelper();
    $permissions = ['email'];
    $fbloginurl = $helper->getLoginUrl($facebook_credentials["callbak_url"], $permissions);
  }

  /**==================================================== */
  /*GOOGLE LOGIN*/
  /**==================================================== */

  if (isset($_SESSION["fb_access_token"]) && $_GET["type"] == "google") {
    $gClient = new Google_Client();
    $gClient->setApplicationName('Login to cloudweblabs.com');
    $gClient->setClientId($google_credentials['client_id']);
    $gClient->setClientSecret($google_credentials['client_secret']);
    //$gClient->setRedirectUri($google_credentials['callback_url']);
    //$gClient->addScope("email");
    //$gClient->addScope("profile");

    $gClient->setAccessToken($_SESSION["google_access_token"]);

    // get profile info
    $google_oauth = new Google_Service_Oauth2($gClient);
    $google_account_info = $google_oauth->userinfo->get();
    $email =  $google_account_info->email;
    $name =  $google_account_info->name;

    echo "Name : " . $name;
    echo "Email : " . $email;
    $login = true;
  } else {
    $gClient = new Google_Client();
    $gClient->setApplicationName('Login to cloudweblabs.com');
    $gClient->setClientId($google_credentials['client_id']);
    $gClient->setClientSecret($google_credentials['client_secret']);
    $gClient->setRedirectUri($google_credentials['callback_url']);
    $gClient->addScope("email");
    $gClient->addScope("profile");

    $google_oauthV2 = new Google_Service_Oauth2($gClient);
    $googleloginurl = $gClient->createAuthUrl();
  }
  ?>
  <main>
    <header>
      <div class="container site-heading-container">
        <h1 class="site-heading ">Social Login Signup</h1>
      </div>
    </header>
    <?php
    if ($login) {
      echo "You are successfully login";
    } else {
      ?>
      <div class="container">
        <div class="social-container">
          <ul class="nav nav-tabs d-flex flex-wrap justify-content-around">
            <li class="social-container__item">
              <a class="social__link--heading active" data-toggle="tab" href="#login-tab">Login</a>
            </li>
            <li class="social-container__item">
              <a class="social__link--heading" data-toggle="tab" href="#signup-tab">Signup</a>
            </li>
          </ul>

          <div class="tab-content">
            <div class="tab-pane active" id="login-tab">

              <div class="social-tab__container d-flex flex-wrap justify-content-around">

                <form>
                  <div class="form-group">
                    <input type="text" name="input_username" id="" class="form-control" placeholder="Enter Email">
                  </div>
                  <div class="form-group">
                    <input type="text" name="input_password" id="" class="form-control" placeholder="Enter Password">
                  </div>
                  <button type="submit" class="btn btn-social">Login</button>
                </form>

                <div class="social-links">
                  <a href="<?php echo htmlspecialchars($fbloginurl); ?>" class="social-links__link social-links__link--fb">
                    <img src="images/fb.png" alt="" />
                  </a>
                  <a href="<?php echo htmlspecialchars($googleloginurl); ?>" class="social-links__link social-links__link--fb">
                    <img src="images/google.png" alt="" />
                  </a>
                </div>

              </div>

            </div>
            <div class="tab-pane" id="signup-tab">
              <div class="social-tab__container d-flex justify-content-around">

                <form>
                  <div class="form-group">
                    <input type="text" name="input_username" id="" class="form-control" placeholder="Enter Email">
                  </div>
                  <div class="form-group">
                    <input type="text" name="input_password" id="" class="form-control" placeholder="Enter Password">
                  </div>
                  <button type="submit" class="btn btn-social">Signup</button>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>

  </main>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>