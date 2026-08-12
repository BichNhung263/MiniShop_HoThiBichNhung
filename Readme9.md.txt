1. $limit, $page, $offset dùng để làm gì?
$limit: số bản ghi hiển thị
$page: trang hiển thị
$offset: số bản ghi bỏ qua trước khi lấy dữ liệu

2. Vì sao cần ceil() khi tính $totalPages?
celi(): dùng để làm tròn bản ghi khi số trang không chia hết

3. LIMIT và OFFSET trong SQL có tác dụng gì?
limit: giới hạn số bản ghi trả về
offset: bỏ qua số bản ghi đầu

4. Vì sao khi chuyển trang phải giữ limit trên URL?
để khi sang trang khác, số lượng hiển thị bản ghi vẫn được giữ nguyên

5. Vì sao khi tìm kiếm phải giữ keyword khi chuyển trang?
nếu không giữ thì trang sau sẽ mất bộ lọc tìm kiếm 

6. count() dùng để làm gì trong chức năng phân trang?
count(): đếm tổng số bản ghi phù hợp để tính tổng trang

7. Vì sao nên tái sử dụng getPage() thay vì tạo getPageByKeyword() riêng?
dùng getPage() nó ngắn gọn, tránh trùng lặp

8. Khi tìm kiếm không có kết quả thì $totalPages có giá trị bao nhiêu?
$totalPages: thường trả về 0 hoặc 1 tùy theo cách xác định hiển thị dữ liệu 

9. sort dùng để làm gì?
sort: dùng để xác định cách sắp xếp dữ liệu

10.Khi kết hợp tìm kiếm + sắp xếp + phân trang, những tham số nào cần được giữ trên
URL?
cần giữ là keyword, sort, page, limit