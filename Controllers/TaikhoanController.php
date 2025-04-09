<?php
class TaikhoanController
{
    public $modelSanPham;
    public $modelTaiKhoan;
    public $modelDanhMuc;


    public function __construct()
    {
        $this->modelSanPham = new SanPham();
        $this->modelTaiKhoan = new TaiKhoan();
    }

    public function formLogin()
    {
        require_once "./Views/auth/formLogin.php";
        deleteSessionErrors();
    }

    public function postLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['mat_khau'];

            $errors = [];
            if (empty($email)) {
                $errors['email'] = 'Email không được để trống';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Email không hợp lệ';
            }
            if (empty($password)) {
                $errors['mat_khau'] = 'Mật khẩu không được để trống';
            } elseif (strlen($password) < 6) {
                $errors['mat_khau'] = 'Mật khẩu phải có ít nhất 6 ký tự';
            }

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $_SESSION['flash'] = true;
                header("Location:" . BASE_URL . '?act=form-login');
                exit();
            };

            // var_dump($result);
            // die();
            $result = $this->modelTaiKhoan->checkLogin($email, $password);
            if (is_array($result)) {
                $email = $result['email'];
                $id = $result['id'];
                $infor = $this->modelTaiKhoan->getTaiKhoanFromEmail($email);
                $ho_ten = $infor['ho_ten'];
                // var_dump($infor);
                // die;
                $_SESSION['user_client'] = [
                    'ho_ten' => $ho_ten,
                    'email' => $email,
                    'id' => $id
                ];

                $_SESSION['alert'] = [
                    'title' => 'Success',
                    'message' => 'Đăng nhập thành công!',
                    'type' => 'success',
                    'redirect' => BASE_URL,
                ];
                // var_dump($_SESSION['alert']);
                // die;

                showAlert();
                exit();
            } else {
                $_SESSION['flash'] = true;
                $_SESSION['alert'] = [
                    'title' => 'Lỗi',
                    'message' => $result,
                    'type' => 'error',
                    'redirect' =>  BASE_URL . '?act=form-login',
                ];
                showAlert();
                exit();
            }
        }
    }

    public function logout()
    {
        if (isset($_SESSION['user_client'])) {
            unset($_SESSION['user_client']);
            unset($_SESSION['products-cart']);

            $_SESSION['alert'] = [
                'title' => 'Success',
                'message' => 'Đăng xuất thành công!',
                'type' => 'success',
                'redirect' => BASE_URL,
            ];
            showAlert();
            exit();
        }
    }

    public function formRegister()
    {
        require_once "./Views/auth/formRegister.php";
        deleteSessionErrors();
    }

    public function postRegister()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Lấy ra dl
            $ho_ten = $_POST['ho_ten'] ?? '';
            $email = $_POST['email'] ?? '';
            $mat_khau = $_POST['mat_khau'] ?? '';
            $chuc_vu = 2;

            $checkEmailExist = $this->modelTaiKhoan->checkEmailExists($email);
            // var_dump($checkEmailExist);
            // die;
            $errors = [];
            if ($checkEmailExist) {
                $errors['email'] = 'Email đã tồn tại';
            }
            if (empty($ho_ten)) {
                $errors['ho_ten'] = 'Họ tên không được để trống';
            }
            if (empty($email)) {
                $errors['email'] = 'Email không được để trống';
            }
            if (empty($mat_khau)) {
                $errors['mat_khau'] = 'Mật khẩu không được để trống';
            }
            $_SESSION['errors'] = $errors;



            if (empty($errors)) {
                $tai_khoan = $this->modelTaiKhoan->insertTaiKhoan($ho_ten, $email, $mat_khau, $chuc_vu);
                // var_dump($tai_khoan);
                // die();
                $_SESSION['alert'] = [
                    'title' => 'Success',
                    'message' => 'Đăng kí tài khoản thành công',
                    'type' => 'success',
                    'redirect' => BASE_URL,
                ];
                showAlert();
                exit();
            } else {
                $_SESSION['flash'] = true;
                $_SESSION['thongBao'] = 'Đăng ký thất bại';

                header("Location: " . BASE_URL . '?act=form-register');
                exit();
            }
        }
    }

    
}
