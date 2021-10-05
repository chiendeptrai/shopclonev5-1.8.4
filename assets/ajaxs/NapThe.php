<?php 
    require_once("../../config/config.php");
    require_once("../../config/function.php");

    $loaithe = check_string($_POST['loaithe']);
    $menhgia = check_string($_POST['menhgia']);
    $seri = check_string($_POST['seri']);
    $pin = check_string($_POST['pin']);

    if($CMSNT->site('api_card') == '')
    {
        msg_error2("Chức năng này đang được bảo trì");
    }
    if(empty($_SESSION['username']))
    {
        msg_error("Vui lòng đăng nhập ", BASE_URL(''), 2000);
    }
    if(empty($loaithe))
    {
        msg_error2("Vui lòng chọn loại thẻ");
    }
    if(empty($menhgia))
    {
        msg_error2("Vui lòng chọn mệnh giá");
    }
    if(empty($seri))
    {
        msg_error2("Vui lòng nhập seri thẻ");
    }
    if(empty($pin))
    {
        msg_error2("Vui lòng nhập mã thẻ");
    }
    if (strlen($seri) < 5 || strlen($pin) < 5)
    {
        msg_error2("Mã thẻ hoặc seri không đúng định dạng!");
    }
    $code = random('qwertyuiopasdfghjklzxcvbnm1234567890QWERTYUIOPASDFGHJKLZXCVBNM', 10);
    
    $data = card1s($code, $loaithe, $pin, $seri, $menhgia);

    if($data['status'] == 'success') {
        // Gửi thẻ lên hệ thống thành công
        /* THÊM NHẬT KÝ */
        $CMSNT->insert("logs", [
            'username'  => $getUser['username'],
            'content'   => 'Thực hiện nạp thẻ',
            'createdate'=> gettime(),
            'time'      => time()
        ]);
        $CMSNT->insert("cards", array(
            'code' => $code,
            'seri' => $seri,
            'pin'  => $pin,
            'loaithe' => $loaithe,
            'menhgia' => $menhgia,
            'thucnhan' => '0',
            'username' => $getUser['username'],
            'status' => 'xuly',
            'note' => '',
            'createdate' => gettime()
        ));
        msg_success("Gửi thẻ thành công, vui lòng đợi kết quả", "", 2000);
    }else{
        // Thất bại
        $message = $data['message'];
        msg_error2($message);
    }