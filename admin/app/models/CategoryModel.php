<?php
class CategoryModel extends BaseModel
{
    const TableName = 'categories';  // Định nghĩa tên bảng mà lớp này sẽ tương tác

    // Phương thức này trả về ID của người dùng hiện tại từ session
    private function getUserId()
    {
        // Kiểm tra xem có tồn tại ID người dùng trong session hay không. Nếu có, trả về ID đó. Nếu không, trả về 1 (ID mặc định).
        return isset($_SESSION['auth_admin']['user_id']) ? $_SESSION['auth_admin']['user_id'] : 1;
    }

    // Phương thức này lấy tất cả danh mục từ bảng categories
    public function getCategories()
    {
        // Truy vấn SQL lấy tất cả danh mục, sắp xếp theo trạng thái (status) giảm dần và theo ngày tạo (created_at) giảm dần.
        $sql = "SELECT c.* FROM categories as c ORDER BY c.status DESC, c.created_at DESC";
        $result = $this->querySql($sql); // Thực thi truy vấn SQL
        if ($result) {
            // Nếu có kết quả, trả về danh sách các danh mục dưới dạng mảng
            $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $categories;
        }
        // Nếu không có kết quả, trả về mảng rỗng
        return [];
    }

    // Phương thức này lấy các danh mục có trạng thái "active" (status = 1)
    public function getCategoriesByStatusTrue()
    {
        // Truy vấn SQL lấy các danh mục có trạng thái = 1 (active), sắp xếp theo trạng thái và ngày tạo.
        $sql = "SELECT c.* FROM categories as c WHERE c.status = 1 ORDER BY c.status DESC, c.created_at DESC";
        $result = $this->querySql($sql);  // Thực thi truy vấn SQL
        if ($result) {
            // Nếu có kết quả, trả về danh sách các danh mục có trạng thái active dưới dạng mảng
            $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $categories;
        }
        // Nếu không có kết quả, trả về mảng rỗng
        return [];
    }

     // Phương thức này lấy thông tin của một danh mục cụ thể theo ID
    public function getCategory($id)
    {
        // Truy vấn SQL lấy thông tin danh mục theo category_id
        $sql = "SELECT c.* FROM categories as c WHERE c.category_id = '{$id}'";
        $result = $this->querySql($sql);
        // Nếu tìm thấy kết quả, trả về thông tin của danh mục dưới dạng mảng
        return mysqli_fetch_assoc($result);
    }

    // Phương thức này tìm kiếm danh mục theo tên
    public function searchCategories($name)
    {
        // Truy vấn SQL tìm các danh mục có tên chứa chuỗi $name (sử dụng LIKE để tìm kiếm gần đúng)
        $sql = "SELECT c.* FROM categories as c WHERE c.category_name LIKE '%{$name}'";
        $result = $this->querySql($sql);
        // Trả về danh mục tìm thấy dưới dạng mảng
        return mysqli_fetch_array($result);
    }

    // Phương thức này tạo một danh mục mới
    public function createCategory($data)
    {
        // Sử dụng phương thức create() từ lớp cha (BaseModel) để tạo một danh mục mới trong cơ sở dữ liệu
        return $this->create(self::TableName, $data);
    }

    // Phương thức này cập nhật thông tin một danh mục
    public function updateCategory($id, $data)
    {   
        // Lấy ID người dùng hiện tại để lưu vào trường "updated_by"
        $user_id = $this->getUserId();
        // Truy vấn SQL cập nhật thông tin danh mục dựa trên category_id, bao gồm cả trường "updated_by"
        $sql = "UPDATE " . self::TableName . " SET category_name = '{$data['category_name']}', updated_by={$user_id} WHERE category_id = '{$id}'";
        $result = $this->querySql($sql);
        // Trả về kết quả của truy vấn (thành công hay không)
        return $result;
    }

    // Phương thức này "xóa mềm" một danh mục (nghĩa là thay đổi trạng thái của danh mục thay vì xóa hoàn toàn)
    public function deleteCategory($id)
    {
        // Lấy ID người dùng hiện tại để lưu vào trường "deleted_by"
        $user_id = $this->getUserId();
        // Truy vấn SQL "xóa mềm" danh mục, thay đổi trạng thái từ 1 (hoạt động) thành 0 (vô hiệu hóa) và lưu ID người dùng vào trường "deleted_by"
        $sql = "UPDATE " . self::TableName . " SET status = NOT status, deleted_by={$user_id} WHERE category_id = '{$id}'";
        $result = $this->querySql($sql);
        return $result;
    }
}
