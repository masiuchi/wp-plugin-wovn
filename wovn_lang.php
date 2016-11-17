<?php
class WovnLang {
  public $langName = '';
  public $code = '';
  public $en = '';

  public function __construct($langName, $code, $en) {
    $this->langName = $langName;
    $this->code = $code;
    $this->en = $en;
  }

  public static function getAllLangs() {
    return array(
      'العربية'          => new self('العربية',          'ar', 'Arabic'),
      '简体中文'         => new self('简体中文',         'zh-CHS','Simp Chinese'),
      '繁體中文'         => new self('繁體中文',         'zh-CHT', 'Trad Chinese'),
      'Dansk'            => new self('Dansk',            'da', 'Danish'),
      'Nederlands'       => new self('Nederlands',       'nl', 'Dutch'),
      'English'          => new self('English',          'en', 'English'),
      'Suomi'            => new self('Suomi',            'fi', 'Finnish'),
      'Français'         => new self('Français',         'fr', 'French'),
      'Deutsch'          => new self('Deutsch',          'de', 'German'),
      'Ελληνικά'         => new self('Ελληνικά',         'el', 'Greek'),
      'עברית'            => new self('עברית',            'he', 'Hebrew'),
      'Bahasa Indonesia' => new self('Bahasa Indonesia', 'id', 'Indonesian'),
      'Italiano'         => new self('Italiano',         'it', 'Italian'),
      '日本語'           => new self('日本語',           'ja', 'Japanese'),
      '한국어'           => new self('한국어',           'ko', 'Korean'),
      'Bahasa Melayu'    => new self('Bahasa Melayu',    'ms', 'Malay'),
      'Norsk'            => new self('Norsk',            'no', 'Norwegian'),
      'Polski'           => new self('Polski',           'pl', 'Polish'),
      'Português'        => new self('Português',        'pt', 'Portuguese'),
      'Русский'          => new self('Русский',          'ru', 'Russian'),
      'Español'          => new self('Español',          'es', 'Spanish'),
      'Svensk'           => new self('Svensk',           'sv', 'Swedish'),
      'ภาษาไทย'          => new self('ภาษาไทย',          'th', 'Thai'),
      'हिन्दी'              => new self('हिन्दी',              'hi', 'Hindi'),
      'Türkçe'           => new self('Türkçe',           'tr', 'Turkish'),
      'Українська'       => new self('Українська',       'uk', 'Ukrainian'),
      'Tiếng Việt'       => new self('Tiếng Việt',       'vi', 'Vietnamese')
    );
  }

  public static function getLang($langName) {
    if ($langName === null || $langName === '') {
      return null;
    }
    $langs = self::getAllLangs();
    if (isset($langs[$langName])) {
      return $langs[$langName];
    }
    foreach ($langs as $lang) {
      $langNameLower = mb_strtolower($langName);
      if ($langNameLower === mb_strtolower($lang->langName)
        || $langNameLower === mb_strtolower($lang->code)
        || $langNameLower === mb_strtolower($lang->en)
      ) {
        return $lang;
      }
    }
    return null;
  }

  public static function getCode($langName) {
    $lang = self::getLang($langName);
    if ($lang) {
      return $lang->code;
    } else {
      return '';
    }
  }
}
