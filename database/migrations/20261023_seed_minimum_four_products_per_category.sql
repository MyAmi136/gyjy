SET NAMES utf8mb4;

-- Add products only while a category has fewer than four active products.
-- Re-running this migration does not duplicate products or exceed the target.

INSERT INTO products (product_name, description, price, stock, category_id, brand_id, is_best_seller, status, featured_image_url)
SELECT 'Sữa rửa mặt dịu nhẹ Skincare', 'Làm sạch bụi bẩn và bã nhờn, phù hợp dùng hằng ngày.', 185000.00, 35, c.category_id, 1, 0, 1, 'img/product-placeholder.svg'
FROM categories c
WHERE c.category_name = 'Skincare' AND c.parent_id IS NULL
  AND (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id AND p.status = 1) < 4
  AND NOT EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.category_id AND p.product_name = 'Sữa rửa mặt dịu nhẹ Skincare');

INSERT INTO products (product_name, description, price, stock, category_id, brand_id, is_best_seller, status, featured_image_url)
SELECT 'Toner cấp ẩm Skincare', 'Cân bằng độ ẩm sau khi rửa mặt, giúp da mềm mại hơn.', 220000.00, 30, c.category_id, 5, 0, 1, 'img/product-placeholder.svg'
FROM categories c
WHERE c.category_name = 'Skincare' AND c.parent_id IS NULL
  AND (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id AND p.status = 1) < 4
  AND NOT EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.category_id AND p.product_name = 'Toner cấp ẩm Skincare');

INSERT INTO products (product_name, description, price, stock, category_id, brand_id, is_best_seller, status, featured_image_url)
SELECT 'Kem nền Maybelline Fit Me Matte', 'Kem nền lì mịn, độ che phủ tự nhiên và lâu trôi.', 325000.00, 28, c.category_id, 3, 1, 1, 'img/product-placeholder.svg'
FROM categories c
WHERE c.category_name = 'Makeup' AND c.parent_id IS NULL
  AND (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id AND p.status = 1) < 4
  AND NOT EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.category_id AND p.product_name = 'Kem nền Maybelline Fit Me Matte');

INSERT INTO products (product_name, description, price, stock, category_id, brand_id, is_best_seller, status, featured_image_url)
SELECT 'Son dưỡng môi Innisfree', 'Son dưỡng mềm môi, bổ sung độ ẩm và tạo sắc môi tự nhiên.', 145000.00, 42, c.category_id, 1, 0, 1, 'img/SonTintInnisfree.png'
FROM categories c
WHERE c.category_name = 'Makeup' AND c.parent_id IS NULL
  AND (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id AND p.status = 1) < 4
  AND NOT EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.category_id AND p.product_name = 'Son dưỡng môi Innisfree');

INSERT INTO products (product_name, description, price, stock, category_id, brand_id, is_best_seller, status, featured_image_url)
SELECT 'Mascara Maybelline Sky High', 'Làm mi trông dài và cong hơn, đầu cọ dễ chuốt từng sợi mi.', 285000.00, 25, c.category_id, 3, 0, 1, 'img/product-placeholder.svg'
FROM categories c
WHERE c.category_name = 'Makeup' AND c.parent_id IS NULL
  AND (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id AND p.status = 1) < 4
  AND NOT EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.category_id AND p.product_name = 'Mascara Maybelline Sky High');

INSERT INTO products (product_name, description, price, stock, category_id, brand_id, is_best_seller, status, featured_image_url)
SELECT 'Phấn phủ kiềm dầu L’Oreal', 'Phấn phủ mỏng nhẹ giúp lớp trang điểm ráo mịn và bền màu.', 270000.00, 32, c.category_id, 4, 0, 1, 'img/product-placeholder.svg'
FROM categories c
WHERE c.category_name = 'Makeup' AND c.parent_id IS NULL
  AND (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id AND p.status = 1) < 4
  AND NOT EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.category_id AND p.product_name = 'Phấn phủ kiềm dầu L’Oreal');

