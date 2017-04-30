<?php

/*
CREATE TABLE IF NOT EXISTS `shop_search_word` (
  `id_word` int(11) NOT NULL AUTO_INCREMENT,
  `word` varchar(30) NOT NULL,
  PRIMARY KEY (`id_word`),
  KEY `idx_word_word` (`word`(8))
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `shop_search_index` (
  `id_index` int(11) NOT NULL AUTO_INCREMENT,
  `id_art` int(11) NOT NULL,
  `id_word` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`id_index`),
  KEY `idx_index_linkword` (`id_art`,`id_word`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

 */
namespace  Monstercms\Lib;
class search
{

 	public $db_words = 'article_search_word';
 	public $db_index = 'article_search_index';
    private $db;

	function __construct()
	{
        global $DB;
        $this->db = $DB;
	}

	function index($text, $id_art, $weight = 1)
	{
    	$words_core = $this->words_core_array($text);


		$words_and_count = array_count_values ($words_core);


        $id_art = intval($id_art);



        $sql = 'INSERT INTO '.$this->db_index.'(id_art, id_word, weight) VALUES';

        $query = false;

        foreach($words_and_count as $word_core => $count)
        {
           $id_word =  $this->insert_word($word_core);

           $sql .= '("'.$id_art.'","'.$id_word.'","'.($count*$weight).'"),';
            $query = true;

        }

        if($query)
        {
            $sql = trim($sql, ',');
            $this->db->query($sql);
        }



	}

	function delete_index($id_art)
	{
         $id_art = intval($id_art);
         /* Удаляем старый индекс для заданной статьи */

        $sql = 'DELETE FROM '.$this->db_index.' WHERE id_art='.$id_art;
        $this->db->query($sql);
	}

	/* функция возрващает массив основ слов*/
	function words_core_array($text)
	{
        /* удаление тегов */
    	$text =  $this->delete_tages($text);

        /* Переводим в нижний регистр */
        $text = mb_strtolower($text, 'UTF-8');

        /* Удаляем символы */
       $text = preg_replace('/[^\d\w-]+/u',' ',$text);

        /* Помещаем пробел перед первым словом */
		$text = ' '.$text;

        /* Удаляем слова менее 2х символов */
        //$text = preg_replace('|\b[\d\w]{1,3}\b|iu', ' ', $text);

        /* Удаляем лишнии пробелы*/
    	$text = preg_replace('/\s+/',' ',trim($text));

     	/* Заносим в массив слова */

     	$words = explode( " ", $text );


     	/* Заносим основы слов в массив */

        $words_core = array();


        $stemmer = new Lingua_Stem_Ru();
        $i=0;

     	foreach($words as $w)
     	{

         	$lenw =  strlen($w);

         	if($lenw >= 4)
         	{

              $words_core[$i] = $stemmer->stem_word($w);
              $i++;
         	}
     	}

     	return $words_core;
	}

	/* функция записывает слово в бд и возращает id_word */

	function insert_word( $word)
	{
    	$word   = $this->db->escape_string($word);
    	$sql    = 'SELECT id_word FROM '.$this->db_words.' WHERE `word` = "'.$word.'"';
    	$result = $this->db->query($sql);



        if($this->db->numRows($result) > 0)
        {
           $id_word = mysql_result($result,0);
        }
        else
        {
         	$sql = 'INSERT INTO '.$this->db_words.' (`word`) VALUES("'.$word.'")';
            $this->db->query($sql);
         	$id_word = $this->db->insertId();
        }


        return $id_word;
	}

	function is_word()
	{

	}



    /* функция змаеняет теги на пробел */

	function delete_tages($tex)
	{
        $tex = preg_replace('@<script[^>]*?>.*?</script>@si',' ',$tex);
		$tex = preg_replace('@<style[^>]*?>.*?</style>@si',' ',$tex);
        $tex = preg_replace('@<[\/\!]*?[^<>]*?>@si',' ',$tex);

        return $tex;
	}

