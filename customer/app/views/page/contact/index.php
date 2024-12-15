<!-- Breadcrumb Begin -->
<div class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb__links">
                    <a href="/"><i class="fa fa-home"></i> Home</a>
                    <span>Contact</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Contact Section Begin -->
<section class="contact spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="contact__content">
                    <div class="contact__address">
                        <h5>Thông tin liên hệ</h5>
                        <ul>
                            <li>
                                <h6><i class="fa fa-map-marker"></i> Địa chỉ</h6>
                                <p>12 Nguyễn Văn Bảo, phường 4, quận Gò Vấp, TpHCM</p>
                            </li>
                            <li>
                                <h6><i class="fa fa-phone"></i> SĐT</h6>
                                <p><span>0899492279</span><span>0837794727</span></p>
                            </li>
                            <li>
                                <h6><i class="fa fa-headphones"></i> Email Hỗ trợ</h6>
                                <p>phamtuanvt2015@gmail.com</p>
                                <p>alolatrieuvlog1208@gmail.com</p>
                            </li>
                        </ul>
                    </div>
                    <div class="contact__form">
                        <h5>Nội dung tin nhắn</h5>
                        <form id="contactForm">
                            <input type="text" name="name" placeholder="Name" required>
                            <input type="email" name="email" placeholder="Email" required>
                            <textarea name="message" placeholder="Message" required></textarea>
                            <button type="submit" class="site-btn">Gửi tin nhắn</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="contact__map">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3918.85816909105!2d106.68427047451765!3d10.822164158349457!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3174deb3ef536f31%3A0x8b7bb8b7c956157b!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBDw7RuZyBuZ2hp4buHcCBUUC5IQ00!5e0!3m2!1svi!2s!4v1716395757132!5m2!1svi!2s" 
                        width="600" height="450" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Contact Section End -->

<script>
$(document).ready(function() {
    $('#contactForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = {
            name: $('input[name="name"]').val(),
            email: $('input[name="email"]').val(),
            message: $('textarea[name="message"]').val()
        };

        $.ajax({
            url: '/phone-ecommerce-chat/customer/contact/store',
            method: 'POST',
            data: formData,
            success: function(res) {
                if (res.status === 200) {
                    showToast(res.message, true);
                    $('#contactForm')[0].reset();
                } else {
                    showToast(res.message || 'Có lỗi xảy ra', false);
                }
            },
            error: function() {
                showToast('Không thể gửi tin nhắn', false);
            }
        });
    });
});
</script>