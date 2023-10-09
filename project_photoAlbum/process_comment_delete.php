<?php
$conn=mysqli_connect("localhost:81","*","*","project_memories");

settype($_POST['id'], "integer");
$filtered = array(
    'id'=>mysqli_real_escape_string($conn, $_POST['id']),
    'page_id'=>mysqli_real_escape_string($conn, $_POST['page_id'])
);
$sql = "DELETE FROM comment
        WHERE id = {$filtered['id']};
";
$result = mysqli_query($conn, $sql);
if($result == false){
    echo "삭제하는 과정에서 문제가 생겼읍니다.";
    error_log(mysqli_error($conn)); 
} else {
    header("Location: image_display230906.php?id=".$filtered['page_id']);
}
?>