<?php

class AdminTaiKhoanController
{
    public $modelTaiKhoan;
    public $modelDonHang;
    public $modelSanPham;

    public function __construct()
    {
        $this->modelTaiKhoan = new AdminTaiKhoan();
        // $this->modelDonHang = new AdminDonHang();
        $this->modelSanPham = new AdminSanPham();
    }
    public function formLogin()
    {
        if (isset($_SESSION['user_admin'])) {
            header('Location:' . BASE_URL_ADMIN);
            exit();
        }
        require_once './Views/auth/formLogin.php';
        deleteSessionErrors();
    }
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // xử lý kiểm tra thông tin đăng nhập
            $user = $this->modelTaiKhoan->checkLogin($email, $password);
            // var_dump($user);
            // die;
            if ($user[0] == $email) {
                // dn thanh cong
                // Luu thong tin vao session
                $_SESSION['user_admin'] = $user;


                $_SESSION['alert'] = [
                    'title' => 'Success',
                    'message' => 'Đăng nhập thành công!',
                    'type' => 'success',
                    'redirect' => BASE_URL_ADMIN,
                ];
                showAlert();
                exit();
            } else {

                $_SESSION['flash'] = true;
                $_SESSION['alert'] = [
                    'title' => 'Success',
                    'message' => $user,
                    'type' => 'error',
                    'redirect' => BASE_URL_ADMIN . '?act=login-admin',
                ];
                showAlert();
                exit();
            }
        }
    }
    public function logout()
    {
        if (isset($_SESSION['user_admin'])) {
            unset($_SESSION['user_admin']);
            $_SESSION['alert'] = [
                'title' => 'Success',
                'message' => 'Đăng xuất tài khoản thành công!',
                'type' => 'success',
                'redirect' => BASE_URL_ADMIN . '?act=view-logout',
            ];
            showAlert();
            exit();
        }
    }

    public function tabLogout()
    {
        require_once './Views/auth/viewLogout.php';
    }
    
}