INSERT INTO products (product_name, description, price, stock, category_id, brand_id, is_best_seller, status, featured_image_url)
SELECT 'Son kem lì Maybelline', 'Son kem lên màu đều, chất son nhẹ và hạn chế khô môi.', 265000.00, 38, c.category_id, 3, 1, 1, 'img/son.png'
FROM categories c
WHERE c.category_name = 'Son môi' AND c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Makeup' AND parent_id IS NULL)
  AND (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id AND p.status = 1) < 4
  AND NOT EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.category_id AND p.product_name = 'Son kem lì Maybelline');

INSERT INTO products (product_name, description, price, stock, category_id, brand_id, is_best_seller, status, featured_image_url)
SELECT 'Kem dưỡng phục hồi da Laneige', 'Dưỡng ẩm và hỗ trợ phục hồi hàng rào bảo vệ da.', 460000.00, 24, c.category_id, 2, 0, 1, 'img/kemduong.png'
FROM categories c
WHERE c.category_name = 'Kem dưỡng da' AND c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Skincare' AND parent_id IS NULL)
  AND (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id AND p.status = 1) < 4
  AND NOT EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.category_id AND p.product_name = 'Kem dưỡng phục hồi da Laneige');

INSERT INTO products (product_name, description, price, stock, category_id, brand_id, is_best_seller, status, featured_image_url)
SELECT 'Kem dưỡng ban đêm L’Oreal Revitalift', 'Kem dưỡng ban đêm giúp da mềm mịn và đủ ẩm sau một ngày dài.', 520000.00, 20, c.category_id, 4, 0, 1, 'img/kemduong.png'
FROM categories c
WHERE c.category_name = 'Kem dưỡng da' AND c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Skincare' AND parent_id IS NULL)
  AND (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id AND p.status = 1) < 4
  AND NOT EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.category_id AND p.product_name = 'Kem dưỡng ban đêm L’Oreal Revitalift');

INSERT INTO products (product_name, description, price, stock, category_id, brand_id, is_best_seller, status, featured_image_url)
SELECT 'Sữa rửa mặt tạo bọt Innisfree', 'Làm sạch nhẹ nhàng, loại bỏ bụi bẩn mà không làm da khô căng.', 195000.00, 40, c.category_id, 1, 1, 1, 'img/InnisfreeGreenTea.png'
FROM categories c
WHERE c.category_name = 'Sữa rửa mặt' AND c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Skincare' AND parent_id IS NULL)
  AND (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id AND p.status = 1) < 4
  AND NOT EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.category_id AND p.product_name = 'Sữa rửa mặt tạo bọt Innisfree');

INSERT INTO products (product_name, description, price, stock, category_id, brand_id, is_best_seller, status, featured_image_url)
SELECT 'Gel rửa mặt trà xanh Innisfree', 'Gel rửa mặt trà xanh giúp làm sạch da và mang lại cảm giác tươi mát.', 210000.00, 34, c.category_id, 1, 0, 1, 'img/InnisfreeGreenTea.png'
FROM categories c
WHERE c.category_name = 'Sữa rửa mặt' AND c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Skincare' AND parent_id IS NULL)
  AND (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id AND p.status = 1) < 4
  AND NOT EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.category_id AND p.product_name = 'Gel rửa mặt trà xanh Innisfree');

INSERT INTO products (product_name, description, price, stock, category_id, brand_id, is_best_seller, status, featured_image_url)
SELECT 'Nước tẩy trang dịu nhẹ Garnier', 'Làm sạch lớp trang điểm và bụi bẩn, phù hợp cho bước làm sạch hằng ngày.', 175000.00, 45, c.category_id, 5, 0, 1, 'img/taytrang.png'
FROM categories c
WHERE c.category_name = 'Sữa rửa mặt' AND c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Skincare' AND parent_id IS NULL)
  AND (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id AND p.status = 1) < 4
  AND NOT EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.category_id AND p.product_name = 'Nước tẩy trang dịu nhẹ Garnier');

INSERT INTO products (product_name, description, price, stock, category_id, brand_id, is_best_seller, status, featured_image_url)
SELECT 'Serum vitamin C L’Oreal', 'Serum dưỡng sáng da, hỗ trợ cải thiện vẻ xỉn màu và không đều màu.', 480000.00, 22, c.category_id, 4, 1, 1, 'img/product-placeholder.svg'
FROM categories c
WHERE c.category_name = 'Serum & Essence' AND c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Skincare' AND parent_id IS NULL)
  AND (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id AND p.status = 1) < 4
  AND NOT EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.category_id AND p.product_name = 'Serum vitamin C L’Oreal');

