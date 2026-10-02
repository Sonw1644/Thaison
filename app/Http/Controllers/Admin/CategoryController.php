<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

/**
 * Class CategoryController
 * Controller chịu trách nhiệm xử lý toàn bộ các thao tác CRUD (Thêm, Xem, Sửa, Xóa) 
 * đối với Danh mục / Hạng mục gạch trong phân hệ Quản trị (Admin).
 */
class CategoryController extends Controller
{
    /**
     * Hiển thị danh sách các danh mục gạch (có hỗ trợ tìm kiếm và phân trang).
     * Route: GET /admin/categories
     * 
     * @param Request $request Chứa dữ liệu gửi lên từ form tìm kiếm (nếu có)
     * @return \Illuminate\View\View Trả về view admin.categories.index
     */
    public function index(Request $request)
    {
        // 1. Khởi tạo truy vấn danh mục:
        // - withCount('products'): tự động đếm số lượng sản phẩm gạch thuộc mỗi danh mục
        // - orderBy('sort_order', 'asc'): sắp xếp theo số thứ tự ưu tiên (nhỏ đứng trước)
        // - orderBy('id', 'asc'): nếu cùng thứ tự thì sắp xếp theo ID tăng dần
        $query = Category::withCount('products')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc');

        // 2. Kiểm tra nếu quản trị viên có nhập từ khóa tìm kiếm
        if ($request->filled('search')) {
            $search = $request->input('search');
            // Tìm các danh mục có tên hoặc mô tả chứa từ khóa tìm kiếm (so sánh LIKE)
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        // 3. Phân trang 20 bản ghi mỗi trang, withQueryString() giúp giữ lại từ khóa tìm kiếm khi bấm sang trang 2, 3...
        $categories = $query->paginate(20)->withQueryString();

        // 4. Trả về giao diện quản lý danh mục kèm danh sách danh mục $categories
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Xử lý lưu Danh mục mới vào cơ sở dữ liệu.
     * Route: POST /admin/categories
     * 
     * @param Request $request Dữ liệu submit từ form thêm danh mục
     * @return \Illuminate\Http\RedirectResponse Chuyển hướng về trang danh sách
     */
    public function store(Request $request)
    {
        // 1. Xác thực dữ liệu đầu vào (Validation) để tránh lỗi dữ liệu
        $validated = $request->validate([
            'name'        => 'required|string|max:255', // Bắt buộc nhập tên, tối đa 255 ký tự
            'description' => 'nullable|string',          // Mô tả có thể để trống
            'sort_order'  => 'nullable|integer',         // Thứ tự ưu tiên (phải là số nguyên)
            'status'      => 'nullable|boolean',         // Trạng thái hiển thị (true: hiện, false: ẩn)
        ]);

        // 2. Chuẩn bị mảng dữ liệu để chèn vào CSDL
        $data = [
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'sort_order'  => $validated['sort_order'] ?? 1,      // Mặc định là 1 nếu không nhập
            'status'      => $request->has('status') ? true : false, // Checkbox: nếu có chọn là true, không chọn là false
        ];

        // 3. Tạo bản ghi mới trong bảng categories qua Eloquent ORM
        Category::create($data);

        // 4. Chuyển hướng trở lại trang danh sách danh mục và đính kèm thông báo thành công
        return redirect()->route('admin.categories.index')
                         ->with('success', 'Thêm mới hạng mục gạch thành công!');
    }

    /**
     * Lấy thông tin chi tiết của một danh mục để chỉnh sửa.
     * Route: GET /admin/categories/{category}/edit
     * 
     * @param Category $category Tự động binding model Category theo ID từ URL
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View Trả về JSON nếu gọi AJAX, ngược lại trả về View
     */
    public function edit(Category $category)
    {
        // Nếu yêu cầu được gửi qua AJAX hoặc trình duyệt yêu cầu định dạng JSON (dùng khi mở popup Modal sửa nhanh)
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json($category);
        }

        // Nếu truy cập bình thường bằng URL, trả về view chỉnh sửa riêng biệt
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Cập nhật thông tin danh mục đã chỉnh sửa vào CSDL.
     * Route: PUT/PATCH /admin/categories/{category}
     * 
     * @param Request $request Dữ liệu từ form sửa gửi lên
     * @param Category $category Model danh mục cần cập nhật
     * @return \Illuminate\Http\RedirectResponse Chuyển hướng về trang danh sách
     */
    public function update(Request $request, Category $category)
    {
        // 1. Xác thực dữ liệu cập nhật
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order'  => 'nullable|integer',
            'status'      => 'nullable|boolean',
        ]);

        // 2. Chuẩn bị mảng dữ liệu mới
        $data = [
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'sort_order'  => $validated['sort_order'] ?? 1,
            'status'      => $request->has('status') ? true : false,
        ];

        // 3. Thực hiện cập nhật vào CSDL cho bản ghi tương ứng
        $category->update($data);

        // 4. Chuyển hướng về danh sách và thông báo cập nhật thành công
        return redirect()->route('admin.categories.index')
                         ->with('success', 'Cập nhật hạng mục gạch thành công!');
    }

    /**
     * Xóa một danh mục khỏi cơ sở dữ liệu.
     * Route: DELETE /admin/categories/{category}
     * 
     * @param Category $category Model danh mục cần xóa
     * @return \Illuminate\Http\RedirectResponse Chuyển hướng về trang danh sách
     */
    public function destroy(Category $category)
    {
        // 1. Xóa bản ghi danh mục trong CSDL
        $category->delete();

        // 2. Chuyển hướng về trang danh sách kèm thông báo thành công
        return redirect()->route('admin.categories.index')
                         ->with('success', 'Đã xóa hạng mục gạch thành công!');
    }
}
