<?
namespace  Monstercms\Lib;

 class pageList
 {
 	private $numRows;
    private $numRowsInPage;
    private $getVariableName = "page";
    public $result;
    public $sql;
    private $db;

 	//function mPageList($slqSelect, $numRow = 10)
 	function __construct($slqSelect, $numRow = 10)
 	{
        global $DB;

        $this->db =  $DB;

 		if( !isset($_GET[$this->getVariableName]) || !preg_match("/^\d+$/",$_GET[$this->getVariableName]) )
 		{
        	$_GET[$this->getVariableName] = 1;
 		}

 		$slqSelect = preg_replace("/SELECT/i","SELECT SQL_CALC_FOUND_ROWS ",$slqSelect);
        //$i*$this->numRowsInPage
 		$slqSelect .= " LIMIT ".($_GET[$this->getVariableName]*$numRow-$numRow)." , ".$numRow;

        $this->sql = $slqSelect;
   		$this->result = $this->db->query($slqSelect);

        $sql = "SELECT FOUND_ROWS()";

        $this->numRows = $this->db->fetchArray( $this->db->query($sql));

        $this->numRows = $this->numRows[0];

        $this->numRowsInPage = $numRow;

 	}


  	function get_all(
  					 $tpl="<a href=\"{URL}\" class='we' >{PAGENUM}</a>",
  					 $tplThisPage="<a href=\"{URL}\" class='pasivl'>{PAGENUM}</a>"
  					 )
  	{
    	if($this->numRows <= $this->numRowsInPage) return false;

		$tplThisPage = $this->get_url($tplThisPage);
		$tpl = $this->get_url($tpl);

		$numPages = $clink = (int) ($this->numRows/$this->numRowsInPage);

		if($numPages != $this->numRows/$this->numRowsInPage )	$numPages++;

        $html ="";

		for($i=0;$i<$numPages-1;$i++)
		{
            if($i+1 == $_GET[$this->getVariableName])
            {
        		$tplTemp = str_replace("{PAGE}",$i+1,$tplThisPage);
                $html =    str_replace("{PAGENUM}",$i+1,$tplTemp);
            }
            else
            {
            	$tplTemp = str_replace("{PAGE}",$i+1,$tpl);
                $html =    str_replace("{PAGENUM}",$i+1,$tplTemp);
            }
		}



        return $html;

  	}


  	function get(
		$tpl="<a href=\"{URL}\" class='we' >{PAGENUM}</a>",
		$tplThisPage="<a href=\"{URL}\" class='pasivl'>{PAGENUM}</a>",
  					 $clopage = 3  //количество страниц слева и справа
  					 )
  	{
    	if($this->numRows <= $this->numRowsInPage) return false;

		$tplThisPage = $this->get_url($tplThisPage);
		$tpl = $this->get_url($tpl);




		$numPages = $clink = (int) ($this->numRows/$this->numRowsInPage);

		if($numPages != $this->numRows/$this->numRowsInPage )	$numPages++;

        $html ="";
        /*
		for($i=0;$i<$numPages-1;$i++)
		{
            if($i+1 == $_GET[$this->getVariableName])
            {
        		$tplTemp = str_replace("{PAGE}",$i+1,$tplThisPage);

            }
            else
            {
            	$tplTemp = str_replace("{PAGE}",$i+1,$tpl);

            }
		}
        */

		if($_GET[$this->getVariableName] !=1)
		{
            $tplTemp =  str_replace("{PAGE}",1,$tpl);
            $html   .=  str_replace("{PAGENUM}","&laquo;&laquo;",$tplTemp);
            $tplTemp =  str_replace("{PAGE}",$_GET[$this->getVariableName]-1,$tpl);
            $html   .=  str_replace("{PAGENUM}","&laquo;",$tplTemp);
		}

		for($i=$clopage;$i>0;$i--)
		{

        	if($_GET[$this->getVariableName]-$i > 0)
        	{

        		$tplTemp =  str_replace("{PAGE}",$_GET[$this->getVariableName]-$i,$tpl);
            	$html   .=  str_replace("{PAGENUM}",$_GET[$this->getVariableName]-$i,$tplTemp);
            }
		}

		if($numPages>1)
		{

        	$tplTemp =  str_replace("{PAGE}",$_GET[$this->getVariableName],$tplThisPage);
        	$html   .=  str_replace("{PAGENUM}",$_GET[$this->getVariableName],$tplTemp);
        }

		for($i=0;$i<$clopage;$i++)
		{
        	if($_GET[$this->getVariableName]+$i+1 <= $numPages)
        	{
        		$tplTemp =  str_replace("{PAGE}",$_GET[$this->getVariableName]+$i+1,$tpl);
            	$html   .=  str_replace("{PAGENUM}",$_GET[$this->getVariableName]+$i+1,$tplTemp);
            }
		}


		if($_GET[$this->getVariableName] != $numPages)
		{

            $tplTemp =  str_replace("{PAGE}",$_GET[$this->getVariableName]+1,$tpl);
            $html   .=  str_replace("{PAGENUM}","&raquo;",$tplTemp);

            $tplTemp =  str_replace("{PAGE}",$numPages,$tpl);
            $html   .=  str_replace("{PAGENUM}","&raquo;&raquo;",$tplTemp);
		}

        return $html;
  	}

	private function get_url($tplLink)
	{
		  $url = path::add_param($this->getVariableName . "={PAGE}");
		  return str_replace("{URL}", $url, $tplLink);
	}

 }
?>