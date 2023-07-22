<?php

declare(strict_types=1);

namespace Web\Controllers;

class Helper {

  public static function Variables($_app) {
    $vars = [];
    if (!isset($_SESSION['csrf'])) {
      $_SESSION['csrf'] = bin2hex(random_bytes(15));
    }
    if (isset($_SESSION['logged'])) {
        $vars['logged'] = true;
    }
    $vars['csrf'] = $_SESSION['csrf'];
    if (!$_app['env']['dev'] && $_app['env']['ssl']) {
      $vars['host'] = 'https://'.$_SERVER['HTTP_HOST'];
    }
    else{
      $vars['host'] = 'http://'.$_SERVER['HTTP_HOST'];
    }
    if (!empty($_app['env']['pages'])) {
      foreach($_app['env']['pages'] as $pagekey => $pageitem) {
        $vars[$pagekey] = $pageitem;
      }
    }
    return $vars;
  }

  public static function Nix($str) {
      if (!empty($str)) {
         $str = str_replace('\\', '/', $str);
         $str = str_replace(':/', ':\\', $str);
         $str = rtrim(trim($str), '/');
     }
     return $str;
  }

  public static function TwigRender($_app) {

    self::ScssMinifier($_app);
    self::JsMinifier($_app);

    $twig_view = $_app['env']['root'].'/pages';
    $twig_path = new \Twig\Loader\FilesystemLoader([ $twig_view ]);
    $twig_load = new \Twig\Environment($twig_path, [
        'cache'       => false,
        'debug'       => $_app['env']['dev'] ? true : false,
        'auto_reload' => true
    ]);
    $twig_load->addFilter(
      new \Twig\TwigFilter('json_decode', 'json_decode')
    );
    /*
    $twig_template_load->addFilter(new \Twig\TwigFilter('uuid_generate', function() { return Utility::UuidGenerate(); }));
    $twig_template_load->addFilter(new \Twig\TwigFilter('is_json', function($string) { return Utility::IsJson($string); }));
    $twig_template_load->addFilter(new \Twig\TwigFilter('encrypt', function($string) { return Utility::Encrypt($_SESSION['csrf'], $_SESSION['csrf'], $string); }));
    */
    if ($_app['env']['dev']) {
        $twig_load->addExtension(new \Twig\Extension\DebugExtension());
    }

    $twig_display = $twig_load->render(
      'index.tpl',
      self::Variables($_app)
    );
    $twig_display = preg_replace_callback(
      '#<(?P<tag>textarea|pre|code)[^>]*?>.*</(?P=tag)>#sim',
      function ($matches) {
         return str_replace("\n", "%TEMPNEWLINE%", $matches[0]);
    }, $twig_display);

    return trim(str_replace(
      array("\n", "%TEMPNEWLINE%"), array('', "\n"),
      str_replace('> <', '><',
      trim(preg_replace('/\s\s+|\r|\n|\t/', '',
      preg_replace('/\s+/', ' ',
      $twig_display))))
    ));

  }

  public static function ScssMinifier($_app) {

    $scss_files = [
      'colors', 'mixins', 'fonts', 'keyframes', 'animations',
      'styling', 'header', 'navbar', 'sidebar', 'dashboard',
      'page', 'content', 'buttons', 'modal', 'forms',
      'footer', 'subfooter', 'custom'
    ];

    $css_file = $_app['env']['root'].'/static/style.min.css';
    $scss_dir = $_app['env']['root'].'/assets/scss';
    $tailwind = $_app['env']['root'].'/static/tailwind.sh';

    $scss_tmestmp = [];
    $scss_assets  = '';
    if (file_exists($scss_dir)) {
      foreach($scss_files as $file) {
        $scss_file = $scss_dir.'/'.$file.'.scss';
        if (file_exists($scss_file)) {
          $scss_tmestmp[] = filemtime($scss_file);
          $scss_handle    = fopen($scss_file, 'r');
          $scss_assets   .= fread($scss_handle, filesize($scss_file));
          fclose($scss_handle);
        }
      }
      if (!empty($scss_assets)) {
        try {
            $scss_compiler = new \ScssPhp\ScssPhp\Compiler;
            $scss_compiler->setOutputStyle(
              \ScssPhp\ScssPhp\OutputStyle::COMPRESSED
            );
            $scss_compiled = $scss_compiler
            ->compileString(trim($scss_assets))->getCss();
            if (file_exists($css_file)) {
                if (filemtime($css_file) < max($scss_tmestmp)) {
                    $scss_handle = fopen($css_file, 'w+');
                    fwrite($scss_handle, trim($scss_compiled));
                    fclose($scss_handle);
                }
            }else{
                $scss_handle = fopen($css_file, 'w+');
                fwrite($scss_handle, trim($scss_compiled));
                fclose($scss_handle);
            }
        } catch (\Exception $e) {
            if ($_app['env']['dev']) {
                echo 'Caught exception: ' . $e->getMessage();
                exit();
            }else{
                return false;
            }
        }
      }
    }

    return false;

  }

