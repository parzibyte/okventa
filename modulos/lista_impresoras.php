<?php
$ruta_powershel = 'c:\Windows\System32\WindowsPowerShell\v1.0\powershell.exe'; #Necesitamos el powershell
$opciones_para_ejecutar_comando = "-c";#Ejecutamos el powershell y necesitamos el "-c" para decirle que ejecutaremos un comando
$espacio = " "; #ayudante para concatenar
$comillas = '"'; #ayudante para concatenar
$comando = 'get-WmiObject -class Win32_printer |ft shared, name'; #Comando de powershell para obtener lista de impresoras
$delimitador = "True"; #Queremos solamente aquellas en donde la línea comienza con "True"
$lista_de_impresoras = array(); #Aquí pondremos las impresoras
$impresorasxdxd = exec(
    $ruta_powershel
    . $espacio
    . $opciones_para_ejecutar_comando
    . $espacio
    . $comillas
    . $comando
    . $comillas,
    $resultado,
    $codigo_salida);
if ($codigo_salida === 0) {
    if (is_array($resultado)) {
        foreach ($resultado as $linea) {
            $linea = trim($linea);
            if (strlen($linea) > 0) {
                if (strpos($linea, $delimitador) === 0) {
                    $nombre_impresora = substr($linea, strlen($delimitador) + 1, strlen($linea) - strlen($delimitador) + 1);
                    array_push($lista_de_impresoras, $nombre_impresora);
                }
            }
        }
    }
    echo json_encode($lista_de_impresoras);
} else {
    echo json_encode("Error al ejecutar el comando.");
}