INSERT INTO products (product_name, description, price, stock, category_id, brand_id, is_best_seller, status, featured_image_url)
SELECT 'Tinh chất cấp ẩm Hyaluronic Acid', 'Bổ sung độ ẩm cho da, giúp bề mặt da trông căng mịn hơn.', 350000.00, 30, c.category_id, 5, 0, 1, 'img/product-placeholder.svg'
FROM categories c
WHERE c.category_name = 'Serum & Essence' AND c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Skincare' AND parent_id IS NULL)
  AND (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id AND p.status = 1) < 4
  AND NOT EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.category_id AND p.product_name = 'Tinh chất cấp ẩm Hyaluronic Acid');

INSERT INTO products (product_name, description, price, stock, category_id, brand_id, is_best_seller, status, featured_image_url)
SELECT 'Serum phục hồi da ban đêm', 'Serum dưỡng da ban đêm với kết cấu nhẹ, dễ thấm và dễ sử dụng.', 395000.00, 26, c.category_id, 2, 0, 1, 'img/product-placeholder.svg'
FROM categories c
WHERE c.category_name = 'Serum & Essence' AND c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Skincare' AND parent_id IS NULL)
  AND (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id AND p.status = 1) < 4
  AND NOT EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.category_id AND p.product_name = 'Serum phục hồi da ban đêm');

INSERT INTO products (product_name, description, price, stock, category_id, brand_id, is_best_seller, status, featured_image_url)
SELECT 'Mặt nạ giấy trà xanh Innisfree', 'Mặt nạ giấy cấp ẩm nhanh, mang lại cảm giác dễ chịu cho da.', 35000.00, 80, c.category_id, 1, 0, 1, 'img/InnisfreeGreenTea.png'
FROM categories c
WHERE c.category_name = 'Mặt nạ' AND c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Skincare' AND parent_id IS NULL)
  AND (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id AND p.status = 1) < 4
  AND NOT EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.category_id AND p.product_name = 'Mặt nạ giấy trà xanh Innisfree');

INSERT INTO products (product_name, description, price, stock, category_id, brand_id, is_best_seller, status, featured_image_url)
SELECT 'Mặt nạ đất sét làm sạch da', 'Hỗ trợ làm sạch dầu thừa và bụi bẩn, thích hợp dùng định kỳ.', 165000.00, 27, c.category_id, 4, 0, 1, 'img/product-placeholder.svg'
FROM categories c
WHERE c.category_name = 'Mặt nạ' AND c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Skincare' AND parent_id IS NULL)
  AND (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id AND p.status = 1) < 4
  AND NOT EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.category_id AND p.product_name = 'Mặt nạ đất sét làm sạch da');

INSERT INTO products (product_name, description, price, stock, category_id, brand_id, is_best_seller, status, featured_image_url)
SELECT 'Kem chống nắng Innisfree SPF 50+', 'Kem chống nắng SPF 50+ giúp bảo vệ da khi hoạt động ngoài trời.', 330000.00, 32, c.category_id, 1, 1, 1, 'img/product-placeholder.svg'
FROM categories c
WHERE c.category_name = 'Chống nắng' AND c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Skincare' AND parent_id IS NULL)
  AND (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id AND p.status = 1) < 4
  AND NOT EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.category_id AND p.product_name = 'Kem chống nắng Innisfree SPF 50+');

INSERT INTO products (product_name, description, price, stock, category_id, brand_id, is_best_seller, status, featured_image_url)
SELECT 'Kem chống nắng nâng tông', 'Kết cấu nhẹ, hỗ trợ bảo vệ da và làm sáng tông da tự nhiên.', 295000.00, 29, c.category_id, 5, 0, 1, 'img/product-placeholder.svg'
FROM categories c
WHERE c.category_name = 'Chống nắng' AND c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Skincare' AND parent_id IS NULL)
  AND (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id AND p.status = 1) < 4
  AND NOT EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.category_id AND p.product_name = 'Kem chống nắng nâng tông');