    /* Функиця возврощает результат поиска в формате ид_статьи1,ид_статьи2,..ид_статьиN
    	удобный формат для SELECT (IN(), сортировка ORDER BY Field() )
    */
	function result($key_word)
	{
        $words_core = $this->words_core_array($key_word);

        if(sizeof($words_core) == 0) return '';
        //print_r($words_core);

        $if_clause = '';

        for($i=0,$num=sizeof($words_core);$i<$num;$i++)
        {
	        $if_clause .= "tw.word='".$words_core[$i]."'";

	        if($i!=$num-1) $if_clause .= " or ";
        }

        $sql = "SELECT ti.id_art, COUNT(DISTINCT tw.id_word)*1000 + SUM(ti.weight) AS rel
                FROM ".$this->db_words." tw,".$this->db_index." ti
                WHERE (".$if_clause.") AND ti.id_word=tw.id_word
                GROUP BY ti.id_art ORDER BY rel DESC";



 		$result = $this->db->query($sql);

      	$out = '';

      	while($row = $this->db->fetchArray($result))
      	{
            $out .= $row[0].',';
      	}

        $out = trim($out,',');

        return $out;

	}


}





/* Стеммер Портера возвращает основу слова
Изменение - работа с UTF-8
*/
class Lingua_Stem_Ru
{
    var $VERSION = "0.02";
    var $Stem_Caching = 0;
    var $Stem_Cache = array();
    var $VOWEL = '/аеиоуыэюя/';
    var $PERFECTIVEGROUND = '/((ив|ивши|ившись|ыв|ывши|ывшись)|((?<=[ая])(в|вши|вшись)))$/';
    var $REFLEXIVE = '/(с[яь])$/';
    var $ADJECTIVE = '/(ее|ие|ые|ое|ими|ыми|ей|ий|ый|ой|ем|им|ым|ом|его|ого|еых|ую|юю|ая|яя|ою|ею)$/';
    var $PARTICIPLE = '/((ивш|ывш|ующ)|((?<=[ая])(ем|нн|вш|ющ|щ)))$/';
    var $VERB = '/((ила|ыла|ена|ейте|уйте|ите|или|ыли|ей|уй|ил|ыл|им|ым|ены|ить|ыть|ишь|ую|ю)|((?<=[ая])(ла|на|ете|йте|ли|й|л|ем|н|ло|но|ет|ют|ны|ть|ешь|нно)))$/';
    var $NOUN = '/(а|ев|ов|ие|ье|е|иями|ями|ами|еи|ии|и|ией|ей|ой|ий|й|и|ы|ь|ию|ью|ю|ия|ья|я)$/';
    var $RVRE = '/^(.*?[аеиоуыэюя])(.*)$/';
    var $DERIVATIONAL = '/[^аеиоуыэюя][аеиоуыэюя]+[^аеиоуыэюя]+[аеиоуыэюя].*(?<=о)сть?$/';

    function s(&$s, $re, $to)
    {
        $orig = $s;
        $s = preg_replace($re, $to, $s);
        return $orig !== $s;
    }

    function m($s, $re)
    {
        return preg_match($re, $s);
    }

    function stem_word($word)
    {
        $word =  mb_strtolower($word,'UTF-8');
        $word = str_replace('ё', 'е', $word);
        # Check against cache of stemmed words
        if ($this->Stem_Caching && isset($this->Stem_Cache[$word])) {
            return $this->Stem_Cache[$word];
        }
        $stem = $word;
        do {
          if (!preg_match($this->RVRE, $word, $p)) break;
          $start = $p[1];
          $RV = $p[2];
          if (!$RV) break;

          # Step 1
          if (!$this->s($RV, $this->PERFECTIVEGROUND, '')) {
              $this->s($RV, $this->REFLEXIVE, '');

              if ($this->s($RV, $this->ADJECTIVE, '')) {
                  $this->s($RV, $this->PARTICIPLE, '');
              } else {
                  if (!$this->s($RV, $this->VERB, ''))
                      $this->s($RV, $this->NOUN, '');
              }
          }

          # Step 2
          $this->s($RV, '/и$/', '');

          # Step 3
          if ($this->m($RV, $this->DERIVATIONAL))
              $this->s($RV, '/ость?$/', '');

          # Step 4
          if (!$this->s($RV, '/ь$/', '')) {
              $this->s($RV, '/ейше?/', '');
              $this->s($RV, '/нн$/', 'н');
          }

          $stem = $start.$RV;
        } while(false);
        if ($this->Stem_Caching) $this->Stem_Cache[$word] = $stem;
        return $stem;
    }

    function stem_caching($parm_ref)
    {
        $caching_level = @$parm_ref['-level'];
        if ($caching_level) {
            if (!$this->m($caching_level, '/^[012]$/')) {
                die(__CLASS__ . "::stem_caching() - Legal values are '0','1' or '2'. '$caching_level' is not a legal value");
            }
            $this->Stem_Caching = $caching_level;
        }
        return $this->Stem_Caching;
    }

    function clear_stem_cache()
    {
        $this->Stem_Cache = array();
    }
}







?>