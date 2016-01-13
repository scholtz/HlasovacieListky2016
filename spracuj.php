<?php

$handle = @fopen("source.txt", "r");
$n = 0;
$nn = 0;
$lines =array();
if ($handle) {
    while (($line = fgets($handle, 4096)) !== false) {
		$line = trim($line);
		if(strpos($line,"Číslo kandidátnej listiny") !== false){
			$cislostrany = trim(substr($line,strlen("Číslo kandidátnej listiny:")));
			$nazovstrany = trim(fgets($handle, 4096));
			$nn = 0;
		}else{
			if(is_numeric(substr($line,0,1))){
				$n++;
				$nn ++;
				$cislokandidata = substr($line,0,strpos($line,"."));
				if($cislokandidata != $nn){
					var_dump($nn);
					var_dump($cislostrany);
					exit;
				}
				
				$lines[$n] = $cislostrany.",".$nazovstrany.",".$cislokandidata.",".trim(substr($line,strlen($cislokandidata)+1));
			}else{
				$lines[$n] .= $line;
				//var_dump($lines);exit;
			}
		}
		
	}
}
file_put_contents("out01.txt",implode("\n",$lines));

	$fp = fopen('out.csv', 'w');
	
	$fields = array();
	$fields[] = "cislo strany";// cislo strany
	$fields[] = "nazov strany";// nazov strany
	$fields[] = "cislo kandidata";// cislo kandidata

	$fields[] = "meno";// menokandidata
	$fields[] = "priezvisko";// menokandidata
	$fields[] = "tituly";// tituly oddelene bodkociarkou
	$fields[] = "vek";// vek v rokoch
	$fields[] = "povolania";// prace oddelene bodkociarkou
	$fields[] = "mesto";// mesto

	
	fputcsv($fp, $fields);

foreach($lines as $line){
	$data = explode(",",$line);
	foreach($data as $k=>$v){
		$data[$k] = trim($v);
	}
	$vekcol = false;
	foreach($data as $k=>$v){
		if(substr($v,-2) == "r."){
			$vekcol = $k;
			$vek = trim(substr($v,0,-2));
		}
	}
	
	if(!$vekcol){
		var_dump($data);
		exit;
	}


	$meno = substr($data[3],0,$pos = strpos($data[3]," "));
	$priezvisko = substr($data[3],$pos+1);
	if(!$priezvisko) {
		var_dump($data);
		exit;
	}
	$titul = "";
	for($i=4;$i<$vekcol;$i++){
		if($titul) $titul.=";";
		$titul.=$data[$i];
	}
	$praca = "";
	for($i=$vekcol+1;$i<count($data)-1;$i++){
		if($praca) $praca.=";";
		$praca.=$data[$i];
	}
	
	$fields = array();
	$fields[] = $data[0];// cislo strany
	$fields[] = $data[1];// nazov strany
	$fields[] = $data[2];// cislo kandidata

	$fields[] = $meno;// menokandidata
	$fields[] = $priezvisko;// menokandidata
	$fields[] = $titul;// tituly oddelene bodkociarkou
	$fields[] = $vek;// vek v rokoch
	$fields[] = $praca;// prace oddelene bodkociarkou
	$fields[] = $data[count($data)-1];// mesto

	
	fputcsv($fp, $fields);
	
}



