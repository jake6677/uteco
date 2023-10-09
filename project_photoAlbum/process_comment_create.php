<?php
$conn=mysqli_connect("localhost:81","*","*","project_memories");
$filtered = array(
    'image_id'=>mysqli_real_escape_string($conn, $_POST['image_id']),
    'comment'=>mysqli_real_escape_string($conn, $_POST['comment']),
);

$sql = "INSERT INTO comment 
        (comment, created, image_id)
        VALUES(
        '{$filtered['comment']}',
        NOW(),
        '{$filtered['image_id']}'
        )
";

$result = mysqli_query($conn, $sql);
if($result == false){
    echo "저장하는 과정에서 문제가 생겼읍니다.";
    error_log(mysqli_error($conn));  //에러값은 아파치 에러로그에 저장되게 하는 코드
} else {
    header("Location: image_display230906.php?id=".$filtered['image_id']);
}
?>