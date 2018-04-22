<?php  namespace  Monstercms\Lib;

defined('MCMS_ACCESS') or die('No direct script access.');

class Pagination
{
	private $numRows;
	private $numRowsInPage;
	private $getVariableName = "page";
	private $result;
	private $sql;
	private $db;
	private $thisPageNum = 1;
	private $tplLink = '<a href="{URL}" >{PAGENUM}</a>';
	private $tplLinkThis = '<span class="this">{PAGENUM}</span>';
	//количество ссылок справа и слева
	private $quantityLinks = 3;
	private $items = null;

	/**
	 * @param \PDO $db
	 * @param $slq
	 * @param int $numRow - количество на странице
	 */
	function __construct(\PDO $db, $slq, $numRow = 10)
	{

		$this->db =  $db;

		if (isset($_REQUEST[$this->getVariableName]) &&
			preg_match("/^\d+$/", $_REQUEST[$this->getVariableName])) {
			$this->thisPageNum = (int) $_REQUEST[$this->getVariableName];
		}

		$slqSelect = preg_replace("/SELECT/i","SELECT SQL_CALC_FOUND_ROWS ", $slq);

		$limit = $this->thisPageNum * $numRow - $numRow;
		$slqSelect .= " LIMIT {$limit} , ".$numRow;

		$this->sql = $slqSelect;

		$this->result = $this->db->query($slqSelect);

		$sql = "SELECT FOUND_ROWS()";
		$result = $this->db->query($sql);

		$numRows = $result->fetch();
		$this->numRows = $numRows[0];

		$this->numRowsInPage = $numRow;
	}

	/**
	 * Возвращает раезультат
	 * @return \PDOStatement
	 */
	function getResult()
	{
		return $this->result;
	}

	/**
	 * Сгенерированный sql запрос
	 * @return string
	 */
	function getSql()
	{
		return $this->sql;
	}

	/**
	 * Имя GET переменной
	 * @param $variableName
	 * @return mixed
	 */
	function setVariableName($variableName) {
		return $this->getVariableName = $variableName;
	}

	/**
	 * Результат выборки из бд
	 * @return array
	 */
	function getItems()
	{
		if ($this->items === null) {
			$this->items = $this->result->fetchAll(\PDO::FETCH_ASSOC);
		}

		return $this->items;
	}

	/**
	 * Шаблон ссылки
	 * @param $tpl
	 */
	function setTplLink($tpl)
	{
		$this->tplLink = $tpl;
	}

	/**
	 * Шаблон ссылки на текущую страницу
	 * @param $tpl
	 */
	function setTplLinkThis($tpl)
	{
		$this->tplLinkThis = $tpl;
	}

	/**
	 * html код плагинации
	 * @return bool|string
	 */
	function getNavigation()
	{
		if($this->numRows <= $this->numRowsInPage) return false;

		$tplThisPage = $this->getUrl($this->tplLinkThis);
		$tpl = $this->getUrl($this->tplLink);

		$numPages = (int) ($this->numRows/$this->numRowsInPage);

		if($numPages != $this->numRows / $this->numRowsInPage )	$numPages++;

		$html ="";

		if ($this->thisPageNum !=1) {
			$tplTemp =  str_replace("{PAGE}", 1, $tpl);
			$html   .=  str_replace("{PAGENUM}", "&laquo;&laquo;", $tplTemp);
			$tplTemp =  str_replace("{PAGE}", $this->thisPageNum-1,$tpl);
			$html   .=  str_replace("{PAGENUM}", "&laquo;", $tplTemp);
		}

		for ($i = $this->quantityLinks; $i > 0; $i--) {

			if ($this->thisPageNum-$i > 0) {
				$tplTemp =  str_replace("{PAGE}", $this->thisPageNum - $i, $tpl);
				$html   .=  str_replace("{PAGENUM}", $this->thisPageNum - $i, $tplTemp);
			}
		}

		if ($numPages > 1) {
			$tplTemp =  str_replace("{PAGE}",    $this->thisPageNum, $tplThisPage);
			$html   .=  str_replace("{PAGENUM}", $this->thisPageNum, $tplTemp);
		}

		for($i = 0; $i < $this->quantityLinks; $i++) {
			if($this->thisPageNum +$i +1 <= $numPages) {
				$tplTemp =  str_replace("{PAGE}", $this->thisPageNum + $i + 1, $tpl);
				$html   .=  str_replace("{PAGENUM}",$this->thisPageNum + $i + 1, $tplTemp);
			}
		}

		if($this->thisPageNum != $numPages) {

			$tplTemp =  str_replace("{PAGE}", $this->thisPageNum + 1, $tpl);
			$html   .=  str_replace("{PAGENUM}", "&raquo;", $tplTemp);

			$tplTemp =  str_replace("{PAGE}", $numPages, $tpl);
			$html   .=  str_replace("{PAGENUM}", "&raquo;&raquo;", $tplTemp);
		}

		return $html;
	}

	private function getUrl($tplLink)
	{
		$thisUrl = Path::this_url();
		if(stripos($thisUrl, $this->getVariableName) !== false) {

			$url = Path::replace(
				array($this->getVariableName => '{PAGE}'),
				$thisUrl
			);
		} else {
			$url = Path::add_param($this->getVariableName . "={PAGE}");
		}

		return str_replace("{URL}", $url, $tplLink);
	}

}