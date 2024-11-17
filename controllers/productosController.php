<?php

require '../model/database.php';
class ProductosController {
  private $conexion;

  function __construct(){
    $database = new Database();//esto es un objeto de la clase Database;
    $this->conexion = $database->getConexion();

  }
    function CreateProducto(){
      $data = json_decode(file_get_contents('php://input'),true);
      $nombre_producto = $data['nombre_producto'] ?? null;
      $precio = $data['precio'] ?? null;
      $cantidad = $data['cantidad'] ?? null;
      $marca = $data['marca'] ?? null;
 
      if (!$nombre_producto || !$precio || !$cantidad || !$marca) {
          echo json_encode(["status" => "error", "message" => "Datos incompletos"]);
          http_response_code(400);
          return;
      }

        $sql = "INSERT INTO productos(nombre_producto,precio,cantidad,marca) VALUES(:nombre,:precio,:cantidad,:marca)";
        $declaracion = $this->conexion->prepare($sql);
        $declaracion->bindParam("nombre", $nombre_producto);
        $declaracion->bindParam("precio", $precio);
        $declaracion->bindParam("cantidad", $cantidad);
        $declaracion->bindParam("marca", $marca);

        if($declaracion->execute()){
            echo json_encode(array("status"=> "success","message"=> "Producto añadido"));
            http_response_code(200);
        }else {
            echo json_encode(array("status"=> "error","message"=> "No se pudo añadir el producto"));
            http_response_code(500);
        }                    

    }


    function getProductos():array{
      $sql = "SELECT * FROM productos";
      $stmt = $this->conexion->prepare($sql);
      $stmt->execute();               //PDO objetos de datos de php
      $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);//el fetchAll obtiene todos los datos en un array asociativo
      echo json_encode($productos);
       return $productos;
    }



    //funcion para actualizar el producto

    function updateProducto(){
      $data = json_decode(file_get_contents('php://input'), true);
      
      $id_producto = $data['id_producto'] ?? null;
      $nombre_producto = $data['nombre_producto'] ?? null;
      $precio = $data['precio'] ?? null;
      $cantidad = $data['cantidad'] ?? null;
      $marca = $data['marca'] ?? null;
 
      if (!$id_producto || !$nombre_producto || !$precio || !$cantidad || !$marca) {
          echo json_encode(["status" => "error", "message" => "Datos incompletos"]);
          http_response_code(400);
          return;
      }
 
      $sql = "UPDATE productos SET nombre_producto = :nombre_producto, precio = :precio, cantidad = :cantidad, marca = :marca WHERE id_producto = :id_producto";
      
      try {
          $stmt = $this->conexion->prepare($sql);
          $stmt->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
          $stmt->bindParam(":nombre_producto", $nombre_producto, PDO::PARAM_STR);
          $stmt->bindParam(":precio", $precio, PDO::PARAM_STR); 
          $stmt->bindParam(":cantidad", $cantidad, PDO::PARAM_INT);
          $stmt->bindParam(":marca", $marca, PDO::PARAM_INT);
          
          if ($stmt->execute()) {
              echo json_encode(["status" => "success", "message" => "Producto actualizado correctamente"]);
              http_response_code(200);
          } else {
              echo json_encode(["status" => "error", "message" => "No se pudo actualizar el producto"]);
              http_response_code(500);
          }
      } catch (PDOException $e) {
          error_log("Error en updateProducto: " . $e->getMessage());
          echo json_encode(["status" => "error", "message" => "Error en la base de datos"]);
          http_response_code(500);
      }
 }
 






    function deleteProducto(int $id){
      $sql = "DELETE FROM productos WHERE id_producto = :id";
      $stmt = $this->conexion->prepare($sql);
      $stmt->bindParam(":id", $id,PDO::PARAM_INT);
      if($stmt->execute()){
        echo json_encode(array("status"=> "success","message"=> "Producto borrado exitosamente"));
        http_response_code(200);
      }
      else {
        echo json_encode((["status"=> "error","message"=> "No se pudo borrar el producto"]));
        http_response_code(500);
      }  
    }
   
      
  }