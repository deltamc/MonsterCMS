<?

$conf = $this->conf['widgets']['images'];

$sql = "SELECT ".$conf['dbRow']." FROM `".$conf['dbTable']."` WHERE `".$conf['dbId_group']."`=?";

$result = $this->db->query($sql, $id);

while($row = $this->db->fetchArray($result))
{
    @unlink($conf['path'].'/'. $row[0]);
}

$sql = "DELETE FROM `".$conf['dbTable']."` WHERE `".$conf['dbId_group']."`=?";
$result = $this->db->query($sql, $id);

?>