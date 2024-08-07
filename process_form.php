<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = htmlspecialchars($_POST['nombre']);
    $email = htmlspecialchars($_POST['email']);
    $telefono = htmlspecialchars($_POST['telefono']);
    $departamento = htmlspecialchars($_POST['departamento']);
    $localidad = htmlspecialchars($_POST['localidad']);
    $productos = isset($_POST['productos']) ? implode(", ", $_POST['productos']) : '';
    $contacto = htmlspecialchars($_POST['contacto']);
    $mensaje = htmlspecialchars($_POST['mensaje']);
    $archivo_adjunto = $_FILES['archivo_adjunto'];

    $to = 'haudetfelipe@gmail.com'; // Cambia esto por la dirección de correo donde quieres recibir los datos
    $subject = 'Solicitud de Presupuesto';
    
    $body = "Nombre y Apellido: $nombre\n";
    $body .= "Email: $email\n";
    $body .= "Teléfono: $telefono\n";
    $body .= "Departamento: $departamento\n";
    $body .= "Localidad: $localidad\n";
    $body .= "Productos de interés: $productos\n";
    $body .= "Método de contacto preferido: $contacto\n";
    $body .= "Mensaje:\n$mensaje\n";
    
    $headers = "From: $email";

    // Manejo del archivo adjunto
    if ($archivo_adjunto['error'] == 0) {
        $file_tmp = $archivo_adjunto['tmp_name'];
        $file_name = $archivo_adjunto['name'];
        $file_size = $archivo_adjunto['size'];
        $file_type = $archivo_adjunto['type'];
        
        $handle = fopen($file_tmp, "rb");
        $content = fread($handle, filesize($file_tmp));
        fclose($handle);
        
        $content = chunk_split(base64_encode($content));
        $uid = md5(uniqid(time()));
        
        $headers .= "\r\nMIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"$uid\"\r\n\r\n";
        $headers .= "This is a multi-part message in MIME format.\r\n";
        $headers .= "--$uid\r\n";
        $headers .= "Content-type:text/plain; charset=iso-8859-1\r\n";
        $headers .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $headers .= "$body\r\n\r\n";
        $headers .= "--$uid\r\n";
        $headers .= "Content-Type: $file_type; name=\"$file_name\"\r\n";
        $headers .= "Content-Transfer-Encoding: base64\r\n";
        $headers .= "Content-Disposition: attachment; filename=\"$file_name\"\r\n\r\n";
        $headers .= "$content\r\n\r\n";
        $headers .= "--$uid--";
    } else {
        $headers .= "\r\nContent-Type: text/plain; charset=UTF-8\r\n";
    }

    if (mail($to, $subject, $body, $headers)) {
        echo "El formulario ha sido enviado con éxito.";
    } else {
        echo "Hubo un error al enviar el formulario. Por favor, inténtalo de nuevo.";
    }
}
?>