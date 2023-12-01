<?php
include_once '../API/connectdb.php';
$searchTerm = $_GET['search'];

// Write a query to fetch data based on $searchTerm.
// This is a simple LIKE query for demonstration. You may need to adjust based on your needs.
$query = "
  SELECT
      Product.pid,
      Product.barcode,
      Product.product,
      Category.category AS category,
      Product.description,
      ProductStock.id as stock_id,
      ProductStock.stock,
      ProductStock.purchaseprice,
      ProductStock.saleprice,
      ProductStock.ourprice
  FROM Product
  INNER JOIN Category ON Product.catid = Category.catid
  RIGHT JOIN Product_Stock AS ProductStock ON Product.pid = ProductStock.pid
  WHERE (Product.product LIKE :searchTerm OR category LIKE :searchTerm OR Product.description LIKE :searchTerm OR Product.barcode LIKE :searchTerm)
    AND ProductStock.saleprice != ProductStock.ourprice

  ORDER BY Product.pid ASC
";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':searchTerm', '%' . $searchTerm . '%');
$stmt->execute();

$results = $stmt->fetchAll(PDO::FETCH_OBJ);

// Return the results as JSON
echo json_encode($results);
?>
