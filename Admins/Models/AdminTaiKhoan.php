<?php

class AdminTaiKhoan
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }
    public function checkLogin($email, $mat_khau)
    {
        try {
            $sql = " SELECT * FROM tai_khoans WHERE email=:email";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(
                [
                    ':email' => $email,
                ]
            );
            $user = $stmt->fetch();

            // var_dump($user);
            // die;

            if ($user && password_verify($mat_khau, $user['mat_khau'])) {
                if ($user['chuc_vu_id'] == 1) { //ADMIN
                    if ($user['trang_thai'] == 1) {
                        return [
                            $user['email'],
                            $user['ho_ten']
                        ]; // thanh cong
                    } else {
                        return "Tài khoản bị cấm";
                    }
                }
            } elseif ($user && $mat_khau == $user['mat_khau']) {
                if ($user['chuc_vu_id'] == 2) { // KHACH HANG
                    return "Tài khoản không có quyền đăng nhập admin";
                }
            } else {
                return 'Vui lòng kiểm tra lại thông tin đăng nhập';
            }
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
            return  false;
        }
    }
    public function checkDangNhapAdmin($email, $mat_khau)
    {
        try {
            $sql = "SELECT * FROM tai_khoans WHERE email = :email AND mat_khau = :mat_khau";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':email' => $email,
                ':mat_khau' => $mat_khau

            ]);

            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
        }
    }
    public function resetPassword($id, $mat_khau)
    {

        try {
            $sql = "UPDATE tai_khoans 
                    SET 
                    mat_khau = :mat_khau
                
                    WHERE id = :id

                                        ";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(
                [
                    ':mat_khau' => $mat_khau,

                    ':id' => $id
                ]
            );

            // Lấy id sản phẩm vừa thêm
            return true;
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
        }
    }
}