INSERT INTO products (product_name, description, price, stock, category_id, brand_id, is_best_seller, status, featured_image_url)
SELECT 'Xịt chống nắng tiện lợi', 'Dạng xịt dễ sử dụng, thuận tiện bổ sung lớp chống nắng trong ngày.', 255000.00, 24, c.category_id, 4, 0, 1, 'img/product-placeholder.svg'
FROM categories c
WHERE c.category_name = 'Chống nắng' AND c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Skincare' AND parent_id IS NULL)
  AND (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id AND p.status = 1) < 4
  AND NOT EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.category_id AND p.product_name = 'Xịt chống nắng tiện lợi');

INSERT INTO products (product_name, description, price, stock, category_id, brand_id, is_best_seller, status, featured_image_url)
SELECT 'Sữa dưỡng thể hương hoa', 'Dưỡng ẩm cho da cơ thể, để lại cảm giác mềm mại sau khi sử dụng.', 155000.00, 40, c.category_id, 5, 0, 1, 'img/product-placeholder.svg'
FROM categories c
WHERE c.category_name = 'Dưỡng cơ thể' AND c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Skincare' AND parent_id IS NULL)
  AND (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id AND p.status = 1) < 4
  AND NOT EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.category_id AND p.product_name = 'Sữa dưỡng thể hương hoa');

INSERT INTO products (product_name, description, price, stock, category_id, brand_id, is_best_seller, status, featured_image_url)
SELECT 'Kem dưỡng da tay Innisfree', 'Kem dưỡng da tay nhỏ gọn, giúp đôi tay mềm mại và đủ ẩm.', 79000.00, 55, c.category_id, 1, 0, 1, 'img/kem_duong_da_tay_55k.jpg'
FROM categories c
WHERE c.category_name = 'Dưỡng cơ thể' AND c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Skincare' AND parent_id IS NULL)
  AND (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id AND p.status = 1) < 4
  AND NOT EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.category_id AND p.product_name = 'Kem dưỡng da tay Innisfree');

INSERT INTO products (product_name, description, price, stock, category_id, brand_id, is_best_seller, status, featured_image_url)
SELECT 'Tẩy tế bào chết toàn thân', 'Hỗ trợ làm sạch tế bào chết, giúp da cơ thể mịn màng hơn.', 190000.00, 25, c.category_id, 4, 0, 1, 'img/product-placeholder.svg'
FROM categories c
WHERE c.category_name = 'Dưỡng cơ thể' AND c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Skincare' AND parent_id IS NULL)
  AND (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id AND p.status = 1) < 4
  AND NOT EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.category_id AND p.product_name = 'Tẩy tế bào chết toàn thân');

INSERT INTO products (product_name, description, price, stock, category_id, brand_id, is_best_seller, status, featured_image_url)
SELECT 'Kem nền L’Oreal True Match', 'Kem nền tiệp màu da, lớp nền mỏng nhẹ và tự nhiên.', 390000.00, 23, c.category_id, 4, 1, 1, 'img/product-placeholder.svg'
FROM categories c
WHERE c.category_name = 'Phấn nền' AND c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Makeup' AND parent_id IS NULL)
  AND (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id AND p.status = 1) < 4
  AND NOT EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.category_id AND p.product_name = 'Kem nền L’Oreal True Match');

INSERT INTO products (product_name, description, price, stock, category_id, brand_id, is_best_seller, status, featured_image_url)
SELECT 'Cushion che phủ tự nhiên', 'Phấn nước tiện dụng, cho lớp nền mỏng nhẹ và dễ dặm lại.', 360000.00, 20, c.category_id, 3, 0, 1, 'img/product-placeholder.svg'
FROM categories c
WHERE c.category_name = 'Phấn nền' AND c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Makeup' AND parent_id IS NULL)
  AND (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id AND p.status = 1) < 4
  AND NOT EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.category_id AND p.product_name = 'Cushion che phủ tự nhiên');

