<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda</title>
</head>
<body>
<form method="post">
    <table>
        <tr>
            <td colspan="2"><h1>Eliga que parametros quiere meter:</h1></td>
        </tr>
        <tr>
            <td><label>Nombre: </label></td>
            <td><input type="text" name="nombre"></td>
        </tr>
        <tr>
            <td><label>Direccion de correo electronico: </label></td>
            <td><input type="text" name="direccion"></td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" value="Enviar"></td>
        </tr>
    </table>

<?php
// clase agenda
class Agenda{
    //creando el array asociativo
    private $persona=[];
    public function __construct() {
        
    }
    // este metodo sirve para saber si el nombre ya existe
    public function nombre_existente($nombre){
        if(array_key_exists($nombre,$this->persona)){
            return true;
        }
        return false;
    }
    // este metodo sirve para formatear el nombre,es decir,mayusculas y tildes fuera.
    public function formatear_nombre($nombre){
        $nombre=strtolower($nombre);
        $nombre = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $nombre
        );
    
        $nombre = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $nombre );
    
        $nombre = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $nombre );
    
        $nombre = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $nombre );
    
        $nombre = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $nombre );
    
        $nombre = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C'),
            $nombre
        );
        return $nombre;
    }
    //este metodo detecta si el nombre esta vacio
    public function nombre_vacia($nombre){
        if($nombre==""){
            return true;
        }
        return false;
    }
    // este metodo detecta por el solo si se ha introducido un gmail correcto
    public function validar_direccion($direccion){
       if(filter_var($direccion, FILTER_VALIDATE_EMAIL)){
            return true;
        }
        return false;
    }
    // este metodo sirve para saber si el atributo direccion esta vacio o no
    public function direccion_vacia($direccion){
        if($direccion==""){
            return true;
        }
        return false;
    }
    // el funcionamiento de este metodo es recoger el array asociativo
    public function getPersonas(){
        return $this->persona;
    }
    // este metodo añade los atributos a la agenda con sus diferentes casos
    public function añadir_persona($nombre,$direccion){
        $nombre_formateado=$this->formatear_nombre($nombre);
        if($this->nombre_vacia($nombre_formateado)){
            echo "<br>No ha introducido el nombre";
        }
        else if(!$this->nombre_existente($nombre_formateado) && $this->validar_direccion($direccion)){
            $this->persona[$nombre_formateado] = $direccion;
        }
        else if($this->nombre_existente($nombre_formateado) && $this->validar_direccion($direccion)){
            $this->persona[$nombre_formateado] = $direccion;
        }
        else if($this->nombre_existente($nombre_formateado) && $this->direccion_vacia($direccion)){
            unset($this->persona[$nombre_formateado]);
        }
        else{
            echo "<br>No se ha podidio meter el registro";
        }
    }
    // este metodo muestra por pantalla los atributos introducidos en agenda
    public function mostrar_persona(){
        $tabla="<table border='1'>";
        foreach($this->persona as $nombre=>$direccion){
            $tabla.="<tr><td>nombre: ".$nombre."</td><td> direccion: ".$direccion."</td></tr>" ;
        }
        $tabla.="</table>";
        return $tabla;
    }
}
// Cogemos los valores del array antes de ejecutar el input
$nombres=[];
$direcciones=[];
$Age=new Agenda();
if((isset($_POST["nombres"])) && (isset($_POST["direcciones"]))) {
    $nombres= explode(",", $_POST["nombres"]);
    $direcciones= explode(",",$_POST["direcciones"]);
    for($i=0;$i<count($nombres);$i++){
        if($i!=0){
          $Age->añadir_persona($nombres[$i],$direcciones[$i]);
        echo $nombres[$i];
        echo $direcciones[$i];  
        }
    }
}

// Actualizamos los arrays
if((isset($_POST["nombre"])) && (isset($_POST["direccion"]))) {
    $entradas;
    $nombre= $_POST["nombre"];
    $direccion= $_POST["direccion"];
    $Age->añadir_persona($nombre,$direccion);
    $entradas= $Age->getPersonas();
    foreach($entradas as $nombre=>$direccion){
        array_push($nombres,$nombre);
        array_push($direcciones,$direccion);
    }
}
echo $Age->mostrar_persona();
?>

<!-- creaccion de los input hidden -->
<input type="hidden" name="nombres" value="<?php if(!empty($nombres)) { echo implode(",", $nombres); } ?>">
<input type="hidden" name="direcciones" value="<?php if(!empty($nombres)) { echo implode(",",$direcciones); } ?>">
</form>
</body>
</html>