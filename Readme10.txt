1. Middleware
a. AuthMiddleware: Kiểm tra đã đăng nhập chưa
b. GuestMiddleware: Kiểm tra đã đăng nhập thì không cho vào Login.
c. CsrfMiddleware: Tạo và kiểm tra CSRF Token.

2. Session và Cookie:
a. Phân biệt Session và Cookie.
session: lưu server
cookie: lưu trữ phía trình duyệt (client)

b. $_SESSION["user"] dùng để làm gì?
giữ trạng thái người dùng đã xác thực giữ các request

c. GET/POST truyền dữ liệu khác gì với Session?
GET/POST khác Session là get và post truyền dữ liệu trong request hiện tại, session thì tồn tại qua nhiều request và không hiển thị công khai
 
d. Trình bày quá trình Session hoạt động khi người dùng chuyển từ Request này sang Request khác.
khi người dùng đăng nhập thành công, serve tạo $_Session và serve gửi Cookie PHPSESSID và rồi trình duyệt trả lại PHPSESSID, serve dùng id đó để load dữ liệu session
 
3. Đăng nhập, bảo mật và phân quyền:
a. password_verify() dùng để làm gì?
dùng để so sánh mật khẩu plain với hash trong DB

b. Bcrypt là gì? Vì sao mật khẩu trong Database cần được lưu dưới dạng hash bằng Bcrypt? Liệt kê một số thuật toán hash mật khẩu khác.
c. Vì sao cần kiểm tra Session trước khi cho phép truy cập Admin?
vì khi kiểm tra sesion để đảm bảo user được xác thực và có role hợp lệ mới truy cập và admin

d. Khi đăng xuất, cần thực hiện những thao tác nào với Session?
gọi session_unset()/session_destroy() và nếu có cookie ghi nhớ thì xóa token

e. CSRF Token dùng để làm gì?
CSRF Token: ngăn yêu cầu giả mạo bằng token được lưu trong sission và kiểm tra khi nhận POST

f. Điều gì xảy ra khi CSRF Token không hợp lệ?
CSFR Token không hợp lệ: từ chối request- không thực hiện được

g. Phân biệt Authentication và Authorization. Cho ví dụ trong hệ thống MiniShop.
Authentication: là xác thực. Xác định user là ai
VD: user đăng nhập thành công bằng username và password -> set  $_SESSION["user"]
Authorization: là phân quyền. Xác định user đó được làm gì
VD: $_SESSION["user"]->role===1 thì sẽ được truy cập vào 