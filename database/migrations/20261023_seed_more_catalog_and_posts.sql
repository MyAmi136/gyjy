-- Seed thêm danh mục, sản phẩm và bài viết cho cửa hàng mỹ phẩm

INSERT INTO categories (category_name, parent_id)
SELECT 'Serum & Essence', c.category_id
FROM categories c
WHERE c.category_name = 'Skincare' AND c.parent_id IS NULL
UNION ALL
SELECT 'Mặt nạ', c.category_id
FROM categories c
WHERE c.category_name = 'Skincare' AND c.parent_id IS NULL
UNION ALL
SELECT 'Chống nắng', c.category_id
FROM categories c
WHERE c.category_name = 'Skincare' AND c.parent_id IS NULL
UNION ALL
SELECT 'Dưỡng cơ thể', c.category_id
FROM categories c
WHERE c.category_name = 'Skincare' AND c.parent_id IS NULL
UNION ALL
SELECT 'Phấn nền', c.category_id
FROM categories c
WHERE c.category_name = 'Makeup' AND c.parent_id IS NULL
UNION ALL
SELECT 'Mascara', c.category_id
FROM categories c
WHERE c.category_name = 'Makeup' AND c.parent_id IS NULL
UNION ALL
SELECT 'Phấn má hồng', c.category_id
FROM categories c
WHERE c.category_name = 'Makeup' AND c.parent_id IS NULL;