INSERT INTO products (product_name, description, price, stock, category_id, brand_id, is_best_seller, status, featured_image_url)
SELECT 'Kem nền Maybelline Super Stay', 'Lớp nền bền màu, độ che phủ tốt cho những dịp cần trang điểm lâu.', 345000.00, 21, c.category_id, 3, 0, 1, 'img/product-placeholder.svg'
FROM categories c
WHERE c.category_name = 'Phấn nền' AND c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Makeup' AND parent_id IS NULL)
  AND (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id AND p.status = 1) < 4
  AND NOT EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.category_id AND p.product_name = 'Kem nền Maybelline Super Stay');

INSERT INTO products (product_name, description, price, stock, category_id, brand_id, is_best_seller, status, featured_image_url)
SELECT 'Mascara L’Oreal Volume Million', 'Giúp hàng mi trông dày hơn, đầu cọ dễ chải và hạn chế vón cục.', 310000.00, 24, c.category_id, 4, 0, 1, 'img/product-placeholder.svg'
FROM categories c
WHERE c.category_name = 'Mascara' AND c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Makeup' AND parent_id IS NULL)
  AND (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id AND p.status = 1) < 4
  AND NOT EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.category_id AND p.product_name = 'Mascara L’Oreal Volume Million');

INSERT INTO products (product_name, description, price, stock, category_id, brand_id, is_best_seller, status, featured_image_url)
SELECT 'Mascara chống nước Maybelline', 'Mascara chống nước, giúp giữ độ cong mi trong nhiều giờ.', 275000.00, 31, c.category_id, 3, 1, 1, 'img/product-placeholder.svg'
FROM categories c
WHERE c.category_name = 'Mascara' AND c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Makeup' AND parent_id IS NULL)
  AND (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id AND p.status = 1) < 4
  AND NOT EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.category_id AND p.product_name = 'Mascara chống nước Maybelline');

INSERT INTO products (product_name, description, price, stock, category_id, brand_id, is_best_seller, status, featured_image_url)
SELECT 'Mascara làm dài mi', 'Tách mi rõ nét và giúp hàng mi trông dài, cong tự nhiên.', 235000.00, 27, c.category_id, 5, 0, 1, 'img/product-placeholder.svg'
FROM categories c
WHERE c.category_name = 'Mascara' AND c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Makeup' AND parent_id IS NULL)
  AND (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id AND p.status = 1) < 4
  AND NOT EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.category_id AND p.product_name = 'Mascara làm dài mi');

INSERT INTO products (product_name, description, price, stock, category_id, brand_id, is_best_seller, status, featured_image_url)
SELECT 'Phấn má hồng dạng kem 3CE', 'Chất kem dễ tán, tạo sắc hồng tự nhiên cho đôi má.', 280000.00, 22, c.category_id, 3, 0, 1, 'img/product-placeholder.svg'
FROM categories c
WHERE c.category_name = 'Phấn má hồng' AND c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Makeup' AND parent_id IS NULL)
  AND (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id AND p.status = 1) < 4
  AND NOT EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.category_id AND p.product_name = 'Phấn má hồng dạng kem 3CE');

INSERT INTO products (product_name, description, price, stock, category_id, brand_id, is_best_seller, status, featured_image_url)
SELECT 'Phấn má hồng đào', 'Tông màu đào tươi tắn, dễ phối với nhiều kiểu trang điểm.', 210000.00, 35, c.category_id, 5, 0, 1, 'img/product-placeholder.svg'
FROM categories c
WHERE c.category_name = 'Phấn má hồng' AND c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Makeup' AND parent_id IS NULL)
  AND (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id AND p.status = 1) < 4
  AND NOT EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.category_id AND p.product_name = 'Phấn má hồng đào');

INSERT INTO products (product_name, description, price, stock, category_id, brand_id, is_best_seller, status, featured_image_url)
SELECT 'Phấn má hồng lì L’Oreal', 'Phấn má mịn nhẹ, lên màu vừa phải và dễ tán đều.', 245000.00, 26, c.category_id, 4, 0, 1, 'img/product-placeholder.svg'
FROM categories c
WHERE c.category_name = 'Phấn má hồng' AND c.parent_id = (SELECT category_id FROM categories WHERE category_name = 'Makeup' AND parent_id IS NULL)
  AND (SELECT COUNT(*) FROM products p WHERE p.category_id = c.category_id AND p.status = 1) < 4
  AND NOT EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.category_id AND p.product_name = 'Phấn má hồng lì L’Oreal');
