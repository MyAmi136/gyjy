SET NAMES utf8mb4;

UPDATE products
SET description = CASE product_id
    WHEN 9 THEN 'Dưỡng ẩm sâu, giữ da căng mịn suốt cả ngày.'
    WHEN 10 THEN 'Serum cấp ẩm và làm dịu da sau mụn hiệu quả.'
    WHEN 11 THEN 'Mặt nạ ngủ dưỡng ẩm mạnh, giúp da mềm mịn và sáng khỏe.'
    WHEN 12 THEN 'Chống nắng phổ rộng, bảo vệ da khỏi tia UV và ánh sáng xanh.'
    WHEN 13 THEN 'Dưỡng ẩm cơ thể, làm mềm da và giảm khô ráp nhanh.'
    WHEN 14 THEN 'Phấn nền mỏng mịn, che khuyết điểm tự nhiên và bền màu.'
    WHEN 15 THEN 'Mascara làm dày mi, giúp mi cong tự nhiên và đôi mắt trông to hơn.'
    WHEN 16 THEN 'Màu hồng tươi tự nhiên, dễ tán và lên màu đẹp.'
    WHEN 17 THEN 'Son môi dưỡng ẩm, màu sắc tự nhiên và lâu trôi.'
    WHEN 18 THEN 'Dưỡng da tay mềm mịn, ngăn nứt nẻ hiệu quả.'
    WHEN 19 THEN 'Mặt nạ tẩy tế bào chết giúp da sáng mịn và mềm mại.'
    ELSE description
END
WHERE product_id BETWEEN 9 AND 19;

UPDATE products
SET product_name = 'Mặt nạ tái tạo Colorkey 10K'
WHERE product_id = 6;

UPDATE products
SET product_name = 'Nước tẩy trang Garnier dành cho da dầu và mụn 400ml (Mới)'
WHERE product_id = 7;

UPDATE posts
SET
    title = CASE post_id
        WHEN 4 THEN '5 bước skincare mùa hè cho da dầu'
        WHEN 5 THEN 'Cách chọn son môi phù hợp với sắc da'
        WHEN 6 THEN 'Routine makeup tối giản trong 10 phút'
        WHEN 7 THEN 'Top 5 serum nên dùng cho da khô'
        WHEN 8 THEN 'Dưỡng da từ bên trong: ngủ đủ giấc và uống nước'
        WHEN 9 THEN 'Mẹo bảo vệ da khi đi ra nắng'
        ELSE title
    END,
    content = CASE post_id
        WHEN 4 THEN 'Mùa hè thường khiến da dễ đổ dầu và kích ứng. Hãy bắt đầu với sữa rửa mặt dịu nhẹ, toner cấp ẩm, serum niacinamide, kem dưỡng mỏng nhẹ và kem chống nắng SPF 50+. Duy trì đều đặn sẽ giúp da khỏe và hạn chế bóng nhờn.'
        WHEN 5 THEN 'Nếu da bạn có tông sáng, nên ưu tiên son nude, hồng nhạt hoặc cam đào dịu. Da trung bình và da ngăm có thể thử son đỏ nâu, nâu đất hoặc hồng rượu để tạo điểm nhấn tự nhiên.'
        WHEN 6 THEN 'Một quy trình nhanh gọn cho ngày làm việc gồm kem dưỡng, kem nền mỏng, phấn phủ, mascara và son môi màu nude. Cách này giúp bạn trông tươi tắn mà không cần trang điểm nhiều lớp.'
        WHEN 7 THEN 'Da khô cần serum dưỡng ẩm và khóa nước. Hãy chọn serum chứa hyaluronic acid, glycerin, panthenol hoặc ceramide để da bớt khô ráp và căng mịn hơn.'
        WHEN 8 THEN 'Duy trì thói quen ngủ đủ 7–8 tiếng và uống nước đều đặn là cách đơn giản giúp da sáng và khỏe hơn. Đây là nền tảng quan trọng bên cạnh quy trình chăm sóc da.'
        WHEN 9 THEN 'Ngoài kem chống nắng, bạn nên mang khẩu trang, đeo kính râm và hạn chế ra nắng trong khoảng 11 giờ đến 15 giờ. Đội mũ hoặc mặc áo khoác cũng giúp giảm tác hại của tia UV lên da.'
        ELSE content
    END
WHERE post_id BETWEEN 4 AND 9;
