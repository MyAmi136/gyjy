<?php

require_once "model/Database.php";
require_once "model/Product.php";

class ProductController {

    public function index(){

        $database = new Database();
        $db = $database->connect();

        $product = new Product($db);

        $products = $product->getAll();

        require "view/products.php";
    }

     public function detail($id = null){

        $id = $id ?? ($_GET['id'] ?? null);
        if(!is_scalar($id) || filter_var($id, FILTER_VALIDATE_INT) === false || (int) $id < 1){
            die("Product not found");
        }

        $id = (int) $id;

        $productModel = new Product();
        $product = $productModel->getById($id);

        // ✅ Get product images
        $images = !empty($product) ? $productModel->getAllImages($id) : [];

        $guidance = [
            'title' => 'Gợi ý sử dụng',
            'fit' => 'Tham khảo công dụng được mô tả và lựa chọn sản phẩm phù hợp với nhu cầu cá nhân.',
            'steps' => [
                'Đọc hướng dẫn sử dụng trên bao bì trước khi dùng.',
                'Dùng lượng vừa đủ và điều chỉnh theo nhu cầu.',
                'Ngưng sử dụng nếu có dấu hiệu kích ứng.'
            ],
            'note' => 'Thông tin trên trang mang tính tham khảo. Vui lòng ưu tiên hướng dẫn của nhà sản xuất.'
        ];

        if($product){
            $category = (string) ($product['category_name'] ?? '');
            $name = strtolower((string) ($product['product_name'] ?? ''));

            if($category === 'Chống nắng' || strpos($name, 'chống nắng') !== false){
                $guidance = [
                    'title' => 'Gợi ý sử dụng kem chống nắng',
                    'fit' => 'Phù hợp để bổ sung vào quy trình chăm sóc da ban ngày.',
                    'steps' => [
                        'Thoa đều lên vùng da cần bảo vệ trước khi ra ngoài.',
                        'Dùng lại theo hướng dẫn trên bao bì, đặc biệt sau khi đổ mồ hôi hoặc tiếp xúc với nước.',
                        'Kết hợp thêm các biện pháp che chắn phù hợp khi ở ngoài trời.'
                    ],
                    'note' => 'Mức độ bảo vệ và cách dùng cụ thể phụ thuộc vào nhãn sản phẩm. Tránh để sản phẩm tiếp xúc trực tiếp với mắt.'
                ];
            } elseif($category === 'Sữa rửa mặt' || strpos($name, 'sữa rửa mặt') !== false || strpos($name, 'gel rửa mặt') !== false){
                $guidance = [
                    'title' => 'Gợi ý sử dụng sản phẩm làm sạch',
                    'fit' => 'Dùng ở bước làm sạch da trong quy trình chăm sóc hằng ngày.',
                    'steps' => [
                        'Làm ướt da mặt bằng nước sạch.',
                        'Lấy lượng vừa đủ, tạo bọt nếu sản phẩm phù hợp rồi massage nhẹ nhàng.',
                        'Rửa sạch lại với nước và tiếp tục các bước dưỡng da.'
                    ],
                    'note' => 'Tránh chà xát mạnh. Nếu da khô căng hoặc khó chịu, hãy giảm tần suất hoặc ngưng dùng.'
                ];
            } elseif($category === 'Skincare' && strpos($name, 'toner') !== false){
                $guidance = [
                    'title' => 'Gợi ý sử dụng toner',
                    'fit' => 'Dùng sau bước làm sạch và trước serum hoặc kem dưỡng.',
                    'steps' => [
                        'Làm sạch da mặt và để da ráo nhẹ.',
                        'Cho toner ra tay sạch hoặc bông tẩy trang theo hướng dẫn của sản phẩm.',
                        'Thoa nhẹ lên da rồi tiếp tục các bước dưỡng tiếp theo.'
                    ],
                    'note' => 'Tránh vùng mắt và ngưng sử dụng nếu da có biểu hiện kích ứng.'
                ];
            } elseif(strpos($name, 'tẩy trang') !== false){
                $guidance = [
                    'title' => 'Gợi ý sử dụng sản phẩm tẩy trang',
                    'fit' => 'Dùng để làm sạch lớp trang điểm và bụi bẩn trước bước rửa mặt.',
                    'steps' => [
                        'Thấm sản phẩm ra bông tẩy trang nếu hướng dẫn yêu cầu.',
                        'Lau nhẹ nhàng, không miết mạnh trên da hoặc vùng mắt.',
                        'Rửa lại hoặc tiếp tục sữa rửa mặt theo hướng dẫn trên bao bì.'
                    ],
                    'note' => 'Với sản phẩm dùng quanh mắt, hãy kiểm tra hướng dẫn và tránh để sản phẩm lọt vào mắt.'
                ];
            } elseif($category === 'Serum & Essence' || strpos($name, 'serum') !== false || strpos($name, 'tinh chất') !== false){
                $guidance = [
                    'title' => 'Gợi ý sử dụng serum và tinh chất',
                    'fit' => 'Có thể dùng sau bước làm sạch và cân bằng da, trước kem dưỡng.',
                    'steps' => [
                        'Làm sạch da và để da ráo nhẹ.',
                        'Thoa một lượng vừa đủ, tán nhẹ trên da theo hướng dẫn của sản phẩm.',
                        'Tiếp tục bằng kem dưỡng; ban ngày nên dùng kem chống nắng.'
                    ],
                    'note' => 'Nếu lần đầu sử dụng, hãy thử trên một vùng da nhỏ và không kết hợp tùy tiện với hoạt chất khác.'
                ];
            } elseif($category === 'Mặt nạ' || strpos($name, 'mặt nạ') !== false){
                $guidance = [
                    'title' => 'Gợi ý sử dụng mặt nạ',
                    'fit' => 'Dùng như một bước chăm sóc bổ sung, không thay thế các bước làm sạch và dưỡng da cơ bản.',
                    'steps' => [
                        'Làm sạch da trước khi sử dụng.',
                        'Đắp hoặc thoa mặt nạ theo đúng thời gian ghi trên bao bì.',
                        'Làm sạch hoặc tiếp tục dưỡng da tùy theo hướng dẫn của từng loại mặt nạ.'
                    ],
                    'note' => 'Không để mặt nạ trên da lâu hơn thời gian khuyến nghị. Ngưng dùng nếu xuất hiện kích ứng.'
                ];
            } elseif($category === 'Dưỡng cơ thể' || strpos($name, 'toàn thân') !== false || strpos($name, 'dưỡng thể') !== false || strpos($name, 'da tay') !== false){
                $guidance = [
                    'title' => 'Gợi ý chăm sóc da cơ thể',
                    'fit' => 'Dành cho quy trình chăm sóc da cơ thể hoặc da tay tùy theo hướng dẫn của sản phẩm.',
                    'steps' => [
                        'Làm sạch và lau khô vùng da cần chăm sóc.',
                        'Thoa lượng vừa đủ, massage nhẹ nhàng cho đến khi sản phẩm dàn đều.',
                        'Với sản phẩm tẩy tế bào chết, chỉ dùng theo tần suất ghi trên bao bì.'
                    ],
                    'note' => 'Không sử dụng sản phẩm tẩy tế bào chết trên vùng da trầy xước hoặc đang kích ứng.'
                ];
            } elseif($category === 'Kem dưỡng da' || strpos($name, 'kem dưỡng') !== false){
                $guidance = [
                    'title' => 'Gợi ý sử dụng kem dưỡng',
                    'fit' => 'Dùng ở bước dưỡng ẩm cuối quy trình chăm sóc da, theo thời điểm phù hợp với sản phẩm.',
                    'steps' => [
                        'Làm sạch da và hoàn thành các bước tinh chất nếu có.',
                        'Lấy lượng kem vừa đủ, thoa đều lên da.',
                        'Dùng đều đặn theo hướng dẫn; ban ngày kết hợp kem chống nắng.'
                    ],
                    'note' => 'Thử sản phẩm trên vùng da nhỏ trước khi dùng toàn mặt nếu bạn có làn da nhạy cảm.'
                ];
            } elseif(
                $category === 'Son môi' ||
                $category === 'Phấn nền' ||
                $category === 'Mascara' ||
                $category === 'Phấn má hồng' ||
                strpos($name, 'son ') !== false ||
                strpos($name, 'mascara') !== false ||
                strpos($name, 'phấn') !== false ||
                strpos($name, 'cushion') !== false ||
                strpos($name, 'kem nền') !== false
            ){
                $guidance = [
                    'title' => 'Gợi ý sử dụng sản phẩm trang điểm',
                    'fit' => 'Có thể kết hợp vào các bước trang điểm tùy theo công dụng và sắc độ của sản phẩm.',
                    'steps' => [
                        'Chuẩn bị da sạch và đủ ẩm trước khi trang điểm.',
                        'Dùng dụng cụ phù hợp để lấy và tán sản phẩm từng lớp mỏng.',
                        'Tẩy trang và làm sạch da sau khi trang điểm.'
                    ],
                    'note' => 'Màu sắc thực tế có thể khác đôi chút tùy ánh sáng và màn hình. Đọc kỹ hướng dẫn, nhất là với sản phẩm dùng quanh mắt.'
                ];
            }
        }

        require "view/product_detail.php";
    }

}