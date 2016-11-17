<?php
require_once(dirname(__FILE__) . '/wovn_lang.php');

class WovnTranslator {
  private $defaultLanguage = '';
  private $token = '';
  private $html = '';

  public function __construct($defaultLanguage, $token, $html) {
    $this->defaultLanguage = $defaultLanguage;
    $this->token = $token;
    $this->html = $html;
  }

  public function translate($targetLanguageCode) {
    if ($targetLanguageCode === null || $targetLanguageCode === '') {
      $targetLanguageCode = $this->defaultLanguage;
    }
    if (!$this->validLanguageCode($targetLanguageCode)) {
      return $this->html;
    }
    return $this->insertScript($targetlanguageCode);
  }

  private function validLanguageCode($code) {
    return $code === WovnLang::getCode($code);
  }

  private function insertScript($languageCode) {
    $script = '<script src="//j.wovn.io/1" data-wovnio="key=' . $this->token . ' async"></script>';
    return preg_replace('/(<\/head>)/', "$script\n" . '$1', $this->html);
  }
}
