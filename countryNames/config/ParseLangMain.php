<?php

//class to turn language csv files into php arrays. Includes also alphabetical sorting


class ParseLangMain {
  private $lang, $keys;

  private function encode($s) {
    return str_replace(array('\\', '\''), array('\\\\', '\\\''), $s);
  }

  private function sortFunc($a, $b) {
    foreach ($this->keys as $k) {
      if ($k == -1) $r = strcoll($a[$this->lang], $b[$this->lang]);
      else $r = $a[$k] - $b[$k];
        if ($r) return $r;
    }
    return $r;
  }

  private function readCSV($filename) {
    $oldval = ini_set('auto_detect_line_endings', '1');
    $keys = array();
    $data = array();
    $row = 0;
    if (($handle = fopen($filename, 'r')) === FALSE) {
      die("could not open file: $intpuFile");
    }
    while (($line = fgetcsv($handle)) !== FALSE) {
      $row++;
      if ($row == 1) {
        $keys = $line;
      } else {
        $data[] = array_combine($keys, $line);
      }
      }
    fclose($handle);
    ini_set('auto_detect_line_endings', $oldval);
    return $data;
  }

  private function appendToFile($file, $text) {
    if (($handle = fopen(sprintf($file, $this->lang), 'a')) === FALSE) {
      die("could not append to file: $file");
    }
    fwrite($handle, $text);
    fclose($handle);
  }

  public function generate() {
    global $ConfigLangMain;

    // initialize files
    foreach ($ConfigLangMain['localeMap'] as $lang => $locale) {
            file_put_contents(sprintf($ConfigLangMain['outputFile'], $lang), "<?php\n");
    }

    $used = array();
    foreach ($ConfigLangMain['inputFiles'] as $inputFile => $config) {
      $data = $this->readCSV($inputFile);
      foreach ($ConfigLangMain['localeMap'] as $lang => $locale) {
        $this->keys = $config['sortKeys'];
              $file = sprintf($ConfigLangMain['outputFile'], $lang);
        if (!isset($data[0][$lang])) $this->lang = $ConfigLangMain['fallback'];
        else $this->lang = $lang;
        if (!isset($data[0][$this->lang])) die("could not use fallback language in file $inputFile\n");
        if ($locale != $lang && array_key_exists($locale, $data[0])) {
          if (($idx = array_search(-1, $this->keys)) !== FALSE) $this->keys[$ids] = $locale;
        } elseif (count($this->keys)) {
                $oldLocale = setlocale(LC_COLLATE, '0');
                if (setlocale(LC_COLLATE, $locale) === FALSE) {
                  die("could not use locale '$locale' for LC_COLLATE\n");
                }
                usort($data, array($this, 'sortFunc'));
                setlocale(LC_COLLATE, $oldLocale);
        }
  
        if (!isset($used[$config['variable']])) $text = sprintf("$%s = array(\n", $config['variable']);
        else $text = sprintf("$%s = array_merge($%s, array(\n", $config['variable'], $config['variable']);
        foreach ($data as $i => $d) $text .= sprintf(" '%s' => '%s',\n", $this->encode($d[$config['key']]), $this->encode($d[$this->lang]));
        if (!isset($used[$config['variable']])) $text .= ");\n\n";
        else $text .= "));\n\n";
              $this->appendToFile($file, $text);
      }
      $used[$config['variable']] = 1;
    }
  }
}