INSERT INTO products (product_name, description, price, stock, category_id, brand_id, is_best_seller, created_at, status, featured_image_url)
SELECT 'Kem dưỡng ẩm Laneige Water Bank', 'Dưỡng ẩm sâu, giữ da căng mịn suốt cả ngày.', 540000.00, 36,
       (SELECT c.category_id FROM categories c WHERE c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Skincare' AND parent_id IS NULL) ORDER BY c.category_id LIMIT 1 OFFSET 0),
       2, 1, NOW(), 1, 'img/waterbank.png'
UNION ALL
SELECT 'Serum Innisfree Green Tea', 'Serum cấp ẩm và làm dịu da sau mụn hiệu quả.', 420000.00, 42,
       (SELECT c.category_id FROM categories c WHERE c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Skincare' AND parent_id IS NULL) ORDER BY c.category_id LIMIT 1 OFFSET 2),
       1, 1, NOW(), 1, 'img/InnisfreeGreenTea.png'
UNION ALL
SELECT 'Mặt nạ ngủ Laneige', 'Mặt nạ ngủ dưỡng ẩm mạnh, da mềm mịn và sáng khỏe.', 390000.00, 28,
       (SELECT c.category_id FROM categories c WHERE c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Skincare' AND parent_id IS NULL) ORDER BY c.category_id LIMIT 1 OFFSET 3),
       2, 0, NOW(), 1, 'img/product-placeholder.svg'
UNION ALL
SELECT 'Kem chống nắng Garnier UV', 'Chống nắng phổ rộng, bảo vệ da khỏi tia UV và ánh sáng xanh.', 260000.00, 48,
       (SELECT c.category_id FROM categories c WHERE c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Skincare' AND parent_id IS NULL) ORDER BY c.category_id LIMIT 1 OFFSET 4),
       5, 0, NOW(), 1, 'img/product-placeholder.svg'
UNION ALL
SELECT 'Sữa dưỡng thể Vaseline Healthy', 'Dưỡng ẩm cơ thể, làm mềm da và giảm khô ráp nhanh.', 180000.00, 66,
       (SELECT c.category_id FROM categories c WHERE c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Skincare' AND parent_id IS NULL) ORDER BY c.category_id LIMIT 1 OFFSET 5),
       4, 0, NOW(), 1, 'img/product-placeholder.svg'
UNION ALL
SELECT 'Phấn nền Maybelline Fit Me', 'Phấn nền mỏng mịn, che khuyết điểm tự nhiên và bền màu.', 320000.00, 33,
       (SELECT c.category_id FROM categories c WHERE c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Makeup' AND parent_id IS NULL) ORDER BY c.category_id LIMIT 1 OFFSET 1),
       3, 1, NOW(), 1, 'img/product-placeholder.svg'
UNION ALL
SELECT 'Mascara L’Oreal Lash Paradise', 'Mascara volumizing, cong mi tự nhiên, nhìn mắt to hơn.', 290000.00, 40,
       (SELECT c.category_id FROM categories c WHERE c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Makeup' AND parent_id IS NULL) ORDER BY c.category_id LIMIT 1 OFFSET 2),
       4, 1, NOW(), 1, 'img/product-placeholder.svg'
UNION ALL
SELECT 'Phấn má hồng 3CE', 'Màu hồng tươi tự nhiên, dễ tán và lên màu đẹp.', 240000.00, 52,
       (SELECT c.category_id FROM categories c WHERE c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Makeup' AND parent_id IS NULL) ORDER BY c.category_id LIMIT 1 OFFSET 3),
       3, 0, NOW(), 1, 'img/product-placeholder.svg'
UNION ALL
SELECT 'Son môi Innisfree', 'Son môi dưỡng ẩm, màu sắc tự nhiên và lâu trôi.', 260000.00, 51,
       (SELECT c.category_id FROM categories c WHERE c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Makeup' AND parent_id IS NULL) ORDER BY c.category_id LIMIT 1 OFFSET 0),
       1, 1, NOW(), 1, 'img/SonTintInnisfree.png'
UNION ALL
SELECT 'Kem dưỡng da tay 55K', 'Dưỡng da tay mềm mịn, ngăn nứt nẻ hiệu quả.', 55000.00, 90,
       (SELECT c.category_id FROM categories c WHERE c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Skincare' AND parent_id IS NULL) ORDER BY c.category_id LIMIT 1 OFFSET 0),
       1, 0, NOW(), 1, 'img/kem_duong_da_tay_55k.jpg'
UNION ALL
SELECT 'Mặt nạ tẩy tế bào chết', 'Mặt nạ tẩy tế bào chết giúp da sáng mịn, mềm mại.', 210000.00, 44,
       (SELECT c.category_id FROM categories c WHERE c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Skincare' AND parent_id IS NULL) ORDER BY c.category_id LIMIT 1 OFFSET 3),
       4, 0, NOW(), 1, 'img/product-placeholder.svg';

INSERT INTO posts (title, content, image_url, author_id, created_at)
VALUES
('5 bước skincare mùa hè cho da dầu', 'Mùa hè thường khiến da dễ nổi dầu và kích ứng. Hãy bắt đầu với sữa rửa mặt dịu, toner cấp ẩm, serum niacinamide, kem dưỡng nhẹ và kem chống nắng 50+. Duy trì đều đặn sẽ giúp da khỏe và không bóng nhờn.', 'img/product-placeholder.svg', 1, NOW()),
('Cách chọn son môi phù hợp với sắc da', 'Nếu da bạn có tone sáng, nên ưu tiên son nude, hồng nhạt hoặc cam đào dịu. Da trung bình và tối nên thử son đỏ nâu, nâu đất hoặc hồng rượu để tạo điểm nhấn đẹp tự nhiên.', 'img/SonTintInnisfree.png', 1, NOW()),
('Routine makeup tối giản trong 10 phút', 'Một routine nhanh gọn cho ngày làm việc: sữa dưỡng, kem nền mỏng, phấn phủ, mascara và son môi nude. Cách này giúp bạn trông tươi tắn mà không cần quá nhiều lớp.', 'img/product-placeholder.svg', 1, NOW()),
('Top 5 serum nên dùng cho da khô', 'Da khô cần serum dưỡng ẩm và khóa nước. Chọn serum chứa hyaluronic acid, glycerin, panthenol hoặc ceramide để da đỡ khô ráp và căng chảy.', 'img/waterbank.png', 1, NOW()),
('Dưỡng da từ bên trong: ngủ đủ giấc và uống nước', 'Duy trì thói quen ngủ đủ 7–8 tiếng và uống nước đều đặn là cách đơn giản nhưng cực hiệu quả để da sáng và khỏe hơn. Đây là nền tảng quan trọng bên cạnh skincare.', 'img/InnisfreeGreenTea.png', 1, NOW()),
('Mẹo bảo vệ da khi đi ra nắng', 'Không chỉ cần kem chống nắng, bạn nên mang khẩu trang, đeo kính râm và hạn chế ra nắng quá 11h–15h. Dùng thêm mũ hoặc áo khoác để giảm tác nhân UV gây hại cho da.', 'img/product-placeholder.svg', 1, NOW());
