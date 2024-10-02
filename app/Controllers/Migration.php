<?php

use App\Models\Base;
use App\Models\Migration;
$m_table = (new Migration)->getTable();
list($s,$t) = explode('.',$m_table);
$resp =  Base::$DB->query("
    SELECT EXISTS (
    SELECT FROM information_schema.tables 
    WHERE  table_schema = '$s' AND table_name   = '$t'
   )")->fetch(PDO::FETCH_ASSOC);
if(empty($resp['exists'])){
    Base::$DB->query("CREATE TABLE $m_table (
        id serial NOT NULL,
        \"name\" varchar(255) NOT NULL
    )");
}
$m = new Migration;
$list = [];
foreach($m->query("SELECT * FROM $m_table ORDER BY id")->fetchAll() as $row){
    $list[] = $row['name'];
}
$dir = PATH_APP.'migrations/';
foreach(scandir($dir) as $mig){
    if($mig[0] == '.' || in_array($mig, $list)) continue;
    echo "<br>".$mig;
    try {
        Migration::beginTransaction();
        (include $dir.$mig)->up();
        $m->insert(['name'=>$mig]);
        Migration::commit();
    } catch (\Throwable $th) {
        Migration::rollBack();
        echo "<br>".$th->getMessage().'<br>';
    }
}