  public static function JsMinifier($_app) {

    $js_files = [
      'animations', 'components', 'header', 'navbar', 'sidebar',
      'dashboard', 'page', 'content', 'modal', 'forms', 'editor',
      'footer', 'subfooter', 'custom', 'onload'
    ];

    $js_file = $_app['env']['root'].'/static/scripts.min.js';
    $js_dir = $_app['env']['root'].'/assets/js';

    $js_tmestmp = [];
    $js_assets  = '';
    if (file_exists($js_dir)) {
      foreach($js_files as $file) {
        $js_cfile = $js_dir.'/'.$file.'.js';
        if (file_exists($js_cfile)) {
          $js_tmestmp[] = filemtime($js_cfile);
          $js_handle    = fopen($js_cfile, 'r');
          $js_assets   .= fread($js_handle, filesize($js_cfile));
          fclose($js_handle);
        }
      }
      if (!empty($js_assets)) {
        $js_compiler = new \MatthiasMullie\Minify\JS();
        $js_compiler->add($js_assets);
        $js_compiled = $js_compiler->minify();
        if (file_exists($js_file)) {
          if (filemtime($js_file) < max($js_tmestmp)) {
              $js_handler = fopen($js_file, 'w+');
              fwrite($js_handler,
                trim(str_replace("\n\r", '', $js_compiled)));
              fclose($js_handler);
          }
        }else{
          $js_handler = fopen($js_file, 'w+');
          fwrite($js_handler, trim($js_compiled));
          fclose($js_handler);
        }
      }
    }

    return false;

  }

  public static function Decrypt($data, $key) {
      $data = base64_decode($data);
      if (substr($data, 0, 8) != "Salted__") {
        return false;
      }
      $salt = substr($data, 8, 8);
      $keyAndIV = static::AesEvpKDF($key, $salt);
      $decryptPassword = openssl_decrypt(
        substr($data, 16),
        "aes-256-cbc",
        $keyAndIV["key"],
        OPENSSL_RAW_DATA,
        $keyAndIV["iv"]
      );
      return $decryptPassword;
  }

  public static function Encrypt($data, $key) {
      $salted = "Salted__";
      $salt = openssl_random_pseudo_bytes(8);
      $keyAndIV = static::AesEvpKDF($key, $salt);
      $encrypt  = openssl_encrypt(
        $data,
        "aes-256-cbc",
        $keyAndIV["key"],
        OPENSSL_RAW_DATA,
        $keyAndIV["iv"]
      );
      return base64_encode($salted . $salt . $encrypt);
  }

  static function AesEvpKDF($password,
    $salt, $keySize = 8, $ivSize = 4, $iterations = 1,
    $hashAlgorithm = "md5") {
    $targetKeySize = $keySize + $ivSize;
    $derivedBytes = "";
    $numberOfDerivedWords = 0;
    $block = NULL;
    $hasher = hash_init($hashAlgorithm);
    while ($numberOfDerivedWords < $targetKeySize) {
        if ($block != NULL) {
            hash_update($hasher, $block);
        }
        hash_update($hasher, $password);
        hash_update($hasher, $salt);
        $block = hash_final($hasher, TRUE);
        $hasher = hash_init($hashAlgorithm);
        for ($i = 1; $i < $iterations; $i++) {
            hash_update($hasher, $block);
            $block = hash_final($hasher, TRUE);
            $hasher = hash_init($hashAlgorithm);
        }
        $derivedBytes .= substr($block, 0, min(strlen($block), ($targetKeySize - $numberOfDerivedWords) * 4));
        $numberOfDerivedWords += strlen($block) / 4;
    }
    return array(
        "key" => substr($derivedBytes, 0, $keySize * 4),
        "iv"  => substr($derivedBytes, $keySize * 4, $ivSize * 4)
    );
  }

  public static function Connect($_app, $ep = '',$params = []) {
      if ($_app && $ep && !empty($params)) {
        if (function_exists('curl_exec')) {
          $ch = curl_init($_app['env']['api_url'].'/'.$ep);
          curl_setopt($ch, CURLOPT_HEADER, false);
          curl_setopt($ch, CURLOPT_HTTPHEADER, [
              'Client-Addr: '.$_SERVER['SERVER_ADDR'],
              'Client-Host: '.$_SERVER['SERVER_NAME']
          ]);
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            self::Encrypt(
              base64_encode(json_encode($params)),
              $_app['env']['api_key']
            )
          ]));
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          $response = curl_exec($ch);
          curl_close($ch);
          if (!empty($response)) {
            return json_decode($response, true);
          }
        }
      }
      return false;
    }

